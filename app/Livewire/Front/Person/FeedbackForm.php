<?php

namespace App\Livewire\Front\Person;

use App\Models\Feedback;
use App\Models\FeedbackOTP;
use App\Models\People;
use App\Helpers\FeedbackHelper;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.front')]
class FeedbackForm extends Component
{
    public $person;
    public $currentStep = 1;
    public $otpSent = false;
    public $emailVerified = false;
    public $sendingOtp = false;
    public $verifyingOtp = false;
    public $submittingFeedback = false;
    public $remainingOTPRequests = 3;
    public $timeUntilNextOTP = 0;

    public $isEmailBlocked = false;

    // Step 1: Email Verification
    public $email = '';
    public $otp = '';

    // Step 2: Basic Information
    public $name = '';
    public $type = 'suggestion';

    // Step 3: Feedback Details
    public $message = '';

    // Step 4: Suggested Changes
    public $suggestedName = '';
    public $suggestedProfession = '';
    public $suggestedBirthDate = '';
    public $suggestedDeathDate = '';
    public $suggestedNationality = '';
    public $suggestedAbout = '';

    protected $rules = [
        // Step 1
        'email' => 'required|email|max:255',
        
        // Step 2
        'name' => 'required|string|max:255',
        'type' => 'required|in:correction,addition,update,suggestion',
        
        // Step 3
        'message' => 'required|string|min:10|max:2000',
    ];

    protected $otpRules = [
        'otp' => 'required|digits:6'
    ];

    public function mount($slug)
    {
        $this->person = People::active()
            ->verified()
            ->where('slug', $slug)
            ->first();

        if (!$this->person) {
            abort(404, 'Person not found');
        }

        // Check if user has a valid session
        if (FeedbackHelper::isSessionValid()) {
            $this->email = FeedbackHelper::getVerifiedEmail();
            $this->emailVerified = true;
            $this->currentStep = 2;
            session()->flash('success', 'Welcome back! Your email is already verified.');
        }

        $this->updateOTPRateLimitInfo();
        $this->setMetaTags();
    }

    /**
     * Update OTP rate limit information
     */
    // In your updateOTPRateLimitInfo method
    public function updateOTPRateLimitInfo()
    {
        if ($this->email) {
            $this->remainingOTPRequests = FeedbackHelper::getRemainingOTPRequests($this->email);
            $this->timeUntilNextOTP = FeedbackHelper::getTimeUntilNextOTP($this->email);
            $this->isEmailBlocked = FeedbackHelper::isEmailBlocked($this->email);
            
            // For debugging - you can log this
            $rateLimitInfo = FeedbackHelper::getRateLimitMessage($this->email);
        } else {
            $this->remainingOTPRequests = 3;
            $this->timeUntilNextOTP = 0;
            $this->isEmailBlocked = false;
        }
    }

    /**
     * When email changes, update rate limit info
     */
    public function updatedEmail($value)
    {
        $this->updateOTPRateLimitInfo();
    }
    private function setMetaTags()
{
    if (!$this->person || !is_object($this->person)) {
        return;
    }

    $personName = $this->person->name ?? 'Unknown Person';
    $personSlug = $this->person->slug ?? 'unknown';

    $title = "Suggest Edit for {$personName} - Help Improve Profile";
    $description = "Help us keep {$personName}'s profile accurate and up-to-date. Suggest corrections, additions, or updates to improve this biography.";
    $url = route('people.suggest-edit', $personSlug);

    meta()
        ->set('title', $title)
        ->set('description', $description)
        ->set('canonical', $url)
        ->set('robots', 'noindex,follow');

    meta()
        ->set('og:title', $title)
        ->set('og:description', $description)
        ->set('og:url', $url)
        ->set('og:type', 'website');

    meta()
        ->set('twitter:card', 'summary')
        ->set('twitter:title', $title)
        ->set('twitter:description', $description);
}

    public function nextStep()
    {
        $this->validateCurrentStep();

        if ($this->currentStep < 4) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function validateCurrentStep()
    {
        $stepRules = [
            1 => ['email' => 'required|email|max:255'],
            2 => [
                'name' => 'required|string|max:255',
                'type' => 'required|in:correction,addition,update,suggestion',
            ],
            3 => ['message' => 'required|string|min:10|max:2000'],
        ];

        if (isset($stepRules[$this->currentStep])) {
            $this->validate($stepRules[$this->currentStep]);
        }
    }

    public function sendOTP()
{
    $this->validate(['email' => 'required|email|max:255']);

    // Check if the specific email is blocked using database
    if (FeedbackHelper::isEmailBlocked($this->email)) {
        $this->updateOTPRateLimitInfo();
        
        // Use the helper method for consistent messaging
        session()->flash('error', FeedbackHelper::getRateLimitMessage($this->email));
        return;
    }

    // Check rate limiting for current request using database
    if (!FeedbackHelper::canRequestOTP($this->email)) {
        $this->updateOTPRateLimitInfo();
        session()->flash('error', FeedbackHelper::getRateLimitMessage($this->email));
        return;
    }

    $this->sendingOtp = true;

    try {
        // Generate OTP first
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP with default type - THIS CREATES THE RECORD IN DATABASE
        $otp = FeedbackOTP::create([
            'email' => $this->email,
            'otp_code' => $otpCode,
            'people_id' => $this->person->id,
            'type' => 'suggestion',
            'expires_at' => now()->addMinutes(30),
            'ip_address' => request()->ip(),
        ]);

        // Update rate limit info after creating the record
        $this->updateOTPRateLimitInfo();

        // Send OTP email using Google SMTP
        Mail::send('emails.feedback-otp', [
            'otp' => $otpCode,
            'person' => $this->person,
            'expires_at' => $otp->expires_at,
        ], function ($message) {
            $message->to($this->email)
                    ->subject('Verify Your Email - ' . config('app.name'));
        });

        $this->otpSent = true;
        $this->sendingOtp = false;
        
        $this->dispatch('otp-sent');
        session()->flash('otp_message', 'Verification code sent to your email. Please check your inbox.');

    } catch (\Exception $e) {
        Log::error('Failed to send OTP email: ' . $e->getMessage());
        $this->sendingOtp = false;
        session()->flash('error', 'Failed to send verification code. Please try again.');
    }
}

    public function verifyOTP()
    {
        $this->validate($this->otpRules);
        $this->verifyingOtp = true;

        try {
            $otpRecord = FeedbackOTP::where('email', $this->email)
                ->where('otp_code', $this->otp)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->first();

            if (!$otpRecord) {
                $this->addError('otp', 'Invalid or expired verification code.');
                $this->verifyingOtp = false;
                return;
            }

            // Mark OTP as used
            $otpRecord->update(['is_used' => true]);

            // Set session for 1 hour
            FeedbackHelper::setVerifiedSession($this->email);

            $this->emailVerified = true;
            $this->currentStep = 2;
            $this->verifyingOtp = false;
            
            // Clear any session messages to show navigation buttons
            session()->forget(['success', 'error', 'otp_message']);
            
            $this->dispatch('email-verified');

        } catch (\Exception $e) {
            Log::error('OTP verification error: ' . $e->getMessage());
            $this->verifyingOtp = false;
            session()->flash('error', 'Verification failed. Please try again.');
        }
    }

    public function resendOTP()
    {
        $this->sendOTP();
        session()->flash('otp_message', 'New verification code sent to your email.');
    }

    public function submitFeedback()
    {
        $this->validate();
        $this->submittingFeedback = true;

        try {
            // Create feedback record
            Feedback::create([
                'people_id' => $this->person->id,
                'name' => $this->name,
                'email' => $this->email,
                'type' => $this->type,
                'message' => $this->message,
                'suggested_changes' => $this->getSuggestedChanges(),
                'email_verified_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $this->submittingFeedback = false;
            $this->dispatch('feedback-submitted');
            session()->flash('success', 'Thank you for your feedback! We will review your suggestion and update the information accordingly.');
            
            // Reset form but keep session
            $this->resetForm();

        } catch (\Exception $e) {
            Log::error('Feedback submission error: ' . $e->getMessage());
            $this->submittingFeedback = false;
            session()->flash('error', 'Failed to submit feedback. Please try again.');
        }
    }

    /**
     * Clear session and start over
     */
    public function clearSession()
    {
        FeedbackHelper::clearSession();
        $this->resetForm();
        session()->flash('success', 'Session cleared. Please verify your email again.');
    }

    private function getSuggestedChanges()
    {
        $changes = [];

        if ($this->suggestedName) $changes['name'] = $this->suggestedName;
        if ($this->suggestedProfession) $changes['profession'] = $this->suggestedProfession;
        if ($this->suggestedBirthDate) $changes['birth_date'] = $this->suggestedBirthDate;
        if ($this->suggestedDeathDate) $changes['death_date'] = $this->suggestedDeathDate;
        if ($this->suggestedNationality) $changes['nationality'] = $this->suggestedNationality;
        if ($this->suggestedAbout) $changes['about'] = $this->suggestedAbout;

        return !empty($changes) ? $changes : null;
    }

    private function resetForm()
    {
        $this->reset([
            'otp', 'name', 'type', 'message',
            'suggestedName', 'suggestedProfession', 'suggestedBirthDate',
            'suggestedDeathDate', 'suggestedNationality', 'suggestedAbout'
        ]);
        $this->currentStep = 1;
        $this->otpSent = false;
        $this->emailVerified = false;
        $this->sendingOtp = false;
        $this->verifyingOtp = false;
        $this->submittingFeedback = false;
        
        // Don't reset email if session is valid
        if (!FeedbackHelper::isSessionValid()) {
            $this->reset(['email']);
        }
    }

    public function render()
    {
        if (!$this->person) {
            return view('livewire.front.person.feedback-error', [
                'message' => 'Person data is invalid. Please try again.'
            ]);
        }

        return view('livewire.front.person.feedback-form', [
            'hasValidSession' => FeedbackHelper::isSessionValid()
        ]);
    }
}
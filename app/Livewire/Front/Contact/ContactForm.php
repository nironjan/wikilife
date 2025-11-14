<?php

namespace App\Livewire\Front\Contact;

use App\Models\ContactMessage;
use App\Models\ContactOTP;
use App\Helpers\ContactHelper;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.front')]
#[Title('Contact Us - Get in Touch')]
class ContactForm extends Component
{
    public $currentStep = 1;
    public $otpSent = false;
    public $emailVerified = false;
    public $sendingOtp = false;
    public $verifyingOtp = false;
    public $submittingMessage = false;
    public $remainingOTPRequests = 3;
    public $timeUntilNextOTP = 0;
    public $isEmailBlocked = false;

    // Step 1: Email Verification
    public $email = '';
    public $otp = '';

    // Step 2: Contact Information
    public $name = '';
    public $subject = '';
    public $type = 'general';

    // Step 3: Message Details
    public $message = '';

    protected $rules = [
        'email' => 'required|email|max:255',
        'name' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'type' => 'required|in:general,support,advertise,other',
        'message' => 'required|string|min:10|max:5000',
    ];

    protected $otpRules = [
        'otp' => 'required|digits:6'
    ];

    public function mount()
    {
        if (ContactHelper::isSessionValid()) {
            $this->email = ContactHelper::getVerifiedEmail();
            $this->emailVerified = true;
            $this->currentStep = 2;
        }

        $this->updateOTPRateLimitInfo();
        $this->setMetaTags();
    }

    public function updateOTPRateLimitInfo()
    {
        if ($this->email) {
            $this->remainingOTPRequests = ContactHelper::getRemainingOTPRequests($this->email);
            $this->timeUntilNextOTP = ContactHelper::getTimeUntilNextOTP($this->email);
            $this->isEmailBlocked = ContactHelper::isEmailBlocked($this->email);
        } else {
            $this->remainingOTPRequests = 3;
            $this->timeUntilNextOTP = 0;
            $this->isEmailBlocked = false;
        }
    }

    public function updatedEmail($value)
    {
        $this->updateOTPRateLimitInfo();

        // Check if this email was previously verified (even if session was cleared)
        if ($this->isEmailRecentlyVerified($value)) {
            $this->emailVerified = true;
            $this->currentStep = 2;
            // Restore the session for this email
            ContactHelper::setVerifiedSession($value);
            session()->flash('success', 'Email automatically verified. Welcome back!');
        }
    }

    /**
     * Check if an email was verified in the last hour (even if session was cleared)
     */
    private function isEmailRecentlyVerified($email)
    {
        // Check if there's a contact message with this email verified in the last hour
        return ContactMessage::where('email', $email)
            ->where('email_verified_at', '>=', now()->subHour())
            ->exists();
    }

    private function setMetaTags()
    {
        $title = "Contact Us - Get in Touch";
        $description = "Have questions or feedback? Reach out to us through our contact form. We're here to help and would love to hear from you.";
        $url = route('contact');

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

        if ($this->currentStep < 3) {
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
                'subject' => 'required|string|max:255',
                'type' => 'required|in:general,support,advertise,other',
            ],
            3 => ['message' => 'required|string|min:10|max:5000'],
        ];

        if (isset($stepRules[$this->currentStep])) {
            $this->validate($stepRules[$this->currentStep]);
        }
    }

    public function changeEmail()
    {
        $currentEmail = $this->email;

        // Clear the session but keep the email
        ContactHelper::clearSession();
        $this->resetForm();
        $this->email = $currentEmail; // Keep the email filled in

        // If this email was recently verified, auto-verify it again
        if ($this->isEmailRecentlyVerified($currentEmail)) {
            $this->emailVerified = true;
            $this->currentStep = 2;
            ContactHelper::setVerifiedSession($currentEmail);
            session()->flash('success', 'Email automatically verified. Welcome back!');
        } else {
            session()->flash('success', 'Email verification cleared. You can now enter a different email address.');
        }
    }

    public function sendOTP()
    {
        $this->validate(['email' => 'required|email|max:255']);

        // Immediately set sendingOtp to true to disable the button
        $this->sendingOtp = true;

        // Check if the specific email is blocked using database
        if (ContactHelper::isEmailBlocked($this->email)) {
            $this->updateOTPRateLimitInfo();
            session()->flash('error', ContactHelper::getRateLimitMessage($this->email));
            $this->sendingOtp = false; // Re-enable button
            return;
        }

        // Check rate limiting for current request using database
        if (!ContactHelper::canRequestOTP($this->email)) {
            $this->updateOTPRateLimitInfo();
            session()->flash('error', ContactHelper::getRateLimitMessage($this->email));
            $this->sendingOtp = false; // Re-enable button
            return;
        }

        try {
            // Generate OTP
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store OTP
            $otp = ContactOTP::create([
                'email' => $this->email,
                'otp_code' => $otpCode,
                'type' => 'contact',
                'expires_at' => now()->addMinutes(30),
                'ip_address' => request()->ip(),
            ]);

            // Update rate limit info after creating the record
            $this->updateOTPRateLimitInfo();

            // Send OTP email
            Mail::send('emails.contact-otp', [
                'otp' => $otpCode,
                'expires_at' => $otp->expires_at,
            ], function ($message) {
                $message->to($this->email)
                        ->subject('Verify Your Email - Contact Form - ' . config('app.name'));
            });

            $this->otpSent = true;
            $this->sendingOtp = false; // Re-enable button after successful send

            $this->dispatch('otp-sent');
            session()->flash('otp_message', 'Verification code sent to your email. Please check your inbox.');

        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
            $this->sendingOtp = false; // Re-enable button on error
            session()->flash('error', 'Failed to send verification code. Please try again.');
        }
    }

    public function verifyOTP()
    {
        $this->validate($this->otpRules);
        $this->verifyingOtp = true;

        try {
            $otpRecord = ContactOTP::where('email', $this->email)
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
            ContactHelper::setVerifiedSession($this->email);

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

    public function submitMessage()
    {
        $this->validate();
        $this->submittingMessage = true;

        try {
            ContactMessage::create([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'type' => $this->type,
                'message' => $this->message,
                'email_verified_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $this->submittingMessage = false;
            $this->dispatch('message-submitted');
            $this->resetForm();
            session()->flash('success_message', 'Thank you for your message! We will get back to you as soon as possible.');

        } catch (\Exception $e) {
            Log::error('Contact message submission error: ' . $e->getMessage());
            $this->submittingMessage = false;
            session()->flash('error', 'Failed to submit your message. Please try again.');
        }
    }

    public function clearSession()
    {
        ContactHelper::clearSession();
        $this->resetForm();
        session()->flash('success', 'Session cleared. Please verify your email again.');
    }

    private function resetForm()
    {
        $this->reset([
            'otp', 'name', 'subject', 'type', 'message'
        ]);
        $this->currentStep = 2;
        $this->otpSent = false;
        $this->emailVerified = true;
        $this->sendingOtp = false;
        $this->verifyingOtp = false;
        $this->submittingMessage = false;

        if (!ContactHelper::isSessionValid()) {
            $this->reset(['email']);
            $this->emailVerified = false;
            $this->currentStep = 1;
        }
    }

    public function render()
    {
        return view('livewire.front.contact.contact-form', [
            'hasValidSession' => ContactHelper::isSessionValid()
        ]);
    }
}

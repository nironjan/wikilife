<?php

namespace App\Livewire\Admin\Feedback;

use App\Models\Feedback;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Mail;

class Manage extends Component
{
    public ?int $editingId = null;
    public $feedback;

    // Feedback fields
    public $people_id = '';
    public $name = '';
    public $email = '';
    public $type = 'suggestion';
    public $message = '';
    public $suggested_changes = [];
    public $status = 'pending';
    public $email_verified_at = '';

    // Suggested changes fields
    public $suggested_name = '';
    public $suggested_profession = '';
    public $suggested_birth_date = '';
    public $suggested_death_date = '';
    public $suggested_nationality = '';
    public $suggested_about = '';

    protected function rules()
    {
        return [
            'status' => 'required|in:pending,approved,rejected',
        ];
    }

    public function mount($id)
    {
        $this->editingId = $id;
        $this->loadFeedback($id);
    }

    public function loadFeedback($id)
    {
        // Use eager loading to get the person relationship
        $this->feedback = Feedback::with('person')->findOrFail($id);

        $this->people_id = $this->feedback->people_id;
        $this->name = $this->feedback->name;
        $this->email = $this->feedback->email;
        $this->type = $this->feedback->type;
        $this->message = $this->feedback->message;
        $this->status = $this->feedback->status;
        $this->email_verified_at = optional($this->feedback->email_verified_at)->format('Y-m-d\TH:i');
        
        // Load suggested changes
        $this->suggested_changes = $this->feedback->suggested_changes ?? [];
        $this->loadSuggestedChanges();
    }

    private function loadSuggestedChanges()
    {
        $this->suggested_name = $this->suggested_changes['name'] ?? '';
        $this->suggested_profession = $this->suggested_changes['profession'] ?? '';
        $this->suggested_birth_date = $this->suggested_changes['birth_date'] ?? '';
        $this->suggested_death_date = $this->suggested_changes['death_date'] ?? '';
        $this->suggested_nationality = $this->suggested_changes['nationality'] ?? '';
        $this->suggested_about = $this->suggested_changes['about'] ?? '';
    }

    public function save()
    {
        $this->validate();

        try {
            $feedback = Feedback::findOrFail($this->editingId);
            $oldStatus = $feedback->status;
            
            $feedback->update([
                'status' => $this->status,
            ]);

            // Send email notification when status changes to approved
            if ($this->status === 'approved' && $oldStatus !== 'approved') {
                $this->sendApprovalEmail($feedback);
            }

            Toaster::success('Feedback updated successfully.');

            return redirect()->route('webmaster.feedback.index');

        } catch (\Exception $e) {
            Toaster::error('Failed to update feedback: ' . $e->getMessage());
        }
    }

    private function sendApprovalEmail(Feedback $feedback)
    {
        try {
            Mail::send('emails.feedback-approved', [
                'feedback' => $feedback,
                'person' => $feedback->person,
            ], function ($message) use ($feedback) {
                $message->to($feedback->email)
                        ->subject('Your Feedback Has Been Approved - ' . config('app.name'));
            });

            Toaster::success("Approval email sent to {$feedback->email}.");
        } catch (\Exception $e) {
            Toaster::error("Failed to send approval email: " . $e->getMessage());
        }
    }

    public function getPersonProperty()
    {
        if ($this->people_id) {
            return \App\Models\People::find($this->people_id);
        }
        return null;
    }



    public function render()
    {
        $types = [
            'correction' => 'Correction',
            'addition' => 'Addition', 
            'update' => 'Update',
            'suggestion' => 'Suggestion'
        ];

        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ];

        return view('livewire.admin.feedback.manage', compact('types', 'statuses'));
    }
}
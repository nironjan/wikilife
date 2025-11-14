<?php

namespace App\Livewire\Admin\Contact;

use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{
    public ?int $editingId = null;
    public $contactMessage;

    // Contact message fields
    public $name = '';
    public $email = '';
    public $subject = '';
    public $type = 'general';
    public $message = '';
    public $status = 'pending';
    public $email_verified_at = '';
    public $ip_address = '';
    public $user_agent = '';

    protected function rules()
    {
        return [
            'status' => 'required|in:pending,in_progress,responded,closed',
        ];
    }

    public function mount($id)
    {
        $this->editingId = $id;
        $this->loadContactMessage($id);
    }

    public function loadContactMessage($id)
    {
        $this->contactMessage = ContactMessage::findOrFail($id);

        $this->name = $this->contactMessage->name;
        $this->email = $this->contactMessage->email;
        $this->subject = $this->contactMessage->subject;
        $this->type = $this->contactMessage->type;
        $this->message = $this->contactMessage->message;
        $this->status = $this->contactMessage->status;
        $this->email_verified_at = optional($this->contactMessage->email_verified_at)->format('Y-m-d\TH:i');
        $this->ip_address = $this->contactMessage->ip_address;
        $this->user_agent = $this->contactMessage->user_agent;
    }

    public function save()
    {
        $this->validate();

        try {
            $contactMessage = ContactMessage::findOrFail($this->editingId);
            $oldStatus = $contactMessage->status;

            $contactMessage->update([
                'status' => $this->status,
            ]);

            // Send email notification when status changes to responded
            if ($this->status === 'responded' && $oldStatus !== 'responded') {
                $this->sendResponseEmail($contactMessage);
            }

            Toaster::success('Contact message updated successfully.');

            return redirect()->route('webmaster.contact.index');

        } catch (\Exception $e) {
            Toaster::error('Failed to update contact message: ' . $e->getMessage());
        }
    }

    private function sendResponseEmail(ContactMessage $contactMessage)
    {
        try {
            Mail::send('emails.contact-responded', [
                'contactMessage' => $contactMessage,
            ], function ($message) use ($contactMessage) {
                $message->to($contactMessage->email)
                        ->subject('We\'ve Responded to Your Inquiry - ' . config('app.name'));
            });

            Toaster::success("Response email sent to {$contactMessage->email}.");
        } catch (\Exception $e) {
            Toaster::error("Failed to send response email: " . $e->getMessage());
        }
    }


    public function render()
    {
        $types = [
            'general' => 'General Inquiry',
            'support' => 'Support',
            'partnership' => 'Partnership',
            'technical' => 'Technical Issue',
            'other' => 'Other'
        ];

        $statuses = [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'responded' => 'Responded',
            'closed' => 'Closed'
        ];

        return view('livewire.admin.contact.manage', compact('types', 'statuses'));
    }
}

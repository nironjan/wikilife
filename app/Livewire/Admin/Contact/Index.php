<?php

namespace App\Livewire\Admin\Contact;

use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $type = '';
    public string $status = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function deleteContactMessage($id)
    {
        try {
            $contactMessage = ContactMessage::findOrFail($id);
            $contactName = $contactMessage->name;
            $contactMessage->delete();

            Toaster::success("Contact message from '{$contactName}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete contact message: " . $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $contactMessage = ContactMessage::findOrFail($id);
            $oldStatus = $contactMessage->status;
            $contactMessage->status = $status;
            $contactMessage->save();

            // Send email notification when status changes to responded
            if ($status === 'responded' && $oldStatus !== 'responded') {
                $this->sendResponseEmail($contactMessage);
            }

            Toaster::success("Contact message status updated to {$status}.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update status: " . $e->getMessage());
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

    public function resendResponseEmail($id)
    {
        try {
            $contactMessage = ContactMessage::findOrFail($id);
            $this->sendResponseEmail($contactMessage);
        } catch (\Exception $e) {
            Toaster::error("Failed to resend response email: " . $e->getMessage());
        }
    }

    public function getCleanExcerpt($text, $limit = 100)
    {
        // Strip HTML tags and limit text
        $cleanText = strip_tags($text);
        return Str::limit($cleanText, $limit);
    }

    public function render()
    {
        $contactMessages = ContactMessage::when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('subject', 'like', "%{$this->search}%")
                      ->orWhere('message', 'like', "%{$this->search}%");
                });
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $types = ['general', 'support', 'partnership', 'technical', 'other'];
        $statuses = ['pending', 'in_progress', 'responded', 'closed'];

        return view('livewire.admin.contact.index', compact('contactMessages', 'types', 'statuses'));
    }
}

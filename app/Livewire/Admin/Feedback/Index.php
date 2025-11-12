<?php

namespace App\Livewire\Admin\Feedback;

use App\Models\Feedback;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    public function deleteFeedback($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $feedbackName = $feedback->name;
            $feedback->delete();

            Toaster::success("Feedback from '{$feedbackName}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete feedback: " . $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $oldStatus = $feedback->status;
            $feedback->status = $status;
            $feedback->save();

            // Send email notification when status changes to approved
            if ($status === 'approved' && $oldStatus !== 'approved') {
                $this->sendApprovalEmail($feedback);
            }

            Toaster::success("Feedback status updated to {$status}.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update status: " . $e->getMessage());
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

    public function resendApprovalEmail($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $this->sendApprovalEmail($feedback);
        } catch (\Exception $e) {
            Toaster::error("Failed to resend approval email: " . $e->getMessage());
        }
    }

    public function getCleanExcerpt($html, $limit = 100)
    {
        // Strip HTML tags and limit text
        $cleanText = strip_tags($html);
        return Str::limit($cleanText, $limit);
    }

    public function render()
    {
        $feedbacks = Feedback::with(['person'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('message', 'like', "%{$this->search}%")
                        ->orWhereHas('person', function ($personQuery) {
                            $personQuery->where('name', 'like', "%{$this->search}%");
                        });
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

        $types = ['correction', 'addition', 'update', 'suggestion'];
        $statuses = ['pending', 'approved', 'rejected'];

        return view('livewire.admin.feedback.index', compact('feedbacks', 'types', 'statuses'));
    }
}
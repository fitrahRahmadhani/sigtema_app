<?php

namespace App\Livewire\Components\Modals;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class ReportTicketModal extends Component
{
    use WithFileUploads;

    public bool $show = false;

    public string $title       = '';
    public string $description = '';
    public $attachment         = null;

    protected function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'attachment'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx', 'max:5120'],
        ];
    }

    protected array $messages = [
        'title.required'       => 'Title is required.',
        'title.min'            => 'Title must be at least 5 characters.',
        'description.required' => 'Description is required.',
        'description.min'      => 'Description must be at least 10 characters.',
        'attachment.mimes'     => 'Attachment must be an image, PDF, or Word document.',
        'attachment.max'       => 'Attachment must not exceed 5MB.',
    ];

    public function open(): void
    {
        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
        $this->reset(['title', 'description', 'attachment']);
        $this->resetValidation();
    }

    public function removeAttachment(): void
    {
        $this->attachment = null;
    }

    public function save(): void
    {
        $this->validate();

        $attachmentPath = null;

        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('tickets/attachments', 'public');
        }

        Ticket::create([
            'user_id'     => Auth::id(),
            'title'       => $this->title,
            'description' => $this->description,
            'attachment'  => $attachmentPath,
        ]);

        $this->reset(['title', 'description', 'attachment']);
        $this->resetValidation();

        // Dispatch browser event so Alpine closes the modal
        $this->dispatch('ticket-submitted');

        Toaster::success('Your report has been submitted. We will review it shortly.');
    }

    public function render()
    {
        return view('livewire.components.modals.report-ticket-modal');
    }
}

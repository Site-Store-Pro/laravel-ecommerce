<?php

namespace App\Livewire;

use App\Models\CmsForm;
use App\Models\CmsFormSubmission;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCmsFormSubmissions extends Component
{
    use WithPagination;

    public int    $formId = 0;
    public string $search = '';
    public ?CmsForm $form  = null;

    public function mount(int $formId): void
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);

        $this->formId = $formId;
        $this->form   = CmsForm::with('fields')->findOrFail($formId);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteSubmission(int $id): void
    {
        CmsFormSubmission::where('form_id', $this->formId)->findOrFail($id)->delete();
        session()->flash('status', 'Submission deleted.');
    }

    public function exportCsv(): mixed
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);

        $form        = CmsForm::with('fields')->findOrFail($this->formId);
        $fields      = $form->fields;
        $submissions = CmsFormSubmission::where('form_id', $this->formId)
            ->orderByDesc('submitted_at')
            ->get();

        $rows   = [];
        $header = ['Submitted At', 'IP Address'];
        foreach ($fields as $field) {
            $header[] = $field->label;
        }
        $rows[] = $header;

        foreach ($submissions as $sub) {
            $row = [
                $sub->submitted_at?->format('Y-m-d H:i:s') ?? '',
                $sub->ip_address ?? '',
            ];
            foreach ($fields as $field) {
                $val = $sub->data[$field->id] ?? '';
                if (is_array($val)) {
                    $val = implode(', ', array_filter($val));
                }
                $row[] = $val;
            }
            $rows[] = $row;
        }

        $filename = 'form-submissions-' . $form->slug . '-' . now()->format('Y-m-d') . '.csv';

        $handle = fopen('php://temp', 'r+');
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);

        $fields = $this->form->fields;

        $submissions = CmsFormSubmission::where('form_id', $this->formId)
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('ip_address', 'like', '%' . $this->search . '%')
                       ->orWhere('data', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('submitted_at')
            ->paginate(25);

        return view('livewire.admin-cms-form-submissions', compact('fields', 'submissions'));
    }
}

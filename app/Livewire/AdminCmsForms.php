<?php

namespace App\Livewire;

use App\Models\CmsForm;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCmsForms extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $form = CmsForm::findOrFail($id);
        $form->is_active = ! $form->is_active;
        $form->save();
        session()->flash('status', 'Form updated.');
    }

    public function deleteForm(int $id): void
    {
        CmsForm::findOrFail($id)->delete();
        session()->flash('status', 'Form deleted.');
    }

    public function duplicateForm(int $id): void
    {
        $original = CmsForm::with('fields')->findOrFail($id);

        $copy = $original->replicate(['slug']);
        $copy->name    = $original->name . ' (Copy)';
        $copy->slug    = CmsForm::generateSlug($original->name . ' copy');
        $copy->save();

        foreach ($original->fields as $field) {
            $newField = $field->replicate(['form_id']);
            $newField->form_id = $copy->id;
            $newField->save();
        }

        session()->flash('status', 'Form duplicated.');
        $this->resetPage();
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);

        $forms = CmsForm::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->withCount('submissions')
            ->withCount('fields')
            ->orderByDesc('id')
            ->paginate(20);

        return view('livewire.admin-cms-forms', compact('forms'));
    }
}

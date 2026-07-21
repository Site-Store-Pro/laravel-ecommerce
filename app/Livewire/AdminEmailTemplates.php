<?php

namespace App\Livewire;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateType;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminEmailTemplates extends Component
{
    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
    }

    public function toggleActive(int $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $template = EmailTemplate::findOrFail($id);
        
        // Deactivate all templates of this type
        EmailTemplate::where('email_type_id', $template->email_type_id)
            ->update(['is_active' => false]);

        // Activate this template
        $template->update(['is_active' => true]);

        session()->flash('status', "Template '{$template->profile_name}' is now active.");
    }

    public function duplicateTemplate(int $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $source = EmailTemplate::findOrFail($id);
        
        $clone = $source->replicate();
        $clone->profile_name = $source->profile_name . ' (Copy)';
        $clone->is_active = false; // Duplicated templates start inactive
        $clone->save();

        session()->flash('status', "Template duplicated successfully as '{$clone->profile_name}'.");
    }

    public function deleteTemplate(int $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $template = EmailTemplate::findOrFail($id);

        if ($template->is_active) {
            // Check if there are other templates of the same type
            $fallback = EmailTemplate::where('email_type_id', $template->email_type_id)
                ->where('id', '!=', $template->id)
                ->first();

            if ($fallback) {
                $fallback->update(['is_active' => true]);
                session()->flash('status', "Active template deleted. '{$fallback->profile_name}' has been activated as fallback.");
            } else {
                session()->flash('error', "Cannot delete the active template when it is the only template of this type. Please create another template first.");
                return;
            }
        } else {
            session()->flash('status', 'Template deleted successfully.');
        }

        $template->delete();
    }

    public function render(): View
    {
        $types = EmailTemplateType::orderBy('ordering')
            ->with(['templates' => function($q) {
                if (!empty($this->search)) {
                    $q->where('profile_name', 'like', '%' . $this->search . '%')
                      ->orWhere('subject', 'like', '%' . $this->search . '%');
                }
                $q->orderBy('profile_name');
            }])
            ->get();

        return view('livewire.admin-email-templates', [
            'types' => $types,
        ]);
    }
}

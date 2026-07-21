<?php

namespace App\Livewire;

use App\Models\CmsEmbed;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminCmsEmbedEdit extends Component
{
    public ?int   $embedId      = null;
    public string $name         = '';
    public int    $embed_type   = 0;   // 0=YouTube, 1=Vimeo, 2=Other HTML
    public string $code_snippet = '';
    public bool   $is_active    = true;

    protected function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'embed_type'   => 'required|integer|in:0,1,2',
            'code_snippet' => 'required|string',
            'is_active'    => 'boolean',
        ];
    }

    public function mount(?int $id = null): void
    {
        if ($id) {
            $embed = CmsEmbed::findOrFail($id);
            $this->embedId      = $embed->id;
            $this->name         = $embed->name;
            $this->embed_type   = (int) $embed->embed_type;
            $this->code_snippet = $embed->code_snippet ?? '';
            $this->is_active    = (bool) $embed->is_active;
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'         => $this->name,
            'embed_type'   => $this->embed_type,
            'code_snippet' => $this->code_snippet,
            'is_active'    => $this->is_active,
        ];

        if ($this->embedId) {
            $embed = CmsEmbed::findOrFail($this->embedId);
            $embed->update($data);
            session()->flash('status', 'Code embed updated successfully.');
        } else {
            $embed = CmsEmbed::create($data);
            $this->embedId = $embed->id;
            session()->flash('status', 'Code embed created successfully.');
            $this->redirect(route('admin.cms-embeds.edit', $embed->id), navigate: true);
        }
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);
        return view('livewire.admin-cms-embed-edit');
    }
}

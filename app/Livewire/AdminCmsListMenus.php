<?php

namespace App\Livewire;

use App\Models\CmsListMenu;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCmsListMenus extends Component
{
    use WithPagination;

    public string $search = '';
    public string $newMenuName = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createMenu(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $this->validate([
            'newMenuName' => 'required|string|max:255',
        ]);

        $menu = CmsListMenu::create([
            'name' => trim($this->newMenuName),
            'custom_css' => '',
        ]);

        $this->newMenuName = '';
        $this->dispatch('toast', message: 'List Menu created successfully.', type: 'success');

        // Redirect to edit page
        $this->redirect(route('admin.cms-list-menus.edit', $menu->id), navigate: true);
    }

    public function deleteMenu(int $id): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $menu = CmsListMenu::findOrFail($id);
        $menu->delete();

        $this->dispatch('toast', message: 'List Menu deleted successfully.', type: 'success');
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $menus = CmsListMenu::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount('items')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('livewire.admin-cms-list-menus', compact('menus'));
    }
}

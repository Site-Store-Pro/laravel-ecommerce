<?php

namespace App\Livewire;

use App\Models\Discount;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminDiscounts extends Component
{
    use WithPagination;

    public string $activeTab = 'standard'; // 'standard' or 'certificates'
    public string $search = '';

    protected $queryString = [
        'activeTab' => ['except' => 'standard'],
        'search' => ['except' => ''],
    ];

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        \DB::table('discount_types')->where('id', 5)->update(['name' => 'Brand or Category']);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteDiscount(int $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        
        $discount = Discount::findOrFail($id);
        $discount->delete();

        session()->flash('status', 'Discount deleted successfully.');
    }

    public function render(): View
    {
        $query = Discount::query()->with('discountType');

        if ($this->activeTab === 'certificates') {
            $query->where('discount_type_id', 1)->where('code_type', 1); // Gift Certificate
        } else {
            $query->where(function($q) {
                $q->where('discount_type_id', '!=', 1)->orWhere('code_type', 0); // Not a Gift Certificate
            });
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        $discounts = $query->latest()->paginate(10);

        return view('livewire.admin-discounts', [
            'discounts' => $discounts,
        ]);
    }
}

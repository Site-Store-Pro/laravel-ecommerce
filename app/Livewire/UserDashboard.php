<?php

namespace App\Livewire;

use App\Enums\TicketStatus;
use App\Livewire\Actions\Logout;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class UserDashboard extends Component
{
    use WithPagination;

    #[Url(keep: true)]
    public string $tab = 'orders';

    public ?int $selectedOrderId = null;

    public function mount(): mixed
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdmin() || $user->isOrderProcessor()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isTicketManager()) {
                return redirect()->route('admin.tickets');
            } elseif ($user->isGuest() && !$user->hasVerifiedEmail()) {
                // Step 1 of guest conversion: verify email ownership first.
                // Setting url.intended ensures that after clicking the verification link
                // the user lands on /account/set-password (not /dashboard).
                // This prevents someone from guessing a guest email and hijacking the account.
                session(['url.intended' => route('guest.set-password')]);
                $user->sendEmailVerificationNotification();
                return redirect()->route('verification.notice');
            } elseif ($user->isGuest() && $user->hasVerifiedEmail()) {
                // Step 2: email proved — now let them set a real password.
                return redirect()->route('guest.set-password');
            } elseif (!$user->hasVerifiedEmail()) {
                // Regular registered users who haven't verified yet.
                return redirect()->route('verification.notice');
            }
        }

        if (!in_array($this->tab, ['orders', 'downloads', 'tickets'])) {
            $this->tab = 'orders';
        }

        // Redirect away from disabled tabs
        $settings = \App\Models\CmsSetting::allCached();
        if (!empty($settings['disable_account_downloads_tab']) && $settings['disable_account_downloads_tab'] === '1' && $this->tab === 'downloads') {
            $this->tab = 'orders';
        }
        if (!empty($settings['disable_account_tickets_tab']) && $settings['disable_account_tickets_tab'] === '1' && $this->tab === 'tickets') {
            $this->tab = 'orders';
        }

        return null;
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: false);
    }

    public function viewOrderDetails(int $orderId): void
    {
        $this->selectedOrderId = $orderId;
    }

    public function closeOrderDetails(): void
    {
        $this->selectedOrderId = null;
    }

    /**
     * Cancel an active subscription for the customer.
     */
    public function cancelSubscription(int $orderDetailId, \App\Services\Payments\SubscriptionService $subscriptionService): void
    {
        $detail = \App\Models\OrderDetail::with(['order', 'variant'])->find($orderDetailId);

        if (!$detail || !$detail->order || $detail->order->order_user_id !== auth()->id()) {
            session()->flash('error', siteLabel('account.cancel_unauthorized', 'Unable to cancel: subscription not found or unauthorized.'));
            return;
        }

        if (!$detail->active_subscription) {
            session()->flash('info', siteLabel('account.already_cancelled', 'This subscription is already cancelled.'));
            return;
        }

        try {
            $subscriptionService->cancelSubscription($detail, 'Cancelled by customer via account portal');
            session()->flash('status', siteLabel('account.cancel_success', 'Subscription has been cancelled successfully.'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("UserDashboard cancelSubscription failed for detail #{$orderDetailId}: " . $e->getMessage());
            session()->flash('error', siteLabel('account.cancel_failed', 'Failed to cancel subscription: ') . $e->getMessage());
        }
    }

    public function render(): View
    {
        $ticketsQuery = Ticket::query()->where('user_id', auth()->id());

        $tickets = (clone $ticketsQuery)
            ->withCount('replies')
            ->latest()
            ->paginate(5, ['*'], 'ticketsPage');

        $counts = (clone $ticketsQuery)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $orders = \App\Models\Order::where('order_user_id', auth()->id())
            ->with('statusList')
            ->latest()
            ->paginate(5, ['*'], 'ordersPage');

        $downloads = \App\Models\OrderDetail::whereHas('order', function ($query) {
                $query->where('order_user_id', auth()->id())
                      ->whereIn('order_status', [1, 2, 5, 7, 8]);
            })
            ->where('download_item', 1)
            ->with('order')
            ->latest()
            ->paginate(5, ['*'], 'downloadsPage');

        $selectedOrder = $this->selectedOrderId
            ? \App\Models\Order::where('order_user_id', auth()->id())
                ->with(['details.variant.product', 'statusList'])
                ->find($this->selectedOrderId)
            : null;

        $settings = \App\Models\CmsSetting::allCached();

        return view('livewire.user-dashboard', [
            'tickets'             => $tickets,
            'statuses'            => TicketStatus::cases(),
            'counts'              => $counts,
            'orders'              => $orders,
            'downloads'           => $downloads,
            'selectedOrder'       => $selectedOrder,
            'downloadsTabEnabled' => empty($settings['disable_account_downloads_tab']) || $settings['disable_account_downloads_tab'] !== '1',
            'ticketsTabEnabled'   => empty($settings['disable_account_tickets_tab'])   || $settings['disable_account_tickets_tab']   !== '1',
        ]);
    }
}

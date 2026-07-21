<?php

namespace App\Enums;

enum UserRole: int
{
    case User           = 1;
    case Wholesale       = 2;
    case Admin          = 3;
    case OrderProcessor = 4;
    case TicketManager  = 5;

    public function label(): string
    {
        return match ($this) {
            self::User           => 'Customer',
            self::Wholesale      => 'Wholesale',
            self::Admin          => 'Admin',
            self::OrderProcessor => 'Order Processor',
            self::TicketManager  => 'Ticket Manager',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::User           => 'Regular customer. Can submit and view their own tickets.',
            self::Wholesale      => 'Wholesale customer. Receives wholesale tier pricing.',
            self::Admin          => 'Full admin access. Can manage all tickets, users, and settings.',
            self::OrderProcessor => 'Can view and edit orders in the admin area.',
            self::TicketManager  => 'Can view and reply to tickets in the admin area.',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::User           => 'bg-slate-100 text-slate-700 border-slate-200',
            self::Wholesale      => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            self::Admin          => 'bg-violet-50 text-violet-700 border-violet-200',
            self::OrderProcessor => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::TicketManager  => 'bg-cyan-50 text-cyan-700 border-cyan-200',
        };
    }

    public function dotClasses(): string
    {
        return match ($this) {
            self::User           => 'bg-slate-400',
            self::Wholesale      => 'bg-indigo-500',
            self::Admin          => 'bg-violet-500',
            self::OrderProcessor => 'bg-emerald-500',
            self::TicketManager  => 'bg-cyan-500',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $role) => [$role->value => $role->label()])
            ->all();
    }
}

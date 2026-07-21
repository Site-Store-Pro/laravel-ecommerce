<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Open = 'open';
    case InProcess = 'in_process';
    case Assigned = 'assigned';
    case Completed = 'completed';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::InProcess => 'In Process',
            self::Assigned => 'Assigned',
            self::Completed => 'Completed',
            self::Closed => 'Closed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Open => 'blue',
            self::InProcess => 'yellow',
            self::Assigned => 'purple',
            self::Completed => 'green',
            self::Closed => 'gray',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->label()])
            ->all();
    }
}

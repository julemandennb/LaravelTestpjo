<?php

namespace app\Enum;

enum OrderStatus: string
{
    case NEW = 'new';
    case PROCESSING = 'processing';
    case PACKED = 'packed';
    case SHIPPED = 'shipped';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public static function list(): array
    {
        return array_map(
            fn (self $status) => [
                'Id' => $status->value,
                'Name' => $status->name,
            ],
            self::cases()
        );
    }

    public static function fromNameOrId(string $value): ?self
    {
        foreach (self::cases() as $status) {
            if (
                strcasecmp($status->value, $value) === 0 ||
                strcasecmp($status->name, $value) === 0
            ) {
                return $status;
            }
        }

        return null;
    }
}

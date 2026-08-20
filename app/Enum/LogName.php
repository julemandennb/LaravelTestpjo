<?php

namespace app\Enum;

enum LogName: string
{
    case USERS = 'users';
    case PRODUKT = 'Produkt';
    case ORDERPRODUKT = 'Order-Produkt';
    case ORDER = 'Order';

    public static function list(): array
    {
        return array_map(
            fn (self $logName) => [
                'Id' => $logName->value,
                'Name' => $logName->name,
            ],
            self::cases()
        );
    }

    public static function fromNameOrId(string $value): ?self
    {
        foreach (self::cases() as $logName) {
            if (
                strcasecmp($logName->value, $value) === 0 ||
                strcasecmp($logName->name, $value) === 0
            ) {
                return $logName;
            }
        }

        return null;
    }
}

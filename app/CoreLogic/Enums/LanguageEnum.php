<?php

namespace App\CoreLogic\Enums;

use App\CoreLogic\Interfaces\HasColor;


enum LanguageEnum: string implements HasColor
{
    case Arabic     = "ar";
    case English    = "en";

    public function label(): string
    {
        return match ($this) {
            self::Arabic        =>  'Arabic',
            self::English       =>  'English',
            default             =>  'Arabic'
        };
    }

    public function color(): string
    {
        return match ($this){
            self::Arabic        => ColorEnum::Blue->value,
            self::English       => ColorEnum::Indigo->value,
            default             => ColorEnum::Blue->value
        };
    }

    public static function toArray()
    {
        $statuses = [];

        foreach (static::cases() as $status) {
            $statuses[$status->value] = $status->name;
        }

        return $statuses;
    }
}

<?php
namespace App\Enums ;



enum Tva: string
{

    case VAT_55 = '55';
    case VAT_10 = '10';
    case VAT_20 = '20';

    public function getChoiceValue(): float
    {
        return match ($this) {
            self::VAT_55 => 55/10 ,
            self::VAT_10 => 10,
            self::VAT_20 => 20,
        };
    }
    public function getChoices(): array
    {
        return [
            self::VAT_55 ,
            self::VAT_10 ,
            self::VAT_20 ,
        ];
    }
}



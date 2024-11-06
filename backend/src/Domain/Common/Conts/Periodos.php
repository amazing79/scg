<?php

namespace App\Domain\Common\Conts;

class Periodos
{
    const VALUES = [
        1 => ['inicio' => '01-01', 'fin' => '01-31'],
        2 => ['inicio' => '02-01', 'fin' => '02-28'],
        3 => ['inicio' => '03-01', 'fin' => '03-31'],
        4 => ['inicio' => '04-01', 'fin' => '04-30'],
        5 => ['inicio' => '05-01', 'fin' => '03-31'],
        6 => ['inicio' => '06-01', 'fin' => '06-30'],
        7 => ['inicio' => '07-01', 'fin' => '07-31'],
        8 => ['inicio' => '08-01', 'fin' => '08-31'],
        9 => ['inicio' => '09-01', 'fin' => '09-30'],
        10 => ['inicio' => '10-01', 'fin' => '10-31'],
        11 => ['inicio' => '11-01', 'fin' => '11-30'],
        12 => ['inicio' => '12-01', 'fin' => '12-31'],
    ];

    public static function makePeriodoFilter($periodo, $anio): string
    {
        $rango = self::VALUES[$periodo];
        $inicio = "${anio}-${rango['inicio']}";
        $fin = "${anio}-${rango['fin']}";

        return " between '${inicio}' and '${fin}' ";
    }

}
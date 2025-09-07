<?php

namespace App\Helper;

class Helper
{
    public static function financialYear(): string
    {
        $currentYear = date('Y');
        $nextYear = date('Y', strtotime('+1 year'));
        $previousYear = date('Y', strtotime('-1 year'));
        $currentMonth = date('m');
        if (intval($currentMonth) < 7) {
            $financialYear = $previousYear.'-'.$currentYear;
        } else {
            $financialYear = $currentYear.'-'.$nextYear;
        }

        return $financialYear;
    }
}

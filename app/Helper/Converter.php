<?php

namespace App\Helper;

class Converter
{
    public static $bn = ['১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০'];

    public static $en = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

    public static function bn2en($number): array|string
    {
        return str_replace(self::$bn, self::$en, $number);
    }

    public static function en2bn($number): array|string
    {
        return str_replace(self::$en, self::$bn, $number);
    }

    public static function banglatoenglish($number): array|string
    {
        return str_replace(self::$bn, self::$en, $number);
    }

    public static function englishtobangla($number): array|string
    {
        return str_replace(self::$en, self::$bn, $number);
    }

    public static function numberformate($number): array|string
    {
        $number = number_format(Converter::banglatoenglish($number));

        return str_replace(self::$en, self::$bn, $number);
    }

    public static function doublevalidator($number): bool
    {
        for ($i = 0; $i < strlen($number); $i++) {
            if ($number[$i] == '/') {
                return true;
                break;
            }
        }

        return false;
    }

    public static function round_numb($number)
    {
        $flt = $number / 100;

        return $number;
    }

    public static function num2bangla($number): string
    {
        if (($number < 0) || ($number > 999999999)) {
            return 'নাম্বারটি অতিরিক্ত বড়';
        } elseif (! is_numeric($number)) {
            return 'বৈধ নাম্বার নয়';
        }
        $Kt = floor($number / 10000000); /* Koti */
        $number -= $Kt * 10000000;
        $Gn = floor($number / 100000);  /* lakh  */
        $number -= $Gn * 100000;
        $kn = floor($number / 1000);     /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);      /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);       /* Tens (deca) */
        $n = $number % 10;               /* Ones */
        $res = '';
        if ($Kt) {
            $res .= Converter::num2bangla($Kt).' কোটি ';
        }
        if ($Gn) {
            $res .= Converter::num2bangla($Gn).' লাখ';
        }
        if ($kn) {
            $res .= (empty($res) ? '' : ' ').
                Converter::num2bangla($kn).' হাজার';
        }
        if ($Hn) {
            $res .= (empty($res) ? '' : ' ').
                Converter::num2bangla($Hn).' শত';
        }
        $hund = ['', 'এক', 'দুই', 'তিন', 'চার', 'পাঁচ', 'ছয়', 'সাত', 'আট', 'নয়', 'দশ',
            'এগার', 'বার', 'তের', 'চৌদ্দ', 'পনের', 'ষোল', 'সতের', 'আঠার', 'ঊনিশ', 'বিশ',
            'একুশ', 'বাইশ', 'তেইশ', 'চব্বিশ', 'পঁচিশ', 'ছাব্বিশ', 'সাতাশ', 'আঠাশ', 'ঊনত্রিশ', 'ত্রিশ',
            'একত্রিশ', 'বত্রিশ', 'তেত্রিশ', 'চৌত্রিশ', 'পয়ত্রিশ', 'ছত্রিশ', 'সতত্রিশ', 'আটত্রিশ', 'ঊনচল্লিশ', 'চল্লিশ',
            'একচল্লিশ', 'বেয়াল্লিশ', 'তেতাল্লিশ', 'চোয়াল্লিশ', 'পঁয়তাল্লিশ', 'ছেচল্লিশ', 'সতচল্লিশ', 'আটচল্লিশ', 'ঊনপঞ্চাশ', 'পঞ্চাশ',
            'একান্ন', 'বাহান্ন', 'তেপান্ন', 'চোয়ান্ন', 'পঁঞ্চান্ন', 'ছাপ্পান্ন', 'সাতান্ন', 'আটান্ন', 'ঊনষাট', 'ষাট',
            'একষট্টি', 'বাষট্টি', 'তেষট্টি', 'চৌষট্টি', 'পঁয়ষট্টি', 'ছেষট্টি', 'সতাষট্টি', 'আটষট্টি', 'ঊনসত্তর', 'সত্তর',
            'একাত্তর', 'বাহাত্তর', 'তেহাত্তর', 'চোয়াত্তর', 'পঁচাত্তর', 'ছিয়াত্তর', 'সাতাত্তর', 'আটাত্তর', 'ঊনআশি', 'আশি',
            'একাশি', 'বিরাশি', 'তিরাশি', 'চোরাশি', 'পঁচাশি', 'ছিয়াশি', 'সাতাশি', 'অটাশি', 'ঊননব্বই', 'নব্বই',
            'একানব্বই', 'বিরানব্বই', 'তিরানব্বই', 'চুরানব্বই', 'পঁচানব্বই', 'ছিয়ানব্বই', 'সাতানব্বই', 'আটানব্বই', 'নিরানব্বই', 'একশ'];

        if ($Dn || $n) {
            if (! empty($res)) {
                $res .= ' ';
            }
            $res .= $hund[$Dn * 10 + $n];
        }
        if (empty($res)) {
            $res = 'শূন্য';
        }

        return $res;
    }

    public function dateToDDMMYYYY($date): string
    {
        $month = substr($date, 0, 2);
        $day = substr($date, 3, 2);
        $year = substr($date, 6, 4);

        return $day.'/'.$month.'/'.$year;
    }
}

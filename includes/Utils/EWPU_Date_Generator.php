<?php

namespace EmoWooPriceUpdate\Utils;

use EmoWooPriceUpdate\Repository\EWPU_Local_Date;

class EWPU_Date_Generator
{
    private $year;
    private $month;
    private $day;
    private $localDate;

    public function __construct(int $year, int $month, int $day)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->localDate = new EWPU_Local_Date;
    }

    private function string_Date(string $sparetor): string
    {
        echo $this->year . "<br>";
        echo $this->month . "<br>";
        echo $this->day . "<br>";
        var_dump($this->localDate);

        return $this->year. $sparetor . $this->localDate->month($this->month) . $sparetor . $this->day;
    }

    public function text_Date():string
    {
        return $this->string_Date('/');
    }

    public function utm_Date (): string
    {
        return $this->string_Date('-');
    }
    
}
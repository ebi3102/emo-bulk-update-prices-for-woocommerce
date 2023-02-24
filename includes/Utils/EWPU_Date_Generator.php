<?php

namespace EmoWooPriceUpdate\Utils;

class EWPU_Date_Generator
{
    private $year;
    private $month;
    private $day;

    public function __construct(int $year, int $month, int $day)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    private function string_Date(string $sparetor): string
    {
	    return $this->year. $sparetor . $this->month . $sparetor . $this->day;
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
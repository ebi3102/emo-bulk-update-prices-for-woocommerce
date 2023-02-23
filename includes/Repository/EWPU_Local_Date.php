<?php
namespace EmoWooPriceUpdate\Repository;

class EWPU_Local_Date
{
    private $localDate;

    public function __construst()
    {
        $this->localDate = new WP_Locale();
    }

    public function month(int $month):string
    {
        return $this->localDate->get_month($month);
    }
}
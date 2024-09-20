<?php

use Illuminate\Support\HtmlString;

if (! function_exists('formatReam')) {
    /**
     * Formats the given total quantity of sheets into reams and remaining sheets.
     *
     * This function converts the total quantity of sheets into reams (where 1 ream = 500 sheets)
     * and calculates the remaining sheets. It returns an HTML string with the formatted result.
     *
     * @param int $totalQuantity The total quantity of sheets (1/2 Plano).
     * @return \Illuminate\Support\HtmlString The formatted HTML string displaying reams and remaining sheets.
     */
    function formatReam($totalQuantity)
    {
        $sheetsPerReam = 500;
        $totalQuantity = $totalQuantity / 2;

        $totalReam = intdiv($totalQuantity, $sheetsPerReam);
        $remainingSheets = $totalQuantity % $sheetsPerReam;

        return new HtmlString(
            '<span class="font-bold text-lg">' . $totalReam . '<span class="font-thin text-sm"> rim </span></span>' .
                '<span class="font-bold text-lg">&nbsp&nbsp+' . $remainingSheets . '<span class="font-thin text-sm"> sheet</span></span>'
        );
    }
}

if (! function_exists('formatNumber')) {
    /**
     * Formats the given number into locale format.
     *
     * This function formats the given number into locale format and returns an HTML string
     * with the formatted result.
     *
     * @param float $number The number to format.
     * @return \Illuminate\Support\HtmlString The formatted HTML string displaying the currency format.
     */
    function formatNumber($number)
    {
        return number_format($number, 0, ',', '.');
    }
}

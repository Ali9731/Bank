<?php

use Illuminate\Support\Facades\Config;

function convertToEn($input)
{
    $persian = Config::get('app.persianNumbers');
    $arabic = Config::get('app.arabicNumbers');
    $english = range(0, 9);

    $convertedPersian = str_replace($persian, $english, $input);

    return str_replace($arabic, $english, $convertedPersian);
}

<?php

namespace Larangular\SpreadsheetImport\Http\Controllers\SpreadsheetRow;

use Cyberduck\LaravelExcel\Contract\ParserInterface;
use Illuminate\Support\Arr;

class Parser implements ParserInterface {
    public function transform($array, $header): array {
        $response = [];
        for ($i = 0, $iMax = count($header); $i < $iMax; $i++) {
            $value = ($array[$i] === '')
                ? null
                : $array[$i];

            Arr::set($response, $header[$i], $value);
        }

        return $response;
    }
}

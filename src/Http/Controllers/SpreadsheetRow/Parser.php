<?php

namespace Larangular\SpreadsheetImport\Http\Controllers\SpreadsheetRow;

use Cyberduck\LaravelExcel\Contract\ParserInterface;
use Illuminate\Support\Arr;

class Parser implements ParserInterface {

    private $parsedHeader;

    public function transform($array, $header): array {
        $header = $this->getHeader($header);
        $response = [];
        for ($i = 0, $iMax = count($header); $i < $iMax; $i++) {
            //careful here it may exclude 0 values
            $value = ((is_array(@$array[$i]) && empty(@$array[$i])) || @$array[$i] === '' || !isset($array[$i]))
                ? null
                : $array[$i];

            Arr::set($response, $header[$i], $value);
        }

        return $response;
    }

    protected function getHeader($header) {
        if (!isset($this->parsedHeader)) {
            foreach ($header as $key => $value) {
                $this->parsedHeader[$key] = $this->cleanupLabel($value);
            }
        }
        return $this->parsedHeader;
    }

    private function cleanupLabel(string $value): string {
        return preg_replace([
            '/[^a-zA-Z\.\_\s]/',
            '/\s+/',
        ], [
            '',
            '_',
        ], strtolower(trim(iconv('UTF-8', 'ASCII//TRANSLIT', $value))));
    }
}

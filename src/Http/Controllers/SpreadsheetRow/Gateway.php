<?php

namespace Larangular\SpreadsheetImport\Http\Controllers\SpreadsheetRow;

use Illuminate\Http\Request;
use Larangular\RoutingController\{Contracts\IGatewayModel, Controller};
use Larangular\SpreadsheetImport\Models\SpreadsheetFileRow;

class Gateway extends Controller implements IGatewayModel {

    public function model() {
        return SpreadsheetFileRow::class;
    }

    public function entries($where = []) {
        $entries = parent::entries($where);
        if ($this->canTryForImportFile($entries, $where)) {
            (new FileImport($where['file_id']))->import();
            $entries = parent::entries($where);
        }

        return $entries;
    }

    public function uniqueFiles() {
        return $this->entries([
            'row_number'  => 2,
            'with'        => 'file',
            'orderByDesc' => 'id',
        ]);
    }

    private function canTryForImportFile($entries, $where): bool {
        //TODO if import fail with some rows imported will return false ;/ ...
        return (count($entries) <= 0 && array_key_exists('file_id', $where));
    }

}

<?php

namespace Larangular\SpreadsheetImport\Http\Controllers\SpreadsheetRow;

use Cyberduck\LaravelExcel\Importer\AbstractSpreadsheet;
use Cyberduck\LaravelExcel\ImporterFacade;
use Larangular\FileManager\Models\FileManager;
use Larangular\RoutingController\MakeResponse;
use Larangular\SpreadsheetImport\Models\SpreadsheetFileRow;
use Larangular\SpreadsheetImport\Facades\OperationTypeHelper;

class FileImport {
    use MakeResponse;

    private $categoryLength, $fileId, $type, $operationType;

    public function __construct(int $fileId) {
        $this->fileId = $fileId;
    }

    public function import() {
        return $this->proc($this->fileId);
    }

    private function getFilePath(int $fileId): ?string {
        $file = FileManager::find($fileId);
        return ($file->exist && $file->extension === 'xlsx')
            ? $file->fullPath()
            : null;
    }

    private function proc(int $fileId) {
        $path = $this->getFilePath($fileId);
        $excel = $this->getSpreadsheet('Excel')
                               ->load($path)
                               ->setParser(new Parser());


        $excel->hasHeader(1);
        $rows = $excel->getCollection();
        $this->storeSpreadsheetRows($rows);
    }

    private function getSpreadsheet(string $type): AbstractSpreadsheet {
        return ImporterFacade::make($type);
    }

    private function storeSpreadsheetRows($rows): void {
        foreach ($rows as $index => $row) {
            SpreadsheetFileRow::create([
                'file_id'               => $this->fileId,
                'row_number'            => $index + 2,
                'row_value'             => $row,
            ]);
        }
    }

}

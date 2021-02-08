<?php

Route::group([
    'prefix'     => 'api/spreadsheet-import/',
    'middleware' => 'api',
    'namespace'  => 'Larangular\SpreadsheetImport\Http\Controllers',
    'as'         => 'larangular.api.spreadsheet-import.',
], static function () {
    Route::get('spreadsheet-row/files', 'SpreadsheetRow\Gateway@uniqueFiles');
    Route::resource('spreadsheet-row', 'SpreadsheetRow\Gateway');
});

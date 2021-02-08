<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Larangular\Installable\Facades\InstallableConfig;
use Larangular\MigrationPackage\Migration\Schematics;

class CreateSpreadsheetFileRowTable extends Migration {
    use Schematics;

    public function __construct() {
        $installableConfig = InstallableConfig::config('Larangular\SpreadsheetImport\SpreadsheetImportServiceProvider');
        $this->connection = $installableConfig->getConnection('spreadsheet_file_rows');
        $this->name = $installableConfig->getName('spreadsheet_file_rows');
    }

    public function up(): void {
        $this->create(static function (Blueprint $table) {
            $table->increments('id')
                  ->unsigned();
            $table->integer('file_id')
                  ->unsigned();
            $table->integer('type');
            $table->integer('status');
            $table->integer('row_number')
                  ->unsigned();
            $table->longText('row_value');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void {
        $this->drop();
    }
}

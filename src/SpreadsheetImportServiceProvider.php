<?php

namespace Larangular\SpreadsheetImport;

use Larangular\Installable\{Contracts\HasInstallable, Contracts\Installable, Installer\Installer};
use Larangular\Installable\Support\InstallableServiceProvider as ServiceProvider;

class SpreadsheetImportServiceProvider extends ServiceProvider implements HasInstallable {

    public function boot(): void {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->declareMigrationGlobal();
        $this->declareMigrationSpreadsheetFileRows();
    }

    public function register(): void {
    }

    public function installer(): Installable {
        return new Installer(__CLASS__);
    }

    private function declareMigrationGlobal(): void {
        $this->declareMigration([
            'connection'   => 'mysql',
            'migrations'   => [
                'local_path' => __DIR__ . '/database/migrations',
            ],
            'seeds'        => [
                'local_path' => __DIR__ . '/database/seeds',
            ],
            'seed_classes' => [],
        ]);
    }

    private function declareMigrationSpreadsheetFileRows(): void {
        $this->declareMigration([
            'name'      => 'spreadsheet_file_rows',
            'timestamp' => true,
        ]);
    }
}

<?php

namespace Larangular\SpreadsheetImport\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Withable\WithableTrait;
use Larangular\FileManager\Models\FileManager;
use Larangular\Installable\Facades\InstallableConfig;
use Larangular\RoutingController\CachableModel as RoutingModel;
use Larangular\SpreadsheetImport\Http\Controllers\SpreadsheetRow\SpreadsheetRowOperationInterface;

class SpreadsheetFileRow extends Model {
    use SoftDeletes, RoutingModel, SearchableTrait, WithableTrait;

    public    $searchable = ['*'];
    protected $withable   = ['*'];
    protected $fillable   = [
        'id',
        'file_id',
        'row_number',
        'row_value',
    ];
    protected $with       = [];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $installableConfig = InstallableConfig::config('Larangular\SpreadsheetImport\SpreadsheetImportServiceProvider');
        $this->table = $installableConfig->getName('spreadsheet_file_rows');
        $this->connection = $installableConfig->getConnection('spreadsheet_file_rows');
        $this->timestamps = $installableConfig->getTimestamp('spreadsheet_file_rows');
    }

    public function setRowValueAttribute($value): void {
        if (!isset($value)) {
            return;
        }
        $this->attributes['row_value'] = json_encode($value);
    }

    public function getRowValueAttribute(): array {
        return json_decode($this->attributes['row_value'], true);
    }

    public function scopeFromFile($query, $fileId) {
        return $query->where('file_id', $fileId);
    }

    public function file(): HasOne {
        return $this->hasOne(FileManager::class, 'id', 'file_id');
    }

}

<?php

namespace App\Jobs;

use App\Enums\CropProtectionColumn;
use App\Enums\DefaultImports;
use App\Enums\FertilizerColumn;
use App\Models\FarmCrop;
use App\Models\FarmFertilizer;
use App\Models\FarmPlantProtection;
use App\Models\UserDefaultModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use OpenSpout\Reader\XLSX\Reader;
use UnitEnum;

class ImportDefaultModelsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $filePath, public string $headerRow, public string $defaultType, public array $columns)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reader = new Reader();
        $reader->open($this->filePath);
        $sheet = $reader->getSheetIterator()->current();
        $columnEnum = match($this->defaultType) {
            DefaultImports::CROP_SPECIES->value => CropProtectionColumn::class,
            DefaultImports::FERTILIZERS->value => FertilizerColumn::class,
            DefaultImports::CROP_PROTECTION->value => CropProtectionColumn::class,
        };

        $hashColumns = match($this->defaultType) {
            DefaultImports::CROP_SPECIES->value => [],
            DefaultImports::FERTILIZERS->value => [FertilizerColumn::NAME, FertilizerColumn::COMPANY],
            DefaultImports::CROP_PROTECTION->value => [CropProtectionColumn::NAME, CropProtectionColumn::COMPANY],
        };

        $modelClass = match($this->defaultType) {
            DefaultImports::CROP_SPECIES->value => FarmCrop::class,
            DefaultImports::FERTILIZERS->value => FarmFertilizer::class,
            DefaultImports::CROP_PROTECTION->value => FarmPlantProtection::class,
        };

        $syncIds = [];

        foreach ($sheet->getRowIterator() as $idx => $row) {
            if ($idx <= $this->headerRow) {
                continue;
            }

            $values = $row->toArray();
            $rowData = [];
            foreach ($columnEnum::cases() as $column) {
                $columnSettings = $this->columns[$column->value];
                $rowData[$column->value] = $values[$columnSettings['mapped_to_column']] ?? $columnSettings['default_value'];
            }

            $syncHash = hash('md5', json_encode(array_map(fn($col) => $rowData[$col->value] ?? '', $hashColumns)));
            UserDefaultModel::query()->updateOrCreate([
                'sync_hash' => $syncHash
            ], [
                'model' => json_encode($rowData),
                'model_type' => $modelClass
            ]);

            $syncIds[] = $syncHash;
        }

        UserDefaultModel::query()
            ->where('model_type', $modelClass)
            ->whereNotIn('sync_hash', $syncIds)
            ->forceDelete();
    }
}

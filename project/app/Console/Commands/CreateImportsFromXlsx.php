<?php

namespace App\Console\Commands;

use App\Enums\CropProtectionColumns;
use App\Enums\DefaultImports;
use App\Enums\DefinedCodifiers;
use App\Enums\FertilizerColumns;
use App\Enums\UnitType;
use App\Models\Codifier;
use App\Models\FarmCrop;
use App\Models\FarmFertilizer;
use App\Models\FarmPlantProtection;
use App\Models\UserDefaultImports;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenSpout\Reader\XLSX\Options;
use OpenSpout\Reader\XLSX\Reader;

class CreateImportsFromXlsx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-imports-from-xlsx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        UserDefaultImports::query()->truncate();
        $this->createFertilizerDefaults();
        $this->createCropProtectionDefault();
        $this->createCropsDefault();
    }

    private function readXlsx(string $fileName, bool $withHeader = false): array {
        $options = new Options();
        $reader = new Reader($options);
        $disk = Storage::disk('local');

        $reader->open($disk->path($fileName));
        $rows = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $idx => $row) {
                if ($idx === 1 && !$withHeader) {
                    continue;
                }

                if (empty($row->toArray())) {
                    continue;
                }

                $rows[] = $row->toArray();
            }
        }

        return $rows;
    }

    private function createCropProtectionDefault() {
        /** @var Collection $cropProtectionUsage */
        $cropProtectionUsage = Codifier::whereParentCode(DefinedCodifiers::CROP_PROTECTION_USAGE)->pluck('code', 'name');
        $imports = [];
        $hash = Str::uuid();
        foreach ($this->readXlsx('augu_aizsardzibas_lidzekli.xlsx') as $row) {
            $protectionName = $row[0];
            if (empty($protectionName)) {
                continue;
            }

            $companyName = $row[2] ?? '';
            $description = $row[5] ?? '';

            $protectionCategories = explode('/', $row[1] ?? '');
            if (empty($protectionName)) {
                continue;
            }

            $categoriesCodes = [];
            foreach ($protectionCategories as $category) {
                $code = $cropProtectionUsage->filter(fn($value, $key) => Str::contains($key, "($category)"))->first();
                if (empty($code)) {
                    continue;
                }
                $categoriesCodes[] = $code;
            }

            $imports[] = [
                CropProtectionColumns::COMPANY->value => $companyName,
                CropProtectionColumns::DESCRIPTION->value => $description,
                CropProtectionColumns::NAME->value => $protectionName,
                CropProtectionColumns::PROTECTION_CATEGORY_CODES->value => collect($categoriesCodes)->unique()->toArray(),
                CropProtectionColumns::UNIT_TYPE->value => $row[7],
                CropProtectionColumns::COST_PER_UNIT->value => floatval($row[8]),
            ];
        }

        UserDefaultImports::create([
            'import_type' => DefaultImports::CROP_PROTECTION,
            'imports' => $imports,
            'model_type' => FarmPlantProtection::class,
            'sync_hash' => $hash,
        ]);
    }

    private function createCropsDefault() {
        //12 = "Selekcionāra tiesību īpašnieka pilnv. pārstāvis ar tiesībām slēgt licences līgumus"
        //11 = "Selekcionāra tiesību īpašnieks"
        //10 = "Šķirnes aizsardzība"
        //9 = "Speciālās piezīmes pēc SĪN testa"
        //8 = "Cita informācija par šķirni"
        //7 = "Šķirnes uzturētājs"
        //6 = "Selekcionārs"
        //5 = "Valsts, kurā šķirne selekcionēta"
        //4 = "Termiņš, uz kādu šķirne iekļauta katalogā"
        //3 = "Iekļaušanas datums"
        //2 = "Šķirnes nosaukums"
        //1 = "Sugas zinātniskais nosaukums"
        //0 = "Sugas nosaukums"
        $cropSpecies = Codifier::whereParentCode(DefinedCodifiers::CROP_SPECIES)->pluck('code', 'name');
        $imports = [];
        $hash = Str::uuid();
        foreach ($this->readXlsx('skirnu_katalogs.xlsx') as $row) {
            $speciesName = $row[0];
            if (!$cropSpecies->has($speciesName)) {
                continue;
            }

            if (!isset($row[2])) {
                continue;
            }

            $imports[] = [
                'crop_species_code' => $cropSpecies->get($speciesName),
                'name' => $row[2],
                'unit_type' => UnitType::KILOGRAMS,
                'cost_per_unit' => 10, // Varbūt jādabu no kādas datubāzes
            ];
        }

        UserDefaultImports::create([
            'import_type' => DefaultImports::CROP_SPECIES,
            'imports' => $imports,
            'model_type' => FarmCrop::class,
            'sync_hash' => $hash,
        ]);
    }

    private function createFertilizerDefaults() {
        $imports = [];
        $hash = Str::uuid();
        $header = null;
        foreach ($this->readXlsx('mineralmeslojums.xlsx', true) as $row) {
            if (empty($header)) {
                $header = array_flip(array_map('strtolower', array_map('trim', $row)));
                continue;
            }

            if (empty($row[$header[FertilizerColumns::NAME->value]])) {
                continue;
            }

            $unitType = $row[3] ?? UnitType::KILOGRAMS;

            if (empty($unitType)) {
                continue;
            }

            $values = [
                FertilizerColumns::NAME->value => trim($row[$header[FertilizerColumns::NAME->value]]),
                FertilizerColumns::COMPANY->value => trim($row[$header[FertilizerColumns::COMPANY->value]]),
                FertilizerColumns::CONTENTS->value => trim($row[$header[FertilizerColumns::CONTENTS->value]]),
                FertilizerColumns::UNIT_TYPE->value => UnitType::KILOGRAMS,
                FertilizerColumns::COST_PER_UNIT->value => 10,
            ];

            foreach (FertilizerColumns::contentsColumns() as $column) {
                $values[$column->value] = floatval($row[$header[$column->value]] ?? 0);
            }

            $imports[] = $values;
        }

        UserDefaultImports::create([
            'import_type' => DefaultImports::FERTILIZERS,
            'imports' => $imports,
            'model_type' => FarmFertilizer::class,
            'sync_hash' => $hash,
        ]);
    }
}

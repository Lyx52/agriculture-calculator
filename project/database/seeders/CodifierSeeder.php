<?php

namespace Database\Seeders;

use App\Enums\DefinedCodifiers;
use App\Models\Codifier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class CodifierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disk = Storage::disk('local');

        foreach (DefinedCodifiers::cases() as $codifierType) {
            $parent = Codifier::firstOrCreate(['code' => $codifierType->value], ['code' => $codifierType->value, 'name' => $codifierType->getLabel()]);

            switch ($codifierType) {
                case DefinedCodifiers::CROP_SPECIES: {
                    $species = (array)json_decode($disk->get('codifiers/species.json'));

                    foreach ($species as $id => $label) {
                        Codifier::firstOrCreate(
                            ['code' => "crop_species_$id", 'parent_id' => $parent->id],
                            ['code' => "crop_species_$id", 'parent_id' => $parent->id, 'name' => $label]
                        );

                    }
                } break;
                case DefinedCodifiers::CROP_PROTECTION_USAGE: {
                    $usages = (array)json_decode($disk->get('codifiers/crop_protection_usage.json'));

                    foreach ($usages as $id => $label) {
                        Codifier::firstOrCreate(
                            ['code' => "crop_usage_$id", 'parent_id' => $parent->id],
                            ['code' => "crop_usage_$id", 'parent_id' => $parent->id, 'name' => $label]
                        );
                    }
                } break;
                case DefinedCodifiers::OPERATION_TYPES: {
                    $operations = (array)json_decode($disk->get('codifiers/operations.json'));

                    foreach ($operations as $id => $label) {
                        Codifier::firstOrCreate(
                            ['code' => "operation_type_$id", 'parent_id' => $parent->id],
                            ['code' => "operation_type_$id", 'parent_id' => $parent->id, 'name' => $label]
                        );
                    }
                } break;
                case DefinedCodifiers::AGRICULTURE_TECHNOLOGY: {
                    $technologies = (array)json_decode($disk->get('codifiers/agriculture_technologies.json'));

                    foreach ($technologies as $code => $label) {
                        Codifier::firstOrCreate(
                            ['code' => $code, 'parent_id' => $parent->id],
                            ['code' => $code, 'parent_id' => $parent->id, 'name' => $label]
                        );
                    }
                } break;
                case DefinedCodifiers::AGRICULTURE_EQUIPMENT_TYPE: {
                    $equipmentTypes = collect(json_decode($disk->get('codifiers/equipment_categories.json')));

                    $this->recursiveCodifiers($equipmentTypes, $parent);
                } break;
            }
        }
    }

    private function recursiveCodifiers(Collection $items, Codifier $parent) {
        foreach ($items->where('parent_code', $parent->code) as $item) {
            $created = Codifier::firstOrCreate(
                ['code' => $item->code, 'parent_id' => $parent->id],
                ['code' => $item->code, 'parent_id' => $parent->id, 'name' => $item->name]
            );

            $this->recursiveCodifiers($items, $created);
        }
    }
}

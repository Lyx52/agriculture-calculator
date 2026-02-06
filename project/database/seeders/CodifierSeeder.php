<?php

namespace Database\Seeders;

use App\Enums\DefinedCodifiers;
use App\Models\Codifier;
use Illuminate\Database\Seeder;
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
            }
        }
    }
}

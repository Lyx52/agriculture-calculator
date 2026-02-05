<?php

namespace Database\Seeders;

use App\Enums\DefinedCodifiers;
use App\Models\Codifier;
use Illuminate\Database\Seeder;

class CodifierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (DefinedCodifiers::cases() as $codifierType) {
            Codifier::firstOrCreate(['code' => $codifierType->value], ['code' => $codifierType->value, 'name' => $codifierType->getLabel()]);
        }
    }
}

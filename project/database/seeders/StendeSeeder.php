<?php

namespace Database\Seeders;

use App\Enums\DefinedCodifiers;
use App\Enums\UnitType;
use App\Models\Farm;
use App\Models\FarmCrop;
use App\Models\FarmFertilizer;
use App\Models\Farmland;
use App\Models\FarmlandOperation;
use App\Models\FarmPlantProtection;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StendeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var User $user */
        $user = User::createOrFirst(['email' => 'stende@lbtu.lv'], [
            'name' => 'stende',
            'email' => 'stende@lbtu.lv',
            'password' => bcrypt('stende'),
        ]);

        $user->plantProtectionProducts()->createOrFirst([ 'name' => 'NPK 7-20-30', 'owner_id' => $user->id ], [
            'owner_id' => $user->id,
            'name' => 'NPK 7-20-30',
            'protection_category_codes' => ["crop_usage_3471"],
            'unit_type' => UnitType::LITERS,
            'cost_per_unit' => 10
        ]);

        $user->plantProtectionProducts()->createOrFirst([ 'name' => 'Zoom', 'owner_id' => $user->id ], [
            'owner_id' => $user->id,
            'name' => 'Zoom',
            'protection_category_codes' => ["crop_usage_3471"],
            'unit_type' => UnitType::LITERS,
            'company' => 'Agri Crop Solutions',
            'cost_per_unit' => 10
        ]);

        $user->plantProtectionProducts()->createOrFirst([ 'name' => 'Brasitrel PRO', 'owner_id' => $user->id ], [
            'owner_id' => $user->id,
            'name' => 'Brasitrel PRO',
            'protection_category_codes' => ["crop_usage_3471"],
            'unit_type' => UnitType::LITERS,
            'company' => 'YaraVita',
            'cost_per_unit' => 10
        ]);

        $user->plantProtectionProducts()->createOrFirst([ 'name' => 'Bortrac', 'owner_id' => $user->id ], [
            'owner_id' => $user->id,
            'name' => 'Bortrac',
            'protection_category_codes' => ["crop_usage_3471"],
            'unit_type' => UnitType::LITERS,
            'company' => 'YaraVita',
            'cost_per_unit' => 10
        ]);

        $user->plantProtectionProducts()->createOrFirst([ 'name' => 'Dasch', 'owner_id' => $user->id ], [
            'owner_id' => $user->id,
            'name' => 'Dasch',
            'protection_category_codes' => ["crop_usage_3468"],
            'unit_type' => UnitType::LITERS,
            'company' => 'BASF',
            'cost_per_unit' => 10
        ]);

        $user->fertilizers()->createOrFirst([ 'name' => '(N)-27 (S)-4', 'owner_id' => $user->id ], [
            'owner_id' => $user->id,
            'name' => '(N)-27 (S)-4',
            'unit_type' => UnitType::KILOGRAMS,
            'company' => 'YaraBela',
            'cost_per_unit' => 1,
            'value_n' => 27,
            'value_s' => 4
        ]);

        /** @var Farm $farm */
        $farm = $user->farms()->first();
        if (empty($farm)) {
            $farm = Farm::createOrFirst(['owner_id' => $user->id], ['name' => 'Stende', 'owner_id' => $user->id]);
        }

        $farm->farmlands()->forceDelete();
        $disk = Storage::disk('local');

        $farmlands = json_decode($disk->get('stende/farmlands.json'));
        $farmlandsByName = [];
        foreach ($farmlands as $farmlandData) {
            /** @var Farmland $farmland */
            $farmland = $farm->farmlands()->firstOrCreate([ 'name' => $farmlandData->name, 'farm_id' => $farm->id ], [
                ...((array)$farmlandData)
            ]);
            $farmlandsByName[$farmland->name] = $farmland;
        }

        $user->equipment()->forceDelete();
        $equipment = collect(json_decode($disk->get('stende/equipment.json')));
        $equipmentByName = [];
        foreach ($equipment as $equipmentData) {
            $equipmentInstance = $user->equipment()->create([
                ...(array)$equipmentData
            ]);

            $equipmentByName["$equipmentInstance->manufacturer $equipmentInstance->model"] = $equipmentInstance->id;
        }

        $operations = collect(json_decode($disk->get('stende/operations.json')))->groupBy('farmland');
        foreach ($operations as $farmlandName => $farmlandOperations) {
            $farmland = $farmlandsByName[(string)$farmlandName];
            if (empty($farmland)) {
                continue;
            }
            $farmland->operations()->forceDelete();
            foreach ($farmlandOperations as $operationData) {
                /** @var FarmlandOperation $operation */
                $operation = $farmland->operations()->create([
                    'operation_date' => $operationData->operation_date,
                    'operation_code' => "operation_type_{$operationData->operation_code}",
                ]);
                $usedMaterials = $operationData->used_materials ?? [];

                foreach ($usedMaterials as $materialData) {
                    $material = $this->getMaterial($materialData, $user);
                    if (empty($material)) {
                        throw new Exception("'$materialData->name', '$materialData->material_type'");
                    }

                    $operation->materials()->create([
                        'material_type' => $materialData->material_type,
                        'material_id' => $material->id,
                        'material_amount_type' => $materialData->material_amount_type,
                        'material_amount' => $materialData->material_amount,
                    ]);
                }

                $usedEquipment = $operationData->used_equipment ?? [];

                foreach ($usedEquipment as $equipmentData) {
                    $equipmentId = $equipmentByName[$equipmentData->equipment];
                    if (empty($equipmentId)) {
                        throw new Exception($equipmentData->equipment);
                    }
                    $attachmentId = $equipmentByName[$equipmentData->attachment ?? ''] ?? null;

                    $operation->operationEquipment()->create([
                        'equipment_id' => $equipmentId,
                        'attachment_id' => $attachmentId,
                    ]);
                }
            }
        }
    }

    private function getMaterial(object $materialData, User $user): ?Model {
        return match($materialData->material_type) {
            FarmPlantProtection::class => $user->plantProtectionProducts()->firstWhere('name', $materialData->name),
            FarmCrop::class => $user->crops()->firstWhere('name', $materialData->name),
            FarmFertilizer::class => $user->fertilizers()->firstWhere('name', $materialData->name),
        };
    }
}

<?php

namespace Database\Seeders;

use App\Enums\DefinedCodifiers;
use App\Models\Farm;
use App\Models\FarmCrop;
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
                        throw new Exception("$materialData->name");
                        continue;
                    }

                    $operation->materials()->create([
                        'material_type' => $materialData->material_type,
                        'material_id' => $material->id,
                        'material_amount_type' => $materialData->material_amount_type,
                        'material_amount' => $materialData->material_amount,
                    ]);
                }
            }
        }
    }

    private function getMaterial(object $materialData, User $user): ?Model {
        return match($materialData->material_type) {
            FarmPlantProtection::class => $user->plantProtectionProducts()->firstWhere('name', $materialData->name),
            FarmCrop::class => $user->crops()->firstWhere('name', $materialData->name),
        };
    }
}

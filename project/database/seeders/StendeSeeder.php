<?php

namespace Database\Seeders;

use App\Models\Farm;
use App\Models\User;
use Illuminate\Database\Seeder;

class StendeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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


    }
}

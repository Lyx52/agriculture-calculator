<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserDefaultImports;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;

class ImportUserDefaultsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;
        UserDefaultImports::all()->each(function (UserDefaultImports $import) use ($user) {
            /** @var class-string<Model> $importModel */
            $importModel = $import->model_type;
            // Skip if we already have existing records
            if ($importModel::where('owner_id', $user->id)->count() > 0) {
                return;
            }

            foreach($import->imports as $record) {
                $importModel::create([
                    ...$record,
                    'owner_id' => $user->id,
                ]);
            }
        });
    }
}

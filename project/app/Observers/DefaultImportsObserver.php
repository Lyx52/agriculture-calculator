<?php

namespace App\Observers;

use App\Jobs\ImportUserDefaultsJob;
use App\Models\User;

class DefaultImportsObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        ImportUserDefaultsJob::dispatch($user);
    }
}

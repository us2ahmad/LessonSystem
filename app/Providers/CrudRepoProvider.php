<?php

namespace App\Providers;

use App\Http\Controllers\Student\StudentOrderController;
use App\Interfaces\CrudRepoInterface;
use App\Repository\StudentOrderRepo;
use Illuminate\Support\ServiceProvider;

class CrudRepoProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->when(StudentOrderController::class)
            ->needs(CrudRepoInterface::class)
            ->give(function () {
                return new StudentOrderRepo();
            });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace HoangPhi\PreCommit\Providers;

use HoangPhi\PreCommit\Commands\CreatePhpCsCommand;
use HoangPhi\PreCommit\Commands\InstallHookCommand;
use HoangPhi\PreCommit\Commands\PreCommitHookCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class PreCommitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/pre-commit.php', 'pre-commit');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/pre-commit.php' => config_path('pre-commit.php'),
            ], 'config');

            $this->commands([
                InstallHookCommand::class,
                PreCommitHookCommand::class,
                CreatePhpCsCommand::class,
            ]);

            $this->app->booted(function () {
                Artisan::call('vendor:publish', [
                    '--provider' => 'HoangPhi\PreCommit\Providers\PreCommitServiceProvider',
                    '--tag' => 'config'
                ]);
            });
        }
    }
}

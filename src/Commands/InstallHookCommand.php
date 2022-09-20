<?php

namespace HoangPhi\PreCommit\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class InstallHookCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pre-commit:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command install pre-commit hook.';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws ReflectionException
     */
    public function handle()
    {
        if (!app()->isLocal()) {
            $this->warn('[Pre-commit] No development env.');
            return 0;
        }

        $hooks = config('pre-commit.hooks');
        if (!$hooks) {
            $this->warn('[Pre-commit] No hooks found.');
            return 0;
        }

        foreach ($hooks as $hook => $cmd) {
            $this->installHook($hook, $cmd)
                ? $this->info('[Pre-commit] Hook ' . $hook . ' successfully installed.')
                : $this->error('[Pre-commit] Unable to install ' . $hook . ' hook.');
        }

        return 0;
    }

    /**
     * Install the hook command.
     *
     * @param $hook
     * @param $class
     * @return bool
     * @throws ReflectionException
     */
    protected function installHook($hook, $class): bool
    {
        $signature = $this->getCommandSignature($class);
        $script = $this->getHookScript($signature);
        $path = base_path('.git/hooks/' . $hook);

        if (file_exists($path) && md5_file($path) != md5($script)) {
            if (!$this->confirmToProceed('[Pre-commit\] ' . $path . ' already exists, do you want to overwrite it?', true)) {
                return false;
            }
        }

        return $this->writeHookScript($path, $script);
    }

    /**
     * Get command signature.
     *
     * @param string $class
     * @return string
     * @throws ReflectionException
     */
    protected function getCommandSignature(string $class): string
    {
        $reflect = new ReflectionClass($class);
        $properties = $reflect->getDefaultProperties();

        if (!preg_match('/^(\S+)/', $properties['signature'], $matches)) {
            throw new RuntimeException('[Pre-commit] Cannot read signature of ' . $class . '.');
        }

        [, $signature] = $matches;

        return $signature;
    }

    /**
     * Get the hook script content.
     *
     * @param $signature
     * @return string
     */
    protected function getHookScript($signature)
    {
        $artisan = base_path('artisan');

        return "#!/bin/sh\n/usr/bin/env php " . addslashes($artisan) . ' ' . $signature . "\n";
    }

    /**
     * Writes hook script
     *
     * @param $path
     * @param $script
     * @return bool
     */
    protected function writeHookScript($path, $script)
    {
        if (!$result = file_put_contents($path, $script)) {
            return false;
        }

        if (!chmod($path, 0755)) {
            return false;
        }

        return true;
    }
}

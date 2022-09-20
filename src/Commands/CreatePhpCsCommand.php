<?php

namespace HoangPhi\PreCommit\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class CreatePhpCsCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pre-commit:create-phpcs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command create PSR default config.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $phpCs = __DIR__ . '/../../phpcs.xml';
        $rootPhpCs = base_path('phpcs.xml');

        // Checkout existence of sample phpcs.xml.
        if (!file_exists($phpCs)) {
            $this->error('[Pre-commit] The sample phpcs.xml does not exist! Try to reinstall hoangphi/pre-commit package!');
            return 0;
        }

        // Checkout existence phpcs.xml in root path of project.
        if (file_exists($rootPhpCs)) {
            if (!$this->confirmToProceed('[Pre-commit] phpcs.xml already exists, do you want to overwrite it?', true)) {
                return 1;
            }

            // Remove old phpcs.xml file form root
            unlink($rootPhpCs);
        }

        $this->writePHPCS($phpCs, $rootPhpCs) ? $this->info('[Pre-commit] phpcs.xml successfully created!') : $this->error('[Pre-commit] Unable to create phpcs.xml');

        return 0;
    }

    /**
     * Copy phpcs.xml file to root
     *
     * @param $phpCs
     * @param $rootPhpCs
     * @return bool
     */
    protected function writePHPCS($phpCs, $rootPhpCs)
    {
        if (!copy($phpCs, $rootPhpCs)) {
            return false;
        }

        return true;
    }
}

<?php

/**
 * Author: Sadnub
 * url: https://github.com/sadnub/laravel-mongodb-passport-fix/blob/master/MongoDBPassportFix.php
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MongoPassportFix extends Command
{

    protected $signature = 'fix:passport
                            {--rollback : Rollback the Passport fix}';

    protected $description =  "Resolves passport support issues for Mongodb";

    protected $mongodb_model = 'Jenssegers\Mongodb\Eloquent\Model';

    protected $default_model = 'Illuminate\Database\Eloquent\Model';

    protected $passport_path = 'vendor/laravel/passport/src/';

    public function __construct() {
        parent::__construct();
    }

    public function handle()
    {
        if (!$this->option('rollback')) {
            $this->fixFiles();
            $this->info('Passport support issues for mongodb resolved');
        }
        else {
            $this->rollbackFiles();
            $this->info("Fixes rolled back successfully");
        }
    }

    protected function fixFiles ()
    {
        foreach (glob(base_path($this->passport_path) . '*.php') as $filename)
        {
            $file = file_get_contents($filename);
            file_put_contents($filename, str_replace($this->default_model, $this->mongodb_model, $file));
            $this->info($filename . ' has been resolved');
        }
    }

    protected function rollbackFiles()
    {
        foreach (glob(base_path($this->passport_path) . '*.php') as $filename)
        {
            $file =  file_get_contents($filename);
            file_put_contents($filename, str_replace($this->mongodb_model, $this->default_model, $file));
            $this->info($filename . ' has been reversed');
        }
    }
}

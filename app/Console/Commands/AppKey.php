<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AppKey extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:key
        {app}
        {--s|show : Display the key instead of modifying files.}
        {--always-no : Skip generating key if it already exists.}
        {--f|force : Skip confirmation when overwriting an existing key.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the APP Key secret used to make requests from another application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $app = mb_strtoupper($this->argument('app'));

        $key = $app . '-' . Hash::make(Str::random(80));

        if ($this->option('show')) {
            $this->comment($key);

            return;
        }

        if (file_exists($path = $this->envPath()) === false) {
            return $this->displayKey($key, $app);
        }

        if (Str::contains(file_get_contents($path), $app . '_KEY') === false) {
            // create new entry
            file_put_contents($path, PHP_EOL . $app . "_KEY=$key" . PHP_EOL, FILE_APPEND);
        } else {
            if ($this->option('always-no')) {
                $this->comment($app . ' key already exists. Skipping...');

                return;
            }

            if ($this->isConfirmed() === false) {
                $this->comment('Phew... No changes were made to ' . $app . ' key.');

                return;
            }

            // update existing entry
            file_put_contents($path, str_replace(
                $app . '_KEY=' . $this->laravel['config'][$app . '.key'],
                $app . '_KEY=' . $key,
                file_get_contents($path)
            ));
        }

        $this->displayKey($key, $app);
    }

    /**
     * Display the key.
     *
     * @param  string  $key
     *
     * @return void
     */
    protected function displayKey($key, $app)
    {
        $this->laravel['config'][$app . '.secret'] = $key;

        $this->info("$app key [$key] set successfully.");
    }

    /**
     * Check if the modification is confirmed.
     *
     * @return bool
     */
    protected function isConfirmed()
    {
        return $this->option('force') ? true : $this->confirm(
            'This will invalidate all existing tokens. Are you sure you want to override the secret key?'
        );
    }

    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath()
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        // check if laravel version Less than 5.4.17
        if (version_compare($this->laravel->version(), '5.4.17', '<')) {
            return $this->laravel->basePath() . DIRECTORY_SEPARATOR . '.env';
        }

        return $this->laravel->basePath('.env');
    }
}

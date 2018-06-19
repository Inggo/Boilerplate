<?php

namespace Inggo\Boilerplate\Console\Commands;

use Illuminate\Console\Command;
use Inggo\Boilerplate\User;

class Install extends Command
{
    private $user;

    protected $signature = 'boilerplate:install
                            {--test : Whether to create test model instances }';

    protected $description = 'Installs Inggo\Boilerplate';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('Resetting database...');

        $this->call('migrate:fresh');

        $this->call('migrate');

        $this->call('passport:install');

        $this->info('Creating owner account...');

        $this->createAdmin();

        $this->info('Creating API');

        $this->call('passport:client', [
            '--personal' => true,
            '--name' => config('app.name') . ' Personal Access Client',
        ]);

        if ($this->option('test')) {
            $this->info('Test flag detected!');

            $this->info('Creating test users...');
        }

        $this->info('Clearing cache');

        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');

    }

    private function createAdmin()
    {
        User::create([
            'name'     => env('ADMIN_NAME', 'admin'),
            'username' => env('ADMIN_USERNAME', 'admin'),
            'email'    => env('ADMIN_EMAIL', 'admin@email.com'),
            'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
            'role'     => 'owner',
        ]);
    }
}

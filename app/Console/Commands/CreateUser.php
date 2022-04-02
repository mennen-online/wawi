<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Admin User';

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
        $name = $this->ask('Username');

        $email = $this->ask('E-Mail');

        $password = $this->secret('Password');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($password)
        ]);

        if($user->exists) {
            $this->info('User has been created successfully');
        } else {
            $this->error('The User could not been created');
        }

        return 0;
    }
}

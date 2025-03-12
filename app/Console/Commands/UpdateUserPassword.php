<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExpectantMother;

class UpdateUserPassword extends Command
{
    protected $signature = 'user:password-update {email} {password}';
    protected $description = 'Update a user\'s password to Bcrypt hash';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $mother = ExpectantMother::where('email', $email)->first();

        if ($mother) {
            $mother->password_hash = bcrypt($password);
            $mother->save();
            $this->info("Password updated to Bcrypt for " . $mother->email);
        } else {
            $this->error("User with email {$email} not found.");
        }
    }
}
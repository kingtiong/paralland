<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paralland:make-admin {identifier : wallet (0x...) or email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant admin role to a wallet address or email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $identifier = trim((string) $this->argument('identifier'));

        if (preg_match('/^0x[a-fA-F0-9]{40}$/', $identifier)) {
            $wallet = strtolower($identifier);
            $user = User::firstOrCreate(['wallet_address' => $wallet], ['role' => 'admin']);
            $user->role = 'admin';
            $user->save();
            $this->info("Wallet {$wallet} is now admin (user_id={$user->id}).");
            return self::SUCCESS;
        }

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $email = strtolower($identifier);

            // If the user doesn't exist yet, create one with a random password
            // (admin can use "forgot password" to set a real password).
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Admin',
                    'password' => Hash::make(Str::random(32)),
                    'role' => 'admin',
                ]
            );

            $user->role = 'admin';
            $user->save();

            $this->info("Email {$email} is now admin (user_id={$user->id}).");
            return self::SUCCESS;
        }

        $this->error('Invalid identifier. Provide a wallet (0x...) or an email.');
        return self::FAILURE;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paralland:make-admin {wallet : 0x-prefixed wallet address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant admin role to a wallet address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $wallet = strtolower((string) $this->argument('wallet'));
        if (!preg_match('/^0x[a-f0-9]{40}$/', $wallet)) {
            $this->error('Invalid wallet address. Expected 0x + 40 hex chars.');
            return self::FAILURE;
        }

        $user = User::firstOrCreate(['wallet_address' => $wallet], ['role' => 'admin']);
        $user->role = 'admin';
        $user->save();

        $this->info("Wallet {$wallet} is now admin (user_id={$user->id}).");

        return self::SUCCESS;
    }
}

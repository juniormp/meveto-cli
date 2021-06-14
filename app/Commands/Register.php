<?php

namespace App\Commands;

use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class Register extends Command
{
    protected $signature = 'register {username}';
    protected $description = 'Register user in Meveto Backend App Example: php [meveto-cli] register mauricio';

    public function handle(): void
    {
        $data = [
            'username' => $this->argument('username'),
            'public_key' => env('USER_PUBLIC_KEY')
        ];

        $response =  Http::post(env('MEVETO_BACKEND_URL') . '/auth/register', $data);

        $response = $response->getBody();

        $this->info($response);
    }
}

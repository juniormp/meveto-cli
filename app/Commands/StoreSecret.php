<?php

namespace App\Commands;

use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

class StoreSecret extends Command
{
    protected $signature = 'store-secret {username} {secret_name} {secret}';
    protected $description = 'Store Secret in Meveto Backend App Example: php [meveto-cli] store-secret mauricio app_test test_value';

    public function handle(): void
    {
        $data = [
            'username' => $this->argument('username'),
            'secret_name' => $this->argument('secret_name'),
            'encrypted_secret' => $this->encryptWithServerPublicKey($this->argument('secret'))
        ];

        $header = [
          'signature' => $this->signRequest($data['username'])
        ];

        $response =  Http::withHeaders($header)
            ->post(env('MEVETO_BACKEND_URL') . '/encryption/storeSecret', $data);

        $response = $response->getBody();

        $this->info($response);
    }

    private function encryptWithServerPublicKey(string $secret): string
    {
        $privateKey = PublicKey::fromString(env('SERVER_PUBLIC_KEY'));

        return base64_encode($privateKey->encrypt($secret));
    }

    private function signRequest(string $username): string
    {
        $privateKey = env('USER_PRIVATE_KEY');

        return PrivateKey::fromString($privateKey)->sign($username);
    }
}

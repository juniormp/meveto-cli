<?php

namespace App\Commands;

use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;
use Spatie\Crypto\Rsa\PrivateKey;

class GetSecret extends Command
{
    protected $signature = 'get-secret {username} {secret_name}';
    protected $description = 'Store Secret in Meveto Backend App Example: php [meveto-cli] get-secret mauricio app_test';

    public function handle(): void
    {
        $data = [
            'username' => $this->argument('username'),
            'secret_name' => $this->argument('secret_name'),
        ];

        $header = [
          'signature' => $this->signRequest($data['username'])
        ];

        $response =  Http::withHeaders($header)
            ->get(env('MEVETO_BACKEND_URL') . '/encryption/getSecret', $data);

        if (json_decode($response->getBody(), true)['success'] !== false){
            $response = json_decode($response->getBody(), true)['data']['encrypted_secret'];
            $this->info($this->decryptWithServerPublicKey($response));
        } else {
            $response = $response->getBody();
            $this->info($response);
        }
    }

    private function decryptWithServerPublicKey(string $secret): string
    {
        $privateKey = PrivateKey::fromString(env('USER_PRIVATE_KEY'));

        return $privateKey->decrypt(base64_decode($secret));
    }

    private function signRequest(string $username): string
    {
        $privateKey = env('USER_PRIVATE_KEY');

        return PrivateKey::fromString($privateKey)->sign($username);
    }
}

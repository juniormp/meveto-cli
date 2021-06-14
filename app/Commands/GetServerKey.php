<?php

namespace App\Commands;

use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class GetServerKey extends Command
{
    protected $signature = 'get-server-key';
    protected $description = 'Get Server Key from Meveto Backend App Example: php [meveto-cli] get-server-key';

    public function handle(): void
    {
        $response =  Http::get(env('MEVETO_BACKEND_URL') . '/encryption/getServerKey');

        $response = json_decode($response->getBody(), true)['data']['public_key'];

        $this->info($response);
    }
}

<?php

namespace AcquaintSofttech\StataMailer\Listeners;

use AcquaintSofttech\StataMailer\Services\DotEnvService;
use Illuminate\Support\Facades\Artisan;


class MailerConfiguration
{
    protected $dotEnvService;

    public function __construct(DotEnvService $dotEnv)
    {
        $this->dotEnvService = $dotEnv;
    }

    public function handle($event)
    {
        $data = $event->data;

        switch($data['mailer']) {
            case('smtp'):
                $this->smtpConfigSave($data);
                break;
        }

        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('statamic:stache:clear');
    }

    public function smtpConfigSave($data)
    {

        $newVals = [
            'MAIL_USERNAME' => $data['smtp_username'],
            'MAIL_PASSWORD' => $data['smtp_password'],
            'MAIL_FROM_ADDRESS' =>  $data['from_email'],
            'MAIL_FROM_NAME' => $data['from_name'],
            'MAIL_HOST' =>  $data['smtp_host'],
            'MAIL_PORT' => $data['smtp_port'],
            'MAIL_ENCRYPTION' => $data['encryption'],
            'MAIL_MAILER' => $data['mailer'],
        ];

        $this->dotEnvService->writeToEnv($newVals);

        // $envString = file_get_contents(base_path('.env'));

        // $lines = explode("\n", $envString);
        // foreach ($lines as $line) {
        //     try {
        //         $envVals = parse_ini_string($line);
        //     } catch (\Throwable $th) {
        //         $envVals = null;
        //     }
        //     if ($envVals) {
        //         foreach ($envVals as $key => $value) {
        //             if (array_key_exists($key, $newVals)) {
        //                 $envString = str_replace($key . '=' . $value, $key . '=' . $newVals[$key], $envString);
        //             }
        //         }
        //     }
        // }

        // $envFile = fopen(base_path('.env.local'), 'w');

        // fwrite($envFile, $envString . PHP_EOL);
        // fclose($envFile);

        
    }
}

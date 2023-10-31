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
       
    }
}

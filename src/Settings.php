<?php

namespace AcquaintSofttech\StataMailer;

use AcquaintSofttech\StataMailer\Services\DotEnvService;
use Illuminate\Support\Collection;
use Statamic\Facades\Blueprint;

class Settings extends Collection
{

    public function __construct($items = null)
    {
        if (!is_null($items)) {
            $items = collect($items)->all();
        }

        $this->items = $items ?? $this->getDefaults();
    }

    public static function load($items = null)
    {
        return new static($items);
    }

    public function augmented()
    {
        $defaultValues = static::blueprint()
            ->fields()
            ->addValues($this->items)
            ->augment()
            ->values();

        return $defaultValues
            ->only(array_keys($this->items))
            ->all();
    }

    protected function getDefaults()
    {
        $dotEnvService = app(DotEnvService::class);
        $env = $dotEnvService->readFromEnv();
        $data = [
            'from_email' => $env['MAIL_FROM_ADDRESS'] ?? '',
            'from_name' => $env['MAIL_FROM_NAME'] ?? '',
            'mailer' => $env['MAIL_MAILER'] ?? '',
            'encryption' => $env['MAIL_ENCRYPTION'] ?? '',
            'smtp_host' => $env['MAIL_HOST'] ?? '',
            'smtp_port' => $env['MAIL_PORT'] ?? '',
            'smtp_username' => $env['MAIL_USERNAME'] ?? '',
            'smtp_password' => $env['MAIL_PASSWORD'] ?? '',
        ];
        
        return $data;
    }

    public static function blueprint()
    {
        return Blueprint::make()->setContents([
            'sections' => [
                'main' => [
                    'fields' => [
                        [
                            'handle' => 'Info',
                            'field' => [
                                'type' => 'section',
                                'icon' => 'section',
                                'instructions' => 'Please check the saved configuration in the `Utilities > Email` directory. If you don\'t see it there, clear the all cache in the `Utilities > Cache Manager` directory and then check again in the `Utilities > Email` directory.',
                                'listable' => 'visible',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                            ]
                        ],
                        [
                            'handle' => 'from_email',
                            'field' => [
                                'type' => 'text',
                                'display' => 'From Email',
                                'input_type' => 'email',
                                'antlers' => 'false',
                                'icon' => 'text',
                                'instructions' => '',
                                'listable' => 'visible',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'validate' => 'required',
                            ]
                        ],
                        [
                            'handle' => 'from_name',
                            'field' => [
                                'input_type' => 'text',
                                'antlers' => 'false',
                                'type' => 'text',
                                'display' => 'From Name',
                                'icon' => 'text',
                                'instructions' => 'From Name setting above will be used for all emails.',
                                'listable' => 'hidden',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'validate' => 'required',
                            ]
                        ],
                        [
                            'handle' => 'mailer',
                            'field' => [
                                'options' => [
                                    'smtp' => 'Other SMTP',
                                    // 'Google/Gmail' => 'Google / Gmail',
                                    // 'SMTP.com' => 'SMTP.com',
                                    // 'Sendinblue' => 'Sendinblue',
                                    // 'AmazonSES' => 'Amazon SES',
                                    // 'Mailgun' => 'Mailgun',
                                    // '365/Outlook' => '365 / Outlook',
                                    // 'Postmark' => 'Postmark',
                                    // 'SendGrid' => 'SendGrid',
                                    // 'SparkPost' => 'SparkPost',
                                    // 'Zoho Mail' => 'Zoho Mail',
                                    // 'SendLayer' => 'SendLayer',
                                ],
                                'type' => 'radio',
                                'display' => 'Mailer',
                                'inline' => true,
                                'icon' => 'radio',
                                'listable' => 'hidden',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'default' => 'smtp',
                                'width' => 50,
                            ]
                        ],
                        [
                            'handle' => 'encryption',
                            'field' => [
                                'options' => [
                                    'ssl' => 'SSL',
                                    'tls' => 'TLS',
                                ],
                                'inline' => true,
                                'cast_booleans' => false,
                                'type' => 'radio',
                                'display' => 'Encryption',
                                'icon' => 'radio',
                                'width' => 50,
                                'instructions' => 'For most servers TLS is the recommended option. If your SMTP provider offers both SSL and TLS options, we recommend using TLS.',
                                'listable' => 'hidden',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'if' => [
                                    'mailer' => 'equals smtp'
                                ],
                                'validate' =>  'required',
                            ]
                        ],
                        [
                            'handle' => 'smtp_host',
                            'field' => [
                                'input_type' => 'text',
                                'antlers' => 'false',
                                'type' => 'text',
                                'display' => 'Host',
                                'icon' => 'text',
                                'listable' => 'hidden',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'if' => [
                                    'mailer' => 'equals smtp'
                                ],
                                'width' => 50,
                                'validate' =>  'required',
                            ]
                        ],
                        [
                            'handle' => 'smtp_port',
                            'field' => [
                                'input_type' => 'text',
                                'antlers' => false,
                                'type' => 'text',
                                'display' => 'Port',
                                'icon' => 'text',
                                'listable' => 'hidden',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'width' => 50,
                                'if' => [
                                    'mailer' => 'equals smtp'
                                ],
                                'validate' =>  'required',
                            ]
                        ],
                        [
                            'handle' => 'smtp_username',
                            'field' => [
                                'input_type' => 'text',
                                'antlers' => false,
                                'type' => 'text',
                                'display' => 'Username',
                                'icon' => 'text',
                                'listable' => 'hidden',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'if' => [
                                    'mailer' => 'equals smtp'
                                ],
                                'validate' =>  'required',
                            ]
                        ],
                        [
                            'handle' => 'smtp_password',
                            'field' => [
                                'input_type' => 'password',
                                'antlers' => false,
                                'type' => 'text',
                                'display' => 'Password',
                                'icon' => 'text',
                                'listable' => 'hidden',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'if' => [
                                    'mailer' => 'equals smtp'
                                ],
                                'validate' =>  'required',
                            ]
                        ]
                    ],
                ],
            ],
        ]);
    }
}

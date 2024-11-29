<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    public function send($to_email, $to_name, $subject, $template, $vars =null)
    {

        $content = file_get_contents(dirname(__DIR__).'/Mail/'.$template);

        if($vars)
        {
            foreach ($vars as $key=>$var){
                $content = str_replace('{'.$key.'}', $var, $content);
            }
        }

        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "sassi.lokman2@gmail.com",
                        'Name' => "La Boutique Francaise"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 6517623,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $mj->post(Resources::$Email, ['body' => $body]);
    }
}
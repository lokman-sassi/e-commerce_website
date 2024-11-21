<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {

        $client = static::createClient();
        $client->request('GET', '/inscription');

        $client->submitForm('Valider',[
            'register_user[email]' => 'browsertest@gmail.com',
            'register_user[plainPassword][first]' => 'google',
            'register_user[plainPassword][second]' => 'google',
            'register_user[firstname]' => 'Google',
            'register_user[lastname]' => 'Chrome',
        ]);

        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("Votre compte est correctement crée, veuillez vous connecter.")');
    }
}

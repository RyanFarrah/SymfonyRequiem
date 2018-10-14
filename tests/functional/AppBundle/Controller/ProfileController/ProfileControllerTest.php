<?php

namespace Tests\Functional\AppBundle\Controller\ProfileController\ProfileController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    public function testProfileUserNotConnected()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profile');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testProfileUserConnected() {

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'username',
            'PHP_AUTH_PW' => 'password'
        ));

        $crawler = $client->request('GET', '/profile');


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
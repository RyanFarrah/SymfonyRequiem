<?php

namespace Tests\Functional\AppBundle\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    /**
     * Test when user not connected enter member area
     * Expect redirection 
     * 
     * @return void
     */
    public function testUserNotConnectedInMemberArea()
    {
        $client = static::createClient();

        $client->request('GET', '/profile');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertEquals("http://localhost/login", $crawler->getUri());
    }

    /**
     * Test when user connected enter member area
     * Expect HTTP code 200
     *
     * @return void
     */
    public function testUserConnectedInMemberArea() {

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'username',
            'PHP_AUTH_PW' => 'password'
        ));

        $crawler = $client->request('GET', '/profile');


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Test when user login
     * Expect redirection to profile zone after successful login
     *
     * @return void
     */
    public function testLoginUserConnection() {

        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $buttonCrawlerNode = $crawler->selectButton('submit');

        $form = $buttonCrawlerNode->form(array(
            '_username'    => 'username',
            '_password'    => 'password'
        ));

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertEquals("http://localhost/profile", $crawler->getUri());

    }
}
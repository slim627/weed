<?php

namespace APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', static::$kernel->getContainer()->get('router')->generate('api.hello'));

        $content = json_decode($client->getResponse()->getContent());
        $this->assertContains('Hello', $content->response);
    }
}

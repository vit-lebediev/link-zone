<?php

namespace LinkZone\Core\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ManageUsersControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/manage/users');
    }

    public function testShowspecific()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/manage/users/{userId}');
    }

}

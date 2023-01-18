<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerControllerIndexTest extends WebTestCase
{
    public function testIndexIsWorking()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testCustomerPageIsNotWorking_NotConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/clients');

        $this->assertResponseRedirects();
        $this->assertResponseStatusCodeSame(302);
    }

    public function testCompanyPageIsNotWorking_NotConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/entreprises');

        $this->assertResponseRedirects();
        $this->assertResponseStatusCodeSame(302);
    }
}
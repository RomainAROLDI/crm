<?php

namespace App\Tests\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerControllerDeleteTest extends WebTestCase
{
    protected $em;

    protected function setUp(): void
    {
        parent::setUp();
        $client = static::createClient();
        $this->em = $client->getContainer()->get('doctrine.orm.entity_manager');
    }


    public function testCustomerIsDeleted_NotConnected()
    {
        $client = static::createClient();
        $client->request('DELETE', '/clients/delete/10');

        $this->assertResponseStatusCodeSame(405);
    }

    public function testCustomerIsDeleted_Connected()
    {
        $client = static::createClient();

        $user = new User();
        $user->setEmail('ok@ok.fr');
        $user->setPassword('ok');
        $this->em->persist($user);
        $this->em->flush();
        
        // Authenticate the client as the test user
        $client = static::createClient();
        $client->setServerParameter('PHP_AUTH_USER', $user->getEmail());
        $client->setServerParameter('PHP_AUTH_PW', $user->getPassword());
        $client->request('DELETE', '/clients/delete/10');
        
        $this->assertResponseIsSuccessful();
    }

//     public function testCustomerDeleteCustomerThatDoesntExist_Connected()
//     {
//         $client = static::createClient();
//         $client->setServerParameter('PHP_AUTH_USER', 'user@crm.fr');
//         $client->setServerParameter('PHP_AUTH_PW', 'toto');
//         $client->request('DELETE', '/clients/delete/100015');

//         $this->assertResponseStatusCodeSame(204);
//     }
    
//     public function testCustomerDeleteCustomerThatDoesntExist_NotConnected()
//     {
//         $client = static::createClient();
//         $client->request('DELETE', '/clients/delete/1000015');

//         $this->assertResponseStatusCodeSame(405);
//     }
}
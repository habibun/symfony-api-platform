<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ProductResourceTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    // test create product
    public function testCreateProduct()
    {
        $client = self::createClient();
        $client->request('POST', '/api/products', [], [], [], [

        ]);
        $this->assertResponseStatusCodeSame(401);


        // create new user
        $user = new User();
        $user->setUsername('user1');
        $user->setEmail('user1@localhost.com');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$z5LDVzV17j7VdApSLiX51A$qJN0dTwj6Shy1PedVKfU8TFPaI17cqlmscVkwKiz8OQ');

        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        // create a login request
        $client->request('POST', '/api/login', [
           'json' => [
               'email' => 'user1@localhost.com',
               'password' => 'user1',
           ]
        ]);

        // check status
        $this->assertResponseStatusCodeSame(204);
    }
}

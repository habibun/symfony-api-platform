<?php

namespace App\Tests\Functional;

use App\Entity\Product;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ProductResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    // test create product
    public function testCreateProduct()
    {
        $client = self::createClient();
        $client->request('POST', '/api/products');

        $this->assertResponseStatusCodeSame(401);

        $authenticatedUser = $this->createUserAndLogIn($client, 'cheeseplease@example.com', 'foo');
        $otherUser = $this->createUser('otheruser@example.com', 'foo');


        $cheesyData = [
            'title' => 'Mystery cheese... kinda green',
            'description' => 'What mysteries does it hold?',
            'price' => 5000
        ];

        $client->request('POST', '/api/products', [
            'json' => $cheesyData,
        ]);
        $this->assertResponseStatusCodeSame(201);



        $client->request('POST', '/api/cheeses', [
            'json' => $cheesyData + ['owner' => '/api/users/'.$otherUser->getId()],
        ]);

        $this->assertResponseStatusCodeSame(400, 'not passing the correct owner');

        $client->request('POST', '/api/cheeses', [
            'json' => $cheesyData + ['owner' => '/api/users/'.$authenticatedUser->getId()],
        ]);
        $this->assertResponseStatusCodeSame(201);

    }

    public function testUpdateProduct()
    {
        $client = self::createClient();
        $user1 = $this->createUser('user1@localhost.com', 'user1');
        $user2 = $this->createUser('user2@localhost.com', 'user2');

        $product = (new Product())
            ->setName('product10')
            ->setDescription('product10 description')
            ->setPrice(10.0)
            ->setManufacturer($user1);

        $em = $this->getEntityManager();
        $em->persist($product);
        $em->flush();

        $this->logIn($client, 'user2@localhost.com', 'user2');

        $client->request('PUT', '/api/products/'.$product->getId(), [
            'json' => [
                'description' => 'new description',
                'manufacture' => '/api/users/'.$user2->getId()
            ]
        ]);

        self::assertResponseStatusCodeSame(403);
    }

    public function testGetProductCollection()
    {
        $client = self::createClient();
        $user = $this->createUser('cheeseplese@example.com', 'foo');

        $cheeseListing1 = new Product('cheese1');
        $cheeseListing1->setManufacturer($user);
        $cheeseListing1->setPrice(1000);
        $cheeseListing1->setDescription('cheese');

        $cheeseListing2 = new Product('cheese2');
        $cheeseListing2->setManufacturer($user);
        $cheeseListing2->setPrice(1000);
        $cheeseListing2->setDescription('cheese');
        $cheeseListing2->setIsActive(true);

        $cheeseListing3 = new Product('cheese3');
        $cheeseListing3->setManufacturer($user);
        $cheeseListing3->setPrice(1000);
        $cheeseListing3->setDescription('cheese');
        $cheeseListing2->setIsActive(true);

        $em = $this->getEntityManager();
        $em->persist($cheeseListing1);
        $em->persist($cheeseListing2);
        $em->persist($cheeseListing3);
        $em->flush();

        $client->request('GET', '/api/cheeses');
        $this->assertJsonContains(['hydra:totalItems' => 2]);
    }

    public function testGetCheeseListingItem()
    {
        $client = self::createClient();
        $user = $this->createUser('cheeseplese@example.com', 'foo');
        $cheeseListing1 = new Product('cheese1');
        $cheeseListing1->setManufacturer($user);
        $cheeseListing1->setPrice(1000);
        $cheeseListing1->setDescription('cheese');
        $cheeseListing1->setIsActive(false);

        $em = $this->getEntityManager();
        $em->persist($cheeseListing1);
        $em->flush();

        $client->request('GET', '/api/cheeses/'.$cheeseListing1->getId());
        $this->assertResponseStatusCodeSame(404);
    }
}

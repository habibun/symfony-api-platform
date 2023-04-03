<?php

namespace App\Tests\Functional;

use App\Entity\Product;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ProductResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    // test create product

    /**
     * @throws TransportExceptionInterface
     */
    public function testCreateProduct()
    {
        $client = self::createClient();
        $client->request('POST', '/api/products');

        $authenticatedUser = $this->createUserAndLogIn($client, 'productplease@example.com', 'foo');
        $otherUser = $this->createUser('otheruser@example.com', 'foo');

        $productData = [
            'name' => 'Mystery product... kinda green',
            'description' => 'What mysteries does it hold?',
            'price' => "5000",
        ];

//        $client->request('POST', '/api/products', [
//            'json' => $productData + ['manufacturer' => '/api/users/'.$otherUser->getId()],
//        ]);
//        $this->assertResponseStatusCodeSame(400, 'not passing the correct manufacturer');

        $client->request('POST', '/api/products', [
            'json' => $productData + ['owner' => '/api/users/'.$authenticatedUser->getId()],
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
            ->setManufacturer($user1)
            ->setIsActive(true)
        ;

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
        $user = $this->createUser('productplese@example.com', 'foo');

        $product1 = new Product();
        $product1->setName('product 1');
        $product1->setManufacturer($user);
        $product1->setPrice(1000);
        $product1->setDescription('product');
        $product1->setIsActive(false);

        $product2 = new Product();
        $product2->setName('product 2');
        $product2->setManufacturer($user);
        $product2->setPrice(1000);
        $product2->setDescription('product');
        $product2->setIsActive(true);

        $product3 = new Product();
        $product3->setName('product 3');
        $product3->setManufacturer($user);
        $product3->setPrice(1000);
        $product3->setDescription('product');
        $product3->setIsActive(true);

        $em = $this->getEntityManager();
        $em->persist($product1);
        $em->persist($product2);
        $em->persist($product3);
        $em->flush();

        $client->request('GET', '/api/products');
        $this->assertJsonContains(['hydra:totalItems' => 2]);
    }

    public function testGetProductListingItem()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'cheeseplese@example.com', 'foo');

        $product1 = new Product();
        $product1->setName('product 1');
        $product1->setManufacturer($user);
        $product1->setPrice(1000);
        $product1->setDescription('cheese');
        $product1->setIsActive(false);

        $em = $this->getEntityManager();
        $em->persist($product1);
        $em->flush();

        $client->request('GET', '/api/products/'.$product1->getId());
        $this->assertResponseStatusCodeSame(404);

        $client->request('GET', '/api/users/'.$user->getId());
        $data = $client->getResponse()->toArray();
        $this->assertEmpty($data['products']);
    }

    public function testActiveProduct()
    {
        $client = self::createClient();
        $user = UserFactory::new()->create();
        $product = ProductFactory::new()->create([
            'manufacturer' => $user,
        ]);
        $this->logIn($client, $user);
        $client->request('PUT', '/api/products/'.$product->getId(), [
            'json' => ['isActive' => true]
        ]);

        $this->assertResponseStatusCodeSame(200);

        $product->refresh();
        $product->save();

        $this->assertTrue($product->isIsActive());

    }
}

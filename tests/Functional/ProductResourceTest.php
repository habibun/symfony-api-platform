<?php

namespace App\Tests\Functional;

use App\Entity\Product;
use App\Entity\ProductNotification;
use App\Factory\ProductFactory;
use App\Factory\ProductNotificationFactory;
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
            'json' => $productData + ['manufacturer' => '/api/users/'.$authenticatedUser->getId()],
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

        $this->assertTrue($product->getIsActive());
        ProductNotificationFactory::repository()->assertCount(1, 'There should be one notification about being published');

        $product->save();
        // publishing again should not create a second notification
        $client->request('PUT', '/api/products/'.$product->getId(), [
            'json' => ['isActive' => true]
        ]);
        $product->save();
        ProductNotificationFactory::repository()->assertCount(1);
    }

    public function testActiveProductValidation()
    {
        $client = self::createClient();
        $user = UserFactory::new()
            ->create()
            ->disableAutoRefresh();

        $adminUser = UserFactory::new()
            ->create(['roles' => ['ROLE_ADMIN']])
            ->disableAutoRefresh();

        $product = ProductFactory::new()
            ->create(['manufacturer' => $user, 'description' => 'short'])
            ->disableAutoRefresh();

        // 1) the manufacturer CANNOT publish with a short description
        $this->logIn($client, $user);
        $client->request('PUT', '/api/products/'.$product->getId(), [
            'json' => ['isActive' => true]
        ]);
//        $product->save();

//        dd($client->getResponse());
        $this->assertResponseStatusCodeSame(422, 'description is too short');

        // 2) an admin user CAN publish with a short description
        $this->logIn($client, $adminUser);
        $client->request('PUT', '/api/products/'.$product->getId(), [
            'json' => ['isActive' => true]
        ]);
//        $product->save();

        $this->assertResponseStatusCodeSame(200, 'admin CAN publish a short description');

//        $product->save();
        $product->refresh();

        $this->assertTrue($product->getIsActive());

        // 3) a normal user CAN make other changes to their listing
        $this->logIn($client, $user);
        $client->request('PUT', '/api/products/'.$product->getId(), [
            'json' => ['price' => '12345']
        ]);

//        dd($client->getResponse()->getContent());

        $this->assertResponseStatusCodeSame(200, 'user can make other changes on short description');

//        $product->save();
        $product->refresh();
        $this->assertSame('12345', $product->getPrice());

        // 4) a normal user CANNOT unpublish
        $this->logIn($client, $user);
        $client->request('PUT', '/api/products/'.$product->getId(), [
            'json' => ['isActive' => false]
        ]);

        $this->assertResponseStatusCodeSame(422, 'normal user cannot unpublish');

        // 5) an admin user CAN unpublish
        $this->logIn($client, $adminUser);
        $client->request('PUT', '/api/products/'.$product->getId(), [
            'json' => ['isActive' => false]
        ]);
//        $product->save();

        $this->assertResponseStatusCodeSame(200, 'admin can unpublish');
        $product->refresh();
        $this->assertFalse($product->getIsActive());
    }
}

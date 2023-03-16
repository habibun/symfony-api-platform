<?php

namespace App\Tests\Functional;

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

        $this->createUserAndLogIn($client, 'user1@localhost.com', 'user1');
    }
}

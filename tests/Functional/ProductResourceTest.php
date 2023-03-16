<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class ProductResourceTest extends ApiTestCase
{
    // test create product
    public function testCreateProduct()
    {
        $client = self::createClient();
        $client->request('POST', '/api/products', [], [], [], [

        ]);
        $this->assertResponseStatusCodeSame(201);
    }
}

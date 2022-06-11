<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use App\DataFixtures\TestFixtures;

class AddTagsTest extends ApiTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testAddTagsNoTag()
    {
        $this->databaseTool->loadFixtures([
            TestFixtures::class
        ]);

        $response = static::createClient()->request('POST', '/order/1/addTags', ['json' => []]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Order',
            '@id' => '/orders/1',
            'tags' => '',
        ]);
    }

    public function testAddTagsHeavyTag()
    {

        $response = static::createClient()->request('POST', '/order/2/addTags', ['json' => []]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Order',
            '@id' => '/orders/2',
            'tags' => 'heavy',
        ]);
    }

    public function testAddTagsForeignTag()
    {

        $response = static::createClient()->request('POST', '/order/3/addTags', ['json' => []]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Order',
            '@id' => '/orders/3',
            'tags' => 'foreignWarehouse',
        ]);
    }

    public function testAddTagsHasIssuesWeightTag()
    {

        $response = static::createClient()->request('POST', '/order/4/addTags', ['json' => []]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Order',
            '@id' => '/orders/4',
            'tags' => 'heavy,hasIssues',
        ]);
    }

    public function testAddTagsHasIssuesAddressTag()
    {
        $response = static::createClient()->request('POST', '/order/5/addTags', ['json' => []]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Order',
            '@id' => '/orders/5',
            'tags' => 'hasIssues',
        ]);
    }

    public function testAddTagsHasIssuesEmailTag()
    {

        $response = static::createClient()->request('POST', '/order/6/addTags', ['json' => []]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Order',
            '@id' => '/orders/6',
            'tags' => 'hasIssues',
        ]);
    }
}

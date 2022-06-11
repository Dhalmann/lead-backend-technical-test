<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\PHPUnitUtil;
use App\Manager\TagsManager;
use App\Entity\OrderLine;
use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;


class TagsManagerTest extends ApiTestCase 
{
    public function testcheckWeightNoProduct(): void
    {
        self::bootKernel();
        $lines = new ArrayCollection();

        $container = static::getContainer();
        $tagsManager = $container->get(TagsManager::class);


        $weight = PHPUnitUtil::callMethod(
            $tagsManager,
            'checkWeight', 
            array($lines)
        );

        $this->assertEquals(0, $weight);
    }

    public function oneLineProvider(): array
    {
        return [
            [50, 1, 50],
            [20, 5, 100],
            [3, 10, 30],
            [0, 50, 0]
        ];
    }

    /**
     * @dataProvider oneLineProvider
     * @depends testcheckWeightNoProduct
     */
    public function testcheckWeightOneLine($productWeight, $productQuantity, $expectedWeight): void
    {
        self::bootKernel();
        $lines = new ArrayCollection();

        $product = new Product();
        $product->setName('product_'.random_int(100, 999));
        $product->setWeight($productWeight);

        $orderLine = new OrderLine();
        $orderLine->setQuantity($productQuantity);
        $orderLine->setTotal(50);
        $orderLine->setProduct($product);

        $lines->add($orderLine);

        $container = static::getContainer();
        $tagsManager = $container->get(TagsManager::class);


        $weight = PHPUnitUtil::callMethod(
            $tagsManager,
            'checkWeight', 
            array($lines)
        );

        $this->assertEquals($expectedWeight, $weight);
    }

    /**
     * @dataProvider manyLinesProvider
     * @depends testcheckWeightOneLine
     */
    public function testcheckWeightManyLines($productWeight, $productQuantity, $expectedWeight): void
    {
        self::bootKernel();
        $lines = new ArrayCollection();

        $container = static::getContainer();
        $tagsManager = $container->get(TagsManager::class);

        for($i=0; $i<10;$i++) {
            $product = new Product();
            $product->setName('product_'.random_int(100, 999));
            $product->setWeight($productWeight);

            $orderLine = new OrderLine();
            $orderLine->setQuantity($productQuantity);
            $orderLine->setTotal(50);
            $orderLine->setProduct($product);

            $lines->add($orderLine);
        }


        $weight = PHPUnitUtil::callMethod(
            $tagsManager,
            'checkWeight', 
            array($lines)
        );

        $this->assertEquals($expectedWeight, $weight);
    }

    public function ManyLinesProvider(): array
    {
        return [
            [50, 1, 500],
            [20, 5, 1000],
            [3, 10, 300],
            [0, 50, 0]
        ];
    }
}
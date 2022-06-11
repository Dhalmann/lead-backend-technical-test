<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        // NO Tags
        $datas = array(
                    array( //No tags
                        'email' => $this->faker->email(),
                        'zipcode' => '06110',
                        'country' => 'France',
                        'quantity' => 1,
                        'numberOfLine' => 1,
                        'weight' => 10
                    ),
                    array( //Heavy tag
                        'email' => $this->faker->email(),
                        'zipcode' => '06110',
                        'country' => 'France',
                        'quantity' => 5,
                        'numberOfLine' => 1,
                        'weight' => 10
                    ),
                    array( //Foreign tag
                        'email' => $this->faker->email(),
                        'zipcode' => '06110',
                        'country' => 'Italy',
                        'quantity' => 1,
                        'numberOfLine' => 1,
                        'weight' => 10
                    ),
                    array( //hasIssue Weight tag
                        'email' => $this->faker->email(),
                        'zipcode' => '06110',
                        'country' => 'France',
                        'quantity' => 1,
                        'numberOfLine' => 10,
                        'weight' => 10
                    ),
                    array( //hasIssue address tag
                        'email' => $this->faker->email(),
                        'zipcode' => '69000',
                        'country' => 'France',
                        'quantity' => 1,
                        'numberOfLine' => 1,
                        'weight' => 10
                    ),
                    array( //hasIssue email tag
                        'email' => '',
                        'zipcode' => '06110',
                        'country' => 'France',
                        'quantity' => 1,
                        'numberOfLine' => 1,
                        'weight' => 10
                    )
                );

        foreach($datas as $dataOrder)
        {
            $order = new Order();
            $order->setContactEmail($dataOrder['email']);
            $order->setName('#'. mt_rand(10, 100000));
            $order->setShippingAddress('8 rue de la paix');
            $order->setShippingZipcode($dataOrder['zipcode']);
            $order->setShippingCountry($dataOrder['country']);

            
            for ($i=1; $i<=$dataOrder['numberOfLine']; $i++) {
                $qty = $dataOrder['quantity'];
                $price = mt_rand(100, 5000);
                $orderLine = new OrderLine();
                $orderLine->setQuantity($qty);
                $orderLine->setTotal($qty * $price);
        
                $product = new Product();
                $product->setName($this->faker->safeColorName() . ' nuts');
                $product->setWeight($dataOrder['weight']);
                $manager->persist($product);
        
                $orderLine->setProduct($product);
                $orderLine->setOrder($order);
        
                $manager->persist($product);
                $manager->persist($orderLine);
            }
            $manager->persist($order);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['phpunit'];
    }

    protected function getEnvironments(): array
    {
        return array('test');
    }

}

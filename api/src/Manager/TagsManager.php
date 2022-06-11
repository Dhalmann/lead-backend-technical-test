<?php

namespace App\Manager;

use App\Services\AddressService\AddressService;
use Doctrine\Common\Collections\Collection;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Product;

class TagsManager
{
    public const HEAVY = 'heavy';
    public const HAS_ISSUES = 'hasIssues';
    public const FOREIGN_WAREHOUSE = 'foreignWarehouse';

    private $addressService;

    public function __construct(AddressService $addressService) {
        $this->addressService = $addressService;
    }

    public function addTags(Order $order): void
    {
        $tags = array();

        //check the weight
        $weight = $this->checkWeight($order->getLines());
        if($weight > 40 ) $tags[] = self::HEAVY;
        if($weight > 60 ) $tags[] = self::HAS_ISSUES;
        
        //check shipping address
        if (strtolower($order->getShippingCountry()) == "france") {
            if(!$this->addressService->isAddressValid($order->getShippingAddress(), $order->getShippingZipcode())) {
                $tags[] = self::HAS_ISSUES;
            }
        } else {
            $tags[] = self::FOREIGN_WAREHOUSE;
        }

        //check email address
        if(empty($order->getContactEmail())) $tags[] = self::HAS_ISSUES;

        
        //remove the duplicate tags
        $tags = array_unique($tags); 
           
        $order->setTags(implode(',', $tags));
    }

    private function checkWeight(Collection $orderLines): int
    {
        $weight = 0;

        foreach($orderLines as $line){
            $productWeight = $line->getProduct()->getWeight();
            $weight +=  $productWeight * $line->getQuantity();
        }

        return $weight;
    }
}
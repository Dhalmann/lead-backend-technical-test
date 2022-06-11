<?php

namespace App\Services\AddressService;


interface AddressServiceInterface
{
    public function isAddressValid(string $address, string $postcode):bool;
}
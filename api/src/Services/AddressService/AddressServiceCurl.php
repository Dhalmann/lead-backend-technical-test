<?php

namespace App\Services\AddressService;

use App\Services\AddressService\AddressServiceInterface;

class AddressServiceCurl implements AddressServiceInterface
{
    private $url;
    private $validScore;

    public function __construct($apiAdresseConfig)
    {
        $this->url = $apiAdresseConfig['url'];
        $this->validScore = $apiAdresseConfig['valid_score'];
    }
    
    public function isAddressValid(string $address, string $postcode):bool
    {
        $searchParam  = urlencode($address);
        $searchParam .= '&postcode=';
        $searchParam .= urlencode($postcode);

        $ch = curl_init($this->url.$searchParam);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        try {
            // Send the request.
            $response = curl_exec($ch);
        }
        catch(\Exception $e) {
            //die($e->getMessage());
            return false;
        }
        

        // Check for errors
        if($response === false){
            //die(curl_error($ch));
            return false;
        }

        // Decode the response
        $responseData = json_decode($response, true);
        //I only check the fist one as it seems that it has the higher score. 
        $score = $responseData['features'][0]['properties']['score']??0;

        // Close the cURL handler
        curl_close($ch);
        
        return $score > $this->validScore;
    }
}
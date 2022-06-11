<?php

namespace App\Services\AddressService;

use App\Services\AddressService\AddressServiceInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddressService implements AddressServiceInterface
{
    private $client;
    private $url;
    private $validScore;

    public function __construct(HttpClientInterface $client, $apiAdresseConfig)
    {
        $this->client = $client;
        $this->url = $apiAdresseConfig['url'];
        $this->validScore = $apiAdresseConfig['valid_score'];
    }
    
    public function isAddressValid(string $address, string $postcode):bool
    {
        $searchParam  = urlencode($address);
        $searchParam .= '&postcode=';
        $searchParam .= urlencode($postcode);

        $response = $this->client->request(
            'GET',
            $this->url.$searchParam
        );

        if (200 != $response->getStatusCode()) {
            throw new Exception('Error');
        }
        
        $content = $response->toArray();
        $score = $content['features'][0]['properties']['score']??0;

        return $score > $this->validScore;
    }
}
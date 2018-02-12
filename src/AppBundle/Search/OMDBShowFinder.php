<?php


namespace AppBundle\Search;


use GuzzleHttp\Client;

class OMDBShowFinder implements ShowFinderInterface
{
    private $client;
    private $APIKey;

    public function __construct(Client $client, string $APIKey)
    {
        $this->client = $client;
        $this->APIKey = $APIKey;
    }

    public function getName(): string
    {
        return 'OMDB API';
    }

    public function findByName(string $query): array
    {
        $q = sprintf('/?apikey=%s&t=%s', $this->APIKey, $query);
        $results = $this->client->get($q);

        return \GuzzleHttp\json_decode($results->getBody());
    }
}
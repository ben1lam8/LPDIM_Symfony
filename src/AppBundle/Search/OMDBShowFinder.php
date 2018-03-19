<?php


namespace AppBundle\Search;

use AppBundle\Entity\Category;
use AppBundle\Entity\Show;
use DateTime;
use GuzzleHttp\Client;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class OMDBShowFinder implements ShowFinderInterface
{
    private $client;
    private $APIKey;
    private $tokenStorage;

    public function __construct(Client $client, string $APIKey, TokenStorage $tokenStorage)
    {
        $this->client = $client;
        $this->APIKey = $APIKey;
        $this->tokenStorage = $tokenStorage;
    }

    public function getName(): string
    {
        return Show::DATA_SOURCE_OMDB;
    }

    public function findByName(string $query): array
    {
        $q = sprintf('/?apikey=%s&t=%s', $this->APIKey, $query);
        $results = $this->client->get($q)->getBody();

        $json = \GuzzleHttp\json_decode($results, true);

        if (isset($json['Error'])) {
            $shows = [];
        } else {
            $shows = $this->deserializeShows($json);
        }

        return $shows;
    }

    private function deserializeShows(array $json): array
    {
        //TODO: manage multiple results

        $show = new Show();

        $date = new DateTime($json['Released']);

        $category = new Category();
        $category->setName($json['Genre']);

        $show
            ->setName($json['Title'])
            ->setDataSource(Show::DATA_SOURCE_OMDB)
            ->setAbstract($json['Plot'])
            ->setCountry($json['Country'])
            ->setAuthor($this->tokenStorage->getToken()->getUser()) // ... just for fun
            ->setReleaseDate($date)
            ->setMainPicture($json['Poster']);

        return [$show];
    }
}

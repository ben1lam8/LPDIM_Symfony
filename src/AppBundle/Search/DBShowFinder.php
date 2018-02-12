<?php


namespace AppBundle\Search;


use Symfony\Bridge\Doctrine\RegistryInterface;

class DBShowFinder implements ShowFinderInterface
{
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getName(): string
    {
        return 'Local database';
    }

    public function findByName(string $query): array
    {
        return $this->doctrine->getRepository('AppBundle:Show')->findAllByQuery($query);
    }
}
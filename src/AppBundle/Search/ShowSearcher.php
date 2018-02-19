<?php


namespace AppBundle\Search;

class ShowSearcher
{
    private $finders;

    public function searchByName($query): array
    {
        $results = [];

        foreach ($this->finders as $finder) {
            $results = array_merge($results, $finder->findByName($query));
        }

        return $results;
    }

    public function addFinder(ShowFinderInterface $finder): void
    {
        $this->finders[] = $finder;
    }
}

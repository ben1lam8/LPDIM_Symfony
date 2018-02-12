<?php


namespace AppBundle\Search;


interface ShowFinderInterface
{
    /**
     * @return string finder implementation name to be displayed
     */
    public function getName(): string;

    /**
     * @param string $query the query
     * @return mixed array of found objects
     */
    public function findByName(string $query): array;
}
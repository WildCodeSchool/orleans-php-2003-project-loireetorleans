<?php


namespace App\Service;

use Doctrine\ORM\EntityRepository;

class Search extends EntityRepository
{
    public function findBySearch(string $search, array $properties, string $alias)
    {
        $count = count($properties);
        $qb = '
        \'SELECT ' . $alias . ' FROM App\\Entity\\' . $alias . ' ' . $alias;

        for ($i = 0; $i < $count; $i++) {
            if ($i === 0) {
                $qb .= ' WHERE ' . $alias . '.' . $properties[$i] . ' LIKE \'%' . $search . '%\'';
            }
            $qb .= ' OR ' . $alias . '.' . $properties[$i] . ' LIKE \'%' . $search . '%\'';
        }

        $query = $this->getEntityManager()->createQuery($qb);
        return $query->execute();
    }
}

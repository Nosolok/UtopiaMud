<?php

namespace Rottenwood\UtopiaMudBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RaceRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RaceRepository extends EntityRepository {

    public function findByAnchor($anchor) {

        $query = $this->getEntityManager()
            ->createQuery('SELECT r FROM RottenwoodUtopiaMudBundle:Race r WHERE r.anchor LIKE :anchor');
        $query->setParameter('anchor', '%' . $anchor . '%');
        $query->setMaxResults(1);
        $result = $query->getResult();

        return $result;
    }
}

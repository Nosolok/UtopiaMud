<?php

namespace Rottenwood\UtopiaMudBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RoomRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoomRepository extends EntityRepository {

    public function findAllOrderedByName() {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM RottenwoodUtopiaMudBundle:Room r ORDER BY r.name ASC')
            ->getResult();
    }

    public function findCurrentRoom($id) {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM RottenwoodUtopiaMudBundle:Room r WHERE r.id = ' . $id)
            ->getResult();
    }
}

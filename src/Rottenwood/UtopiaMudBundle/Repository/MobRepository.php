<?php

namespace Rottenwood\UtopiaMudBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MobRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MobRepository extends EntityRepository {

    /**
     * Поиск моба по якорю
     * @param $anchor
     * @param $zone
     * @return array
     */
    public function findByAnchor($anchor, $zone) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT m FROM RottenwoodUtopiaMudBundle:Mob m WHERE m.name LIKE :anchor AND m.zone
            LIKE :zone');
        $query->setParameter('anchor', '%' . $anchor . '%');
        $query->setParameter('zone', '%' . $zone . '%');
        $query->setMaxResults(1);
        $result = $query->getResult();

        return $result;
    }

    public function findMobsFromListInZone($mobList, $zone) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT m FROM RottenwoodUtopiaMudBundle:Mob m WHERE m.name IN (:moblist) AND m.zone
            LIKE :zone');
        $query->setParameter('moblist', $mobList);
        $query->setParameter('zone', '%' . $zone . '%');
        $query->setMaxResults(1);
        $result = $query->getResult();

        return $result;
    }
}

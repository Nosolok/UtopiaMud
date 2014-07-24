<?php

namespace Rottenwood\UtopiaMudBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PlayerRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlayerRepository extends EntityRepository {

    /**
     * Сохраняет токен сессии в объект Player
     * @param $id
     * @param $hash
     * @return mixed
     */
    public function saveHash($id, $hash) {
        $querybuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $querybuilder->update('RottenwoodUtopiaMudBundle:Player', 'p')
            ->set('p.hash', '?1')
            ->where('p.id = ?2')
            ->setParameter(1, $hash)
            ->setParameter(2, $id)
            ->getQuery();
        $result = $query->execute();

        return $result;
    }

    /**
     * Возвращает игрока по последнему токену его сессии
     * @param $hash
     * @return int
     */
    public function getByHash($hash) {
        $query = $this->getEntityManager()->createQuery('SELECT p FROM RottenwoodUtopiaMudBundle:Player p WHERE p
        .hash LIKE :hash');
        $query->setParameter('hash', '%' . $hash . '%');
        $result = $query->getResult();

        return $result;
    }

    public function findPlayersOnline($playersOnline) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM RottenwoodUtopiaMudBundle:Player p WHERE p.id IN (:players)');
        $query->setParameter('players', $playersOnline);

        $result = $query->getResult();

        return $result;
    }

    /**
     * @param integer $raceId
     */
    public function findByRace($raceId) {
        $query = $this->getEntityManager()->createQuery('SELECT p FROM RottenwoodUtopiaMudBundle:Player p WHERE p.race = ?1');
        $query->setParameter(1, $raceId);
        $result = $query->getResult();

        return $result;
    }
}

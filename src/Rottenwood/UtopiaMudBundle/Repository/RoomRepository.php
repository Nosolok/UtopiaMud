<?php

namespace Rottenwood\UtopiaMudBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RoomRepository
 */
class RoomRepository extends EntityRepository {

    /**
     * Поиск комнаты по id
     * @param $id
     * @return array
     */
    public function findCurrentRoom($id) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT r FROM RottenwoodUtopiaMudBundle:Room r WHERE r.id LIKE :id');
        $query->setParameter('id', '%' . $id . '%');
        $result = $query->getResult();

        return $result;
    }

    /**
     * Поиск комнаты по якорю
     * @param $anchor
     * @param $zone
     * @return array
     */
    public function findByAnchor($anchor, $zone) {

        $query = $this->getEntityManager()
            ->createQuery('SELECT r FROM RottenwoodUtopiaMudBundle:Room r WHERE r.anchor LIKE :anchor AND r.zone
            LIKE :zone');
        $query->setParameter('anchor', '%' . $anchor . '%');
        $query->setParameter('zone', '%' . $zone . '%');
        $query->setMaxResults(1);
        $result = $query->getResult();

        return $result;
    }

    /**
     * Поиск всех персонажей в комнате
     * @param $roomId
     * @param $playersOnline
     * @return array
     */
    public function findPlayersInRoom($roomId, $playersOnline) {
        $query = $this->getEntityManager()
            ->createQuery("SELECT p FROM RottenwoodUtopiaMudBundle:Player p LEFT JOIN p.room r WHERE r.id = ?1
            AND p.id IN (:players)");
        $query->setParameter(1, $roomId);
        $query->setParameter('players', $playersOnline);
        $result = $query->getResult();

        return $result;
    }

    /**
     * Поиск всех комнат в зоне
     * @param $zone
     * @internal param $anchor
     * @return array
     */
    public function findByZone($zone) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT r FROM RottenwoodUtopiaMudBundle:Room r WHERE r.zone LIKE :zone');
        $query->setParameter('zone', '%' . $zone . '%');
        $result = $query->getResult();

        return $result;
    }

    // ID всех комнат в зоне
    public function findAllRoomIdsInZone($zone) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT r.id FROM RottenwoodUtopiaMudBundle:Room r WHERE r.zone LIKE :zone');
        $query->setParameter('zone', '%' . $zone . '%');
        $result = $query->getResult();

        // форматирование результата в один массив
        $zoneIds = array();
        foreach ($result as $roomId) {
            $zoneIds[] = $roomId["id"];
        }

        return $zoneIds;
    }

    public function findPlayersInZone($zone, $playersOnline) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM RottenwoodUtopiaMudBundle:Player p LEFT JOIN p.room r WHERE r.zone = ?1
            AND p.id IN (:players)');
        $query->setParameter(1, $zone);
        $query->setParameter('players', $playersOnline);

        $result = $query->getResult();

        return $result;
    }
}

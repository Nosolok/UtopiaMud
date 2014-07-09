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
     * @return array
     */
    public function findByAnchor($anchor) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT r FROM RottenwoodUtopiaMudBundle:Room r WHERE r.anchor LIKE :anchor');
        $query->setParameter('anchor', '%' . $anchor . '%');
        $result = $query->getResult();

        return $result;
    }

    /**
     * Поиск всех персонажей в комнате
     * @param $room
     * @param $playersOnline
     * @return array
     */
    public function findPlayersInRoom($room, $playersOnline) {
        $query = $this->getEntityManager()
            ->createQuery("SELECT p FROM RottenwoodUtopiaMudBundle:Player p LEFT JOIN p.room r WITH r.id = ?1
            WHERE p.id IN ( ?2 )");
        $query->setParameter(1, $room);
        $query->setParameter(2, $playersOnline);
        $result = $query->getResult();

        return $result;
    }
}

<?php

namespace Rottenwood\UtopiaMudBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Rottenwood\UtopiaMudBundle\Entity\Room;

/**
 * RoomitemRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoomitemRepository extends EntityRepository {

    /**
     * Удаление всех записей из таблицы, сброс инкремента, truncate
     */
    public function deleteAll() {
        $tableName = $this->getClassMetadata()->getTableName();
        $connection = $this->getEntityManager()->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($tableName);
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
        }
    }

    /**
     * Поиск всех предметов в комнате
     * @param Room $room
     * @return array
     */
    public function findItemsInRoom($room) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT ri FROM RottenwoodUtopiaMudBundle:Roomitem ri WHERE ri.room = :room');
        $query->setParameter('room', $room);
        $result = $query->getResult();

        return $result;
    }
}
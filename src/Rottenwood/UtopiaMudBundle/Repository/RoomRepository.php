<?php

namespace Rottenwood\UtopiaMudBundle\Repository;

use Doctrine\DBAL\Connection;
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

    /**
     * ID всех комнат в зоне
     * @param $zone
     * @return array
     */
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

    /**
     * Поиск игроков онлайн в зоне
     * @param $zone
     * @param $playersOnline
     * @return array
     */
    public function findPlayersInZone($zone, $playersOnline) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM RottenwoodUtopiaMudBundle:Player p LEFT JOIN p.room r WHERE r.zone = ?1
            AND p.id IN (:players)');
        $query->setParameter(1, $zone);
        $query->setParameter('players', $playersOnline);

        $result = $query->getResult();

        return $result;
    }

    /**
     * Возвращает типы комнат в квадрате 5х5 (25 клеток) с центром на указанной Room
     * @param string $roomAnchor
     * @param string $zone
     * @param Connection $con
     * @return array
     */
    public function getTypesForMap($roomAnchor, $zone, Connection $con) {
        $sqlStatement = '
        SELECT
            r.type as r,
            s.type as s,
            n.type as n,
            e.type as e,
            w.type as w,
            nn.type as nn,
            ne.type as ne,
            nw.type as nw,
            ss.type as ss,
            se.type as se,
            sw.type as sw,
            ee.type as ee,
            ww.type as ww,
            nen.type as nen,
            nee.type as nee,
            nwn.type as nwn,
            nww.type as nww,
            ses.type as ses,
            see.type as see,
            sws.type as sws,
            sww.type as sww,
            neen.type as neen,
            nwwn.type as nwwn,
            sees.type as sees,
            swws.type as swws,

            wn.type as wn,
            ws.type as ws,
            en.type as en,
            es.type as es,
            nnw.type as nnw,
            nne.type as nne,
            ssw.type as ssw,
            sse.type as sse,
            wwn.type as wwn,
            wws.type as wws,
            een.type as een,
            ees.type as ees,
            wnn.type as wnn,
            wss.type as wss,
            enn.type as enn,
            ess.type as ess,
            ene.type as ene,
            ese.type as ese,
            wnw.type as wnw,
            wsw.type as wsw

        FROM
            rooms r
        LEFT JOIN rooms s ON r.south = s.anchor AND r.zone = s.zone
        LEFT JOIN rooms n ON r.north = n.anchor AND r.zone = n.zone
        LEFT JOIN rooms e ON r.east = e.anchor AND r.zone = e.zone
        LEFT JOIN rooms w ON r.west = w.anchor AND r.zone = w.zone
        LEFT JOIN rooms nn ON n.north = nn.anchor AND r.zone = nn.zone
        LEFT JOIN rooms ne ON n.east = ne.anchor AND r.zone = ne.zone
        LEFT JOIN rooms nw ON n.west = nw.anchor AND r.zone = nw.zone
        LEFT JOIN rooms ss ON s.south = ss.anchor AND r.zone = ss.zone
        LEFT JOIN rooms se ON s.east = se.anchor AND r.zone = se.zone
        LEFT JOIN rooms sw ON s.west = sw.anchor AND r.zone = sw.zone
        LEFT JOIN rooms ee ON e.east = ee.anchor AND r.zone = ee.zone
        LEFT JOIN rooms ww ON w.west = ww.anchor AND r.zone = ww.zone
        LEFT JOIN rooms nen ON ne.north = nen.anchor AND r.zone = nen.zone
        LEFT JOIN rooms nee ON ne.east = nee.anchor AND r.zone = nee.zone
        LEFT JOIN rooms nwn ON nw.north = nwn.anchor AND r.zone = nwn.zone
        LEFT JOIN rooms nww ON nw.west = nww.anchor AND r.zone = nww.zone
        LEFT JOIN rooms ses ON se.south = ses.anchor AND r.zone = ses.zone
        LEFT JOIN rooms see ON se.east = see.anchor AND r.zone = see.zone
        LEFT JOIN rooms sws ON sw.south = sws.anchor AND r.zone = sws.zone
        LEFT JOIN rooms sww ON sw.west = sww.anchor AND r.zone = sww.zone
        LEFT JOIN rooms neen ON nee.north = neen.anchor AND r.zone = neen.zone
        LEFT JOIN rooms nwwn ON nww.north = nwwn.anchor AND r.zone = nwwn.zone
        LEFT JOIN rooms sees ON see.south = sees.anchor AND r.zone = sees.zone
        LEFT JOIN rooms swws ON sww.south = swws.anchor AND r.zone = swws.zone

        LEFT JOIN rooms wn ON w.north = wn.anchor AND r.zone = wn.zone
        LEFT JOIN rooms ws ON w.south = ws.anchor AND r.zone = ws.zone
        LEFT JOIN rooms en ON e.north = en.anchor AND r.zone = en.zone
        LEFT JOIN rooms es ON e.south = es.anchor AND r.zone = es.zone
        LEFT JOIN rooms nnw ON nn.west = nnw.anchor AND r.zone = nnw.zone
        LEFT JOIN rooms nne ON nn.east = nne.anchor AND r.zone = nne.zone
        LEFT JOIN rooms ssw ON ss.west = ssw.anchor AND r.zone = ssw.zone
        LEFT JOIN rooms sse ON ss.east = sse.anchor AND r.zone = sse.zone
        LEFT JOIN rooms wwn ON ww.north = wwn.anchor AND r.zone = wwn.zone
        LEFT JOIN rooms wws ON ww.south = wws.anchor AND r.zone = wws.zone
        LEFT JOIN rooms een ON ee.north = een.anchor AND r.zone = een.zone
        LEFT JOIN rooms ees ON ee.south = ees.anchor AND r.zone = ees.zone
        LEFT JOIN rooms wnn ON wn.north = wnn.anchor AND r.zone = wnn.zone
        LEFT JOIN rooms enn ON en.north = enn.anchor AND r.zone = enn.zone
        LEFT JOIN rooms wss ON ws.south = wss.anchor AND r.zone = wss.zone
        LEFT JOIN rooms ess ON es.south = ess.anchor AND r.zone = ess.zone

        LEFT JOIN rooms ene ON en.east = ene.anchor AND r.zone = ene.zone
        LEFT JOIN rooms ese ON es.east = ese.anchor AND r.zone = ese.zone
        LEFT JOIN rooms wnw ON wn.west = wnw.anchor AND r.zone = wnw.zone
        LEFT JOIN rooms wsw ON ws.west = wsw.anchor AND r.zone = wsw.zone

        WHERE r.anchor = ? AND r.zone = ?';
        $sql = $con->executeQuery($sqlStatement, array($roomAnchor, $zone));
        $result = $sql->fetchAll();

        return $result[0];
    }
}

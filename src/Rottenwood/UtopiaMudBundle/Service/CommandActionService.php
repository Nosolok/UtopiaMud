<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Rottenwood\UtopiaMudBundle\Entity\Player;
use Rottenwood\UtopiaMudBundle\Entity\Room;
use Symfony\Component\DependencyInjection\Container;
use Rottenwood\UtopiaMudBundle\Repository;

/**
 * Service of action commands
 * Сервис команд действия
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class CommandActionService {

    protected $em;
    protected $kernel;
    protected $container;
    protected $user;
    protected $id;
    protected $roomRepository;

    public function __construct(Container $container, EntityManager $em) {
        $this->container = $container;
        $this->em = $em;
        /** @var Repository\RoomRepository $roomRepository */
        $this->roomRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room');
    }

    /**
     * Техническая функция осмотра комнаты
     * @param \Rottenwood\UtopiaMudBundle\Entity\Room $room
     * @param                                         $charId
     * @internal param \Rottenwood\UtopiaMudBundle\Entity\Player $char
     * @return mixed
     */
    public function techLook($room, $charId) {
        $roomId = $room->getId();
        $result = array();
        // осмотр комнаты
        $result["roomname"] = $room->getName();
        $result["roomdesc"] = $room->getRoomdesc();

        if($exits = $this->techLookExits($room)) {
            $result["exits"] = $exits;
        }

        // персонажи
        $playersOnline = $this->container->get('datachannel')->getOnlineIds($charId);
        $playersInRoom = $this->roomRepository->findPlayersInRoom($roomId, $playersOnline);

        if ($playersInRoom) {
            foreach ($playersInRoom as $player) {
                $playerSex = $player->getSex();
                if ($playerSex == 2) {
                    $getNameFunction = "getNamef";
                } else {
                    $getNameFunction = "getName";
                }
                $result["players"][$player->getUsername()]["race"] = $player->getRace()->{$getNameFunction}();
            }
        }

        return $result;
    }

    /**
     * Сбор и отображение выходов из комнаты
     * @param \Rottenwood\UtopiaMudBundle\Entity\Room $room
     * @return array
     */
    public function techLookExits($room) {
        $result = array();
        if ($room->getNorth()) {
            $result["n"] = 1;
        }
        if ($room->getSouth()) {
            $result["s"] = 1;
        }
        if ($room->getEast()) {
            $result["e"] = 1;
        }
        if ($room->getWest()) {
            $result["w"] = 1;
        }
        if ($room->getUp()) {
            $result["u"] = 1;
        }
        if ($room->getDown()) {
            $result["d"] = 1;
        }

        return $result;
    }

    /**
     * Техническая функция перемещения персонажа
     * персонаж, комната назначения
     * @param Player                                  $char
     * @param \Rottenwood\UtopiaMudBundle\Entity\Room $room
     * @return bool
     */
    public function techGotoRoom($char, $room) {
        $char->setRoom($room);
        $this->em->persist($char);
        $this->em->flush();

        return true;
    }

    /**
     * Command: look
     * @param Player $char
     * @return array
     */
    public function look(Player $char) {
        // получение описания комнаты в которой находится персонаж
        $room = $char->getRoom();

        $result = $this->techLook($room, 0);
        $result["message"] = "1:1"; // вы осмотрелись

        return $result;
    }

    /**
     * Command: north
     * @param Player $char
     * @return array
     */
    public function north(Player $char) {
        $room = $char->getRoom();
        /** @var Room $room */
        $destinationRoomAnchor = $room->getNorth();
        if (strpos($destinationRoomAnchor,':')) {
            // если якорь содержит ссылку на другую зону
            list($destinationRoomAnchor, $zone) = explode(":", $destinationRoomAnchor);
        } else {
            $zone = $room->getZone();
        }

        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        /** @method Repository\RoomRepository findByAnchor() */
        $destinationRoom = $this->roomRepository->findByAnchor($destinationRoomAnchor, $zone);
        $this->techGotoRoom($char, $destinationRoom[0]);
        $result = $this->techLook($destinationRoom[0], $char->getId());

        return $result;
    }

    /**
     * Command: south
     * @param Player $char
     * @return array
     */
    public function south(Player $char) {
        $room = $char->getRoom();
        /** @var Room $room */
        $destinationRoomAnchor = $room->getSouth();
        if (strpos($destinationRoomAnchor,':')) {
            // если якорь содержит ссылку на другую зону
            list($destinationRoomAnchor, $zone) = explode(":", $destinationRoomAnchor);
        } else {
            $zone = $room->getZone();
        }
        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        /** @method Repository\RoomRepository findByAnchor() */
        $destinationRoom = $this->roomRepository->findByAnchor($destinationRoomAnchor, $zone);
        $this->techGotoRoom($char, $destinationRoom[0]);
        $result = $this->techLook($destinationRoom[0], $char->getId());

        return $result;
    }

    /**
     * Command: east
     * @param Player $char
     * @return array
     */
    public function east(Player $char) {
        $room = $char->getRoom();
        /** @var Room $room */
        $destinationRoomAnchor = $room->getEast();
        if (strpos($destinationRoomAnchor,':')) {
            // если якорь содержит ссылку на другую зону
            list($destinationRoomAnchor, $zone) = explode(":", $destinationRoomAnchor);
        } else {
            $zone = $room->getZone();
        }
        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        /** @method Repository\RoomRepository findByAnchor() */
        $destinationRoom = $this->roomRepository->findByAnchor($destinationRoomAnchor, $zone);
        $this->techGotoRoom($char, $destinationRoom[0]);
        $result = $this->techLook($destinationRoom[0], $char->getId());

        return $result;
    }

    /**
     * Command: west
     * @param Player $char
     * @return array
     */
    public function west(Player $char) {
        $room = $char->getRoom();
        /** @var Room $room */
        $destinationRoomAnchor = $room->getWest();
        if (strpos($destinationRoomAnchor,':')) {
            // если якорь содержит ссылку на другую зону
            list($destinationRoomAnchor, $zone) = explode(":", $destinationRoomAnchor);
        } else {
            $zone = $room->getZone();
        }

        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        /** @method Repository\RoomRepository findByAnchor() */
        $destinationRoom = $this->roomRepository->findByAnchor($destinationRoomAnchor, $zone);
        $this->techGotoRoom($char, $destinationRoom[0]);
        $result = $this->techLook($destinationRoom[0], $char->getId());

        return $result;
    }

    /**
     * Command: up
     * @param Player $char
     * @return array
     */
    public function up(Player $char) {
        $room = $char->getRoom();
        /** @var Room $room */
        $destinationRoomAnchor = $room->getUp();
        if (strpos($destinationRoomAnchor,':')) {
            // если якорь содержит ссылку на другую зону
            list($destinationRoomAnchor, $zone) = explode(":", $destinationRoomAnchor);
        } else {
            $zone = $room->getZone();
        }
        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        /** @method Repository\RoomRepository findByAnchor() */
        $destinationRoom = $this->roomRepository->findByAnchor($destinationRoomAnchor, $zone);
        $this->techGotoRoom($char, $destinationRoom[0]);
        $result = $this->techLook($destinationRoom[0], $char->getId());

        return $result;
    }

    /**
     * Command: down
     * @param Player $char
     * @return array
     */
    public function down(Player $char) {
        $room = $char->getRoom();
        /** @var Room $room */
        $destinationRoomAnchor = $room->getDown();
        if (strpos($destinationRoomAnchor,':')) {
            // если якорь содержит ссылку на другую зону
            list($destinationRoomAnchor, $zone) = explode(":", $destinationRoomAnchor);
        } else {
            $zone = $room->getZone();
        }
        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        /** @method Repository\RoomRepository findByAnchor() */
        $destinationRoom = $this->roomRepository->findByAnchor($destinationRoomAnchor, $zone);
        $this->techGotoRoom($char, $destinationRoom[0]);
        $result = $this->techLook($destinationRoom[0], $char->getId());

        return $result;
    }
}
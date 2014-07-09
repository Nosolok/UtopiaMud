<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Rottenwood\UtopiaMudBundle\Entity\Player;
use Symfony\Component\DependencyInjection\Container;

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

    public function __construct(Container $container, EntityManager $em) {
        $this->container = $container;
        $this->em = $em;
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
        // осмотр комнаты
        $result["roomname"] = $room->getName();
        $result["roomdesc"] = $room->getRoomdesc();
        // выходы
        if ($room->getNorth()) {
            $result["exits"]["n"] = 1;
        }
        if ($room->getSouth()) {
            $result["exits"]["s"] = 1;
        }
        if ($room->getEast()) {
            $result["exits"]["e"] = 1;
        }
        if ($room->getWest()) {
            $result["exits"]["w"] = 1;
        }
        if ($room->getUp()) {
            $result["exits"]["u"] = 1;
        }
        if ($room->getDown()) {
            $result["exits"]["d"] = 1;
        }

        // персонажи
        $playersOnline = $this->container->get('datachannel')->getOnlineIds($charId);
        $rooms = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room');
        $playersInRoom = $rooms->findPlayersInRoom($roomId, $playersOnline);

        if ($playersInRoom) {
            foreach ($playersInRoom as $player) {
                $playerSex = $player->getSex();
                if ($playerSex == 1) {
                    $result["players"][$player->getUsername()]["race"] = $player->getRace()->getName();

                } elseif ($playerSex == 2) {
                    $result["players"][$player->getUsername()]["race"] = $player->getRace()->getNamef();

                }
            }
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
        $destinationRoomAnchor = $room->getNorth();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor === "0") {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->findByAnchor($destinationRoomAnchor);
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
        $destinationRoomAnchor = $room->getSouth();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor === "0") {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->findByAnchor($destinationRoomAnchor);
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
        $destinationRoomAnchor = $room->getEast();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor === "0") {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->findByAnchor($destinationRoomAnchor);
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
        $destinationRoomAnchor = $room->getWest();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor === "0") {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->findByAnchor($destinationRoomAnchor);
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
        $destinationRoomAnchor = $room->getUp();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor === "0") {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->findByAnchor($destinationRoomAnchor);
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
        $destinationRoomAnchor = $room->getDown();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomAnchor === "0") {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->findByAnchor($destinationRoomAnchor);
        $this->techGotoRoom($char, $destinationRoom[0]);
        $result = $this->techLook($destinationRoom[0], $char->getId());

        return $result;
    }
}
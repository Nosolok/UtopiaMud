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
     * @return mixed
     */
    public function techLook($room) {
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



        return $result;
    }

    /**
     * Техническая функция перемещения персонажа
     * персонаж, комната назначения
     * @param Player $char
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

        $result = $this->techLook($room);
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
        $destinationRoomInt = $room->getNorth();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomInt == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->find($destinationRoomInt);
        $this->techGotoRoom($char, $destinationRoom);

        $result = $this->techLook($destinationRoom);

        return $result;
    }

    /**
     * Command: south
     * @param Player $char
     * @return array
     */
    public function south(Player $char) {
        $room = $char->getRoom();
        $destinationRoomInt = $room->getSouth();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomInt == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->find($destinationRoomInt);
        $this->techGotoRoom($char, $destinationRoom);

        $result = $this->techLook($destinationRoom);

        return $result;
    }

    /**
     * Command: east
     * @param Player $char
     * @return array
     */
    public function east(Player $char) {
        $room = $char->getRoom();
        $destinationRoomInt = $room->getEast();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomInt == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->find($destinationRoomInt);
        $this->techGotoRoom($char, $destinationRoom);

        $result = $this->techLook($destinationRoom);

        return $result;
    }

    /**
     * Command: west
     * @param Player $char
     * @return array
     */
    public function west(Player $char) {
        $room = $char->getRoom();
        $destinationRoomInt = $room->getWest();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomInt == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->find($destinationRoomInt);
        $this->techGotoRoom($char, $destinationRoom);

        $result = $this->techLook($destinationRoom);

        return $result;
    }

    /**
     * Command: up
     * @param Player $char
     * @return array
     */
    public function up(Player $char) {
        $room = $char->getRoom();
        $destinationRoomInt = $room->getUp();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomInt == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->find($destinationRoomInt);
        $this->techGotoRoom($char, $destinationRoom);

        $result = $this->techLook($destinationRoom);

        return $result;
    }

    /**
     * Command: down
     * @param Player $char
     * @return array
     */
    public function down(Player $char) {
        $room = $char->getRoom();
        $destinationRoomInt = $room->getDown();
        $result = array();

        // если выхода не найдено
        if ($destinationRoomInt == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        // перемещение в комнату назначения
        $destinationRoom = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->find($destinationRoomInt);
        $this->techGotoRoom($char, $destinationRoom);

        $result = $this->techLook($destinationRoom);

        return $result;
    }
}
<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Сервис команд действия
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class NewCommandActionService {

    protected $em;
    protected $kernel;
    protected $container;
    protected $user;
    protected $id;

//    public function __construct(ContainerInterface $container, EntityManager $em) {
//        $this->container = $container;
//        $this->user = $this->container->get('security.context')->getToken()->getUser();
//        $this->id = $this->user->getId();
//        $this->em = $em;
//    }

    public function techGetPlayerRoom() {
        $roomid = $this->user->getRoom();
        $room = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->find($roomid);

        return $room;
    }

    public function techGetRoom($id) {
        $room = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room')->find($id);

        return $room;
    }

    public function techCheckRoom($exit) {
        // если выхода в той стороне нет
        if ($exit == "null") {
            $result["message"] = "0:3"; // нет выхода

            return $result;
        } else {
            return false;
        }
    }

    // смотреть
    public function look() {
//        $room = $this->techGetPlayerRoom();

//        $roomname = $room->getName();
//        $roomdesc = $room->getRoomdesc();

        $result["message"] = "1:1"; // вы осмотрелись
        $result["roomnamelook"] = "test";
        $result["roomdesclook"] = "testdesc";
//        $result["roomnamelook"] = $roomname;
//        $result["roomdesclook"] = $roomdesc;
//        $result["exits"]["n"] = "open";
//        $result["exits"]["s"] = "closed";

        return $result;
    }

    // перемещение: север
    public function north() {
        $room = $this->techGetPlayerRoom();

        // комната назначения
        $roomwheretogo = $room->getNorth();

        // если выхода в той стороне нет
        if ($roomwheretogo == "null") {
            $result["message"] = "0:3"; // нет выхода

            return $result;
        }

        // запись информации о комнате назначения
        $roomnew = $this->techGetRoom($roomwheretogo);
        $this->user->setRoom($roomnew);
        $this->em->flush();

        // отображение текущей комнаты
        $result["roomname"] = $roomnew->getName();
        $result["roomdesc"] = $roomnew->getRoomdesc();

        return $result;
    }

    // перемещение: юг
    public function south() {
        $room = $this->techGetPlayerRoom();

        // комната назначения
        $roomwheretogo = $room->getSouth();

        // если выхода в той стороне нет
        if ($roomwheretogo == "null") {
            $result["message"] = "0:3"; // нет выхода

            return $result;
        }

        // запись информации о комнате назначения
        $roomnew = $this->techGetRoom($roomwheretogo);
        $this->user->setRoom($roomnew);
        $this->em->flush();

        // отображение текущей комнаты
        $result["roomname"] = $roomnew->getName();
        $result["roomdesc"] = $roomnew->getRoomdesc();

        return $result;
    }

    // перемещение: восток
    public function east() {
        $room = $this->techGetPlayerRoom();

        // комната назначения
        $roomwheretogo = $room->getEast();

        // если выхода в той стороне нет
        if ($roomwheretogo == "null") {
            $result["message"] = "0:3"; // нет выхода

            return $result;
        }

        // запись информации о комнате назначения
        $roomnew = $this->techGetRoom($roomwheretogo);
        $this->user->setRoom($roomnew);
        $this->em->flush();

        // отображение текущей комнаты
        $result["roomname"] = $roomnew->getName();
        $result["roomdesc"] = $roomnew->getRoomdesc();

        return $result;
    }

    // перемещение: запад
    public function west() {
        $room = $this->techGetPlayerRoom();

        // комната назначения
        $roomwheretogo = $room->getWest();

        // если выхода в той стороне нет
        if ($roomwheretogo == "null") {
            $result["message"] = "0:3"; // нет выхода

            return $result;
        }

        // запись информации о комнате назначения
        $roomnew = $this->techGetRoom($roomwheretogo);
        $this->user->setRoom($roomnew);
        $this->em->flush();

        // отображение текущей комнаты
        $result["roomname"] = $roomnew->getName();
        $result["roomdesc"] = $roomnew->getRoomdesc();

        return $result;
    }

    // перемещение: вверх
    public function up() {
        $room = $this->techGetPlayerRoom();

        // комната назначения
        $roomwheretogo = $room->getUp();

        // если выхода в той стороне нет
        if ($roomwheretogo == "null") {
            $result["message"] = "0:3"; // нет выхода

            return $result;
        }

        // запись информации о комнате назначения
        $roomnew = $this->techGetRoom($roomwheretogo);
        $this->user->setRoom($roomnew);
        $this->em->flush();

        // отображение текущей комнаты
        $result["roomname"] = $roomnew->getName();
        $result["roomdesc"] = $roomnew->getRoomdesc();

        return $result;
    }

    // перемещение: вниз
    public function down() {
        $room = $this->techGetPlayerRoom();

        // комната назначения
        $roomwheretogo = $room->getDown();

        // если выхода в той стороне нет
        if ($roomwheretogo == "null") {
            $result["message"] = "0:3"; // нет выхода

            return $result;
        }

        // запись информации о комнате назначения
        $roomnew = $this->techGetRoom($roomwheretogo);
        $this->user->setRoom($roomnew);
        $this->em->flush();

        // отображение текущей комнаты
        $result["roomname"] = $roomnew->getName();
        $result["roomdesc"] = $roomnew->getRoomdesc();

        return $result;
    }
}
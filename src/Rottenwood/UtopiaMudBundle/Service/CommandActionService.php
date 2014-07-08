<?php

namespace Rottenwood\UtopiaMudBundle\Service;

/**
 * Сервис команд действия
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class CommandActionService {

    protected $em;
    protected $kernel;
    protected $container;
    protected $user;
    protected $id;

    public function __construct() {

    }

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

            $result = array();
            $result["message"] = "0:3"; // нет выхода
            return $result;
        } else {
            return false;
        }
    }

    // смотреть
    public function look() {


        $result = array();
        $result["message"] = "1:1"; // вы осмотрелись
        $result["roomnamelook"] = "test";
        $result["roomdesclook"] = "testdesc";

        return $result;
    }

}
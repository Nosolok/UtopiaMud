<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Сервис команд действия
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class CommandActionService {

    protected $em;
    protected $kernel;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    // смотреть
    public function look() {
        $result["message"] = "1:1"; // вы осмотрелись
        $result["roomnamelook"] = "Тестовая комната";
        $result["roomdesclook"] = "Приглушенный свет этой тестовой комнаты создает уютную атмосферу.";
        $result["exits"]["n"] = "open";
        $result["exits"]["s"] = "closed";
        return $result;
    }

    // перемещение: север
    public function north() {

        return 1;
    }
}
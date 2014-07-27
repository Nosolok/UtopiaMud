<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Сервис обработки мировых событий
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class WorldService {

    protected $em;
    protected $counter;
    protected $dataChannel;

    public function __construct(EntityManager $em, DataChannelService $dataChannel) {
        $this->em = $em;
        $this->dataChannel = $dataChannel;
        $this->counter = 0;
    }

    /**
     * Цикл смены погоды в мире
     * @return mixed
     */
    public function weather() {
        $weather = array(
            "4:1",
            "4:2",
            "4:3",
            "4:4",
        );

        if ($this->counter == count($weather) - 1) {
            $this->counter = 0;
        } else {
            $this->counter++;
        }
        $result = $weather[$this->counter];

        return $result;
    }

}
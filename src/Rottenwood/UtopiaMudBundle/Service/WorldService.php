<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Сервис обработки мировых событий
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class WorldService {

    protected $em;
    protected $kernel;
    protected $counter;
    protected $dataChannel;
    protected $roomTypes;

    public function __construct(EntityManager $em, DataChannelService $dataChannel, Kernel $kernel) {
        $this->em = $em;
        $this->kernel = $kernel;
        $this->dataChannel = $dataChannel;
        $this->counter = 0;
        $this->roomTypes = 0;
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
<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

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

        // парсинг файла списка зон
        $path = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/types/roomtypes.yml");
        if (!is_string($path)) {
            throw new Exception("Type of $path must be string.");
        }
        $this->roomTypes = Yaml::parse(file_get_contents($path));
        $this->roomTypes = $this->roomTypes["roomtypes"];
    }

    /**
     * Цикл смены погоды в мире
     * @param array $onlineChars
     * @return mixed
     */
    public function weather($onlineChars) {

        if (!$onlineChars) {
            return false;
        }

        $charsOutside = array();
        /** @var \Rottenwood\UtopiaMudBundle\Entity\Player $player */
        foreach ($onlineChars as $player) {
            $roomType = $player->getRoom()->getType();

            // Проверка находится ли персонаж на улице
            if (!(array_key_exists($roomType, $this->roomTypes) && array_key_exists("noweather",
                    $this->roomTypes[$roomType]))
            ) {
                $charsOutside[] = $player;
            }

        }

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

        $result = array(
            "chars"   => $charsOutside,
            "weather" => $weather[$this->counter],
        );

        return $result;
    }

}
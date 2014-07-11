<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 16:05
 */

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Rottenwood\UtopiaMudBundle\Entity\Room;
use Rottenwood\UtopiaMudBundle\Repository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

/**
 * Сервис служебных команд
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class CommandSystemService {

    private $kernel;
    private $em;

    public function __construct(Kernel $kernel, EntityManager $em) {
        $this->kernel = $kernel;
        $this->em = $em;
    }

    // конец (выход)
    public function quit() {
        $result = array();
        $result["message"] = "0:5"; // выход
        return $result;
    }

    // форматирование списка зон, перевод зон из YAML в БД и наоборот
    public function format() {
        $result = array();
        $zoneanchor = "medievaltown";

        // парсинг файла списка зон
        $path = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/zones/zonelist.yml");
        if (!is_string($path)) {
            throw new Exception("Type of $path must be string.");
        }
        $zones = Yaml::parse(file_get_contents($path));

        // проверка наличия искомой зоны в файле списка зон
        if (array_key_exists($zoneanchor, $zones["zonelist"])) {
            // парсинг выбранной зоны
            $zonepath = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/zones/" . $zoneanchor . "/rooms.yml");
            if (!is_string($zonepath)) {
                throw new Exception("Type of $zonepath must be string.");
            }
            $zone = Yaml::parse(file_get_contents($zonepath));

            /** @var Repository\RoomRepository $roomRepository */
            $roomRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room');

            // цикл создания и записи в базу новых комнат
            foreach ($zone["rooms"] as $anchor => $roomData) {

                $oldRoom = $roomRepository->findByAnchor($anchor, $zoneanchor);

                if ($oldRoom) {
                	// если комната уже существовала
                    $room = $oldRoom[0];
                } else {
                    // если комнаты еще нет
                    $room = new Room();
                }

                $room->setAnchor($anchor);
                $room->setName($roomData["name"]);
                $room->setRoomdesc($roomData["desc"]);
                $room->setZone($zoneanchor);
                $room->setType($roomData["type"]);

                if ($roomData["exits"]["north"]) {
                    $room->setNorth($roomData["exits"]["north"]);
                }
                if ($roomData["exits"]["south"]) {
                    $room->setSouth($roomData["exits"]["south"]);
                }
                if ($roomData["exits"]["east"]) {
                    $room->setEast($roomData["exits"]["east"]);
                }
                if ($roomData["exits"]["west"]) {
                    $room->setWest($roomData["exits"]["west"]);
                }

                // запись объекта в БД
                $this->em->persist($room);
                $this->em->flush();
            }

            // вывод результата
            $result["test"] = $zone;
        } else {
            // зоны не существует
            $result["test"] = "zone not found";
        }

        return $result;
    }

}

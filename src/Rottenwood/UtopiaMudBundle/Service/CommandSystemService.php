<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 16:05
 */

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Rottenwood\UtopiaMudBundle\Entity\Player;
use Rottenwood\UtopiaMudBundle\Entity\Race;
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
    public function import() {
        $result = array();

        // парсинг файла списка зон
        $path = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/zones/zonelist.yml");
        if (!is_string($path)) {
            throw new Exception("Type of $path must be string.");
        }
        $zones = Yaml::parse(file_get_contents($path));

        echo "Загрузка компонентов:\n";
        // Обработка каждой зоны
        foreach ($zones["zonelist"] as $zoneanchor => $zone) {

            // если зона размещена на удаленном хосте.
            if (array_key_exists("url", $zone)) {
                $yamlRemote = @file_get_contents($zone["url"]);

                if ($yamlRemote) {
                    echo $zoneanchor, ": зона на удаленном хосте загружена.\n";
                    $zone = Yaml::parse($yamlRemote);
                } else {
                    echo $zoneanchor, ": зона на удаленном хосте НЕ загружена!\n";
                    continue;
                }

            } else {
                echo $zoneanchor, ": загрузка локальной зоны.\n";
                $zonepath = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/zones/" . $zoneanchor . "/rooms.yml");
                if (!is_string($zonepath)) {
                    throw new Exception("Type of $zonepath must be string.");
                }
                $zone = Yaml::parse(file_get_contents($zonepath));
            }

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

                if (array_key_exists("north", $roomData["exits"])) {
                    $room->setNorth($roomData["exits"]["north"]);
                } else {
                    $room->setNorth("");
                }
                if (array_key_exists("south", $roomData["exits"])) {
                    $room->setSouth($roomData["exits"]["south"]);
                } else {
                    $room->setSouth("");
                }
                if (array_key_exists("east", $roomData["exits"])) {
                    $room->setEast($roomData["exits"]["east"]);
                } else {
                    $room->setEast("");
                }
                if (array_key_exists("west", $roomData["exits"])) {
                    $room->setWest($roomData["exits"]["west"]);
                } else {
                    $room->setWest("");
                }
                if (array_key_exists("up", $roomData["exits"])) {
                    $room->setUp($roomData["exits"]["up"]);
                } else {
                    $room->setUp("");
                }
                if (array_key_exists("down", $roomData["exits"])) {
                    $room->setDown($roomData["exits"]["down"]);
                } else {
                    $room->setDown("");
                }

                // подготовка объекта для записи в БД
                $this->em->persist($room);
            }
        }

        // запись в БД
        $this->em->flush();

        // вывод результата
        $result["system"] = "Зоны были успешно импортирована.";

        // Импорт рас
        $setRaces = $this->setRaces();
        if ($setRaces) {
            $result["system2"] = "Расы были успешно импортированы.";
        } else {
            $result["system2"] = "Расы не импортированы!";
        }

        return $result;
    }

    // Загрузка списка рас и импорт его в базу данных
    public function setRaces() {
        $raceArchive = array();
        // парсинг файла списка рас
        $path = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/races/races.yml");
        if (!is_string($path)) {
            throw new Exception("Type of $path must be string.");
        }
        $races = Yaml::parse(file_get_contents($path));

        // цикл создания и записи в базу рас
        foreach ($races["races"] as $race => $raceData) {
            /** @var Repository\RaceRepository $raceRepository */
            $raceRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Race');
            $oldRace = $raceRepository->findByAnchor($race);

            // если раса недоступна игрокам
            if (array_key_exists("npconly", $raceData) && $raceData["npconly"] == "true") {

                // проверка существования расы в базе
                if ($oldRace) {
                    /** @var Race $oldRace */
                    $oldRace = $oldRace[0];
                    $oldRaceId = $oldRace->getId();
                    // очеловечивание
                    /** @var Repository\PlayerRepository $playerRepository */
                    $playerRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Player');
                    $oldRacePlayers = $playerRepository->findByRace($oldRaceId);

                    foreach ($oldRacePlayers as $player) {
                        $raceHuman = $raceArchive[0];
                        /** @var Player $player */
                        $player->setRace($raceHuman);
                    }

                    // удалить расу из базы
                    $this->em->remove($oldRace);
                }


                continue;
            }


            if ($oldRace) {
                // если раса уже существовала
                $newRace = $oldRace[0];
            } else {
                // если расы еще нет
                $newRace = new Race();
            }

            $newRace->setAnchor($race);
            $newRace->setName($raceData["name"]);
            $newRace->setNamef($raceData["namef"]);
            $newRace->setSize($raceData["size"]);
            $newRace->setST($raceData["ST"]);
            $newRace->setDX($raceData["DX"]);
            $newRace->setIQ($raceData["IQ"]);
            $newRace->setHT($raceData["HT"]);

            $raceArchive[] = $newRace;

            // запись объекта в БД
            $this->em->persist($newRace);
        }

        $this->em->flush();

        return true;
    }

}

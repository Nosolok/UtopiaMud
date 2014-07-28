<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 16:05
 */

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Rottenwood\UtopiaMudBundle\Entity\Livemob;
use Rottenwood\UtopiaMudBundle\Entity\Mob;
use Rottenwood\UtopiaMudBundle\Entity\Player;
use Rottenwood\UtopiaMudBundle\Entity\Race;
use Rottenwood\UtopiaMudBundle\Entity\Room;
use Rottenwood\UtopiaMudBundle\Repository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

/**
 * Сервис служебных команд
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class CommandSystemService {

    private $kernel;
    private $em;
    private $container;
    private $roomRepository;
    private $playerRepository;
    private $mobRepository;
    private $livemobRepository;

    public function __construct(Kernel $kernel, EntityManager $em, Container $container) {
        $this->kernel = $kernel;
        $this->container = $container;
        $this->em = $em;
        $this->roomRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room');
        $this->mobRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Mob');
        $this->livemobRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Livemob');
        $this->playerRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Player');
    }

    /**
     * Технический метод создания комнаты
     * @param string $dir
     * @param array  $roomData
     * @return array
     */
    private function techImportCreateRoomExits($dir, $roomData) {
        $roomDir = "";
        $roomDirDoor = array();
        if (array_key_exists($dir, $roomData["exits"])) {
            if (is_array($roomData["exits"][$dir]) && array_key_exists("to", $roomData["exits"][$dir])) {
                $roomDir = $roomData["exits"][$dir]["to"];
                if (array_key_exists("door", $roomData["exits"][$dir])) {
                    $roomDirDoor = array(
                        "door"     => $roomData["exits"][$dir]["door"],
                        "doorname" => $roomData["exits"][$dir]["doorname"],
                    );
                }
            } else {
                $roomDir = $roomData["exits"][$dir];
            }
        }

        $result = array(
            "room" => $roomDir,
            "door" => $roomDirDoor,
        );

        return $result;
    }

    /**
     * Выход из игры
     * @return array
     */
    public function quit() {
        $result = array();
        $result["message"] = "0:5"; // выход
        return $result;
    }


    /**
     * форматирование списка зон, перевод зон из YAML в БД и наоборот
     * @return array
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function import() {
        $result = array();

        // удаление всех живых монстров
        $this->livemobRepository->deleteAll();

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

            if (array_key_exists("mobs", $zone)) {
                // цикл создания и записи в базу новых монстров
                $i = 1;
                foreach ($zone["mobs"] as $mobAnchor => $mob) {
                    $oldMob = $this->mobRepository->findByAnchor($mobAnchor, $zoneanchor);

                    $i++;
                    if ($oldMob) {
                        // если комната уже существовала
                        $importMob{$i} = $oldMob[0];
                    } else {
                        // если комнаты еще нет
                        $importMob{$i} = new Mob();
                    }

                    $importMob{$i}->setName($mobAnchor);
                    $importMob{$i}->setName1($mob["name"][0]);
                    $importMob{$i}->setName2($mob["name"][1]);
                    $importMob{$i}->setName3($mob["name"][2]);
                    $importMob{$i}->setName4($mob["name"][3]);
                    $importMob{$i}->setName5($mob["name"][4]);
                    $importMob{$i}->setName6($mob["name"][5]);
                    $importMob{$i}->setShortdesc($mob["short"]);
                    $importMob{$i}->setLongdesc($mob["desc"]);
                    $importMob{$i}->setRace($mob["race"]);
                    $importMob{$i}->setST($mob["ST"]);
                    $importMob{$i}->setDX($mob["DX"]);
                    $importMob{$i}->setIQ($mob["IQ"]);
                    $importMob{$i}->setHT($mob["HT"]);
                    $importMob{$i}->setZone($zoneanchor);

                    // подготовка объекта для записи в БД
                    $this->em->persist($importMob{$i});
                }
            }

            // цикл создания и записи в базу новых комнат
            foreach ($zone["rooms"] as $anchor => $roomData) {

                $oldRoom = $this->roomRepository->findByAnchor($anchor, $zoneanchor);
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

                // выходы и двери

                // север
                $dir = "north";
                $dirExits = $this->techImportCreateRoomExits($dir, $roomData);
                $room->setNorth($dirExits["room"]);
                $room->setNorthdoor($dirExits["door"]);

                // юг
                $dir = "south";
                $dirExits = $this->techImportCreateRoomExits($dir, $roomData);
                $room->setSouth($dirExits["room"]);
                $room->setSouthdoor($dirExits["door"]);

                // запад
                $dir = "west";
                $dirExits = $this->techImportCreateRoomExits($dir, $roomData);
                $room->setWest($dirExits["room"]);
                $room->setWestdoor($dirExits["door"]);

                // восток
                $dir = "east";
                $dirExits = $this->techImportCreateRoomExits($dir, $roomData);
                $room->setEast($dirExits["room"]);
                $room->setEastdoor($dirExits["door"]);

                // вверх
                $dir = "up";
                $dirExits = $this->techImportCreateRoomExits($dir, $roomData);
                $room->setUp($dirExits["room"]);
                $room->setUpdoor($dirExits["door"]);

                // вниз
                $dir = "down";
                $dirExits = $this->techImportCreateRoomExits($dir, $roomData);
                $room->setDown($dirExits["room"]);
                $room->setDowndoor($dirExits["door"]);

                // если в комнате указаны мобы
                if (array_key_exists("mobs", $roomData)) {
                    $i = 1;
                    foreach ($roomData["mobs"] as $mobInRoomAnchor) {
                        $mobInRoom = $this->mobRepository->findByAnchor($mobInRoomAnchor, $zoneanchor);
                        // TODO: разобраться получше
                        if (!$mobInRoom) {
                            continue;
                        }
                        /** @var Mob $mobInRoom */
                        $mobInRoom = $mobInRoom[0];
                        // расчет максимального хп монстра: HT * 10
                        $mobHp = 10 * $mobInRoom->getHT();

                        $livemob{$i} = new Livemob();
                        $livemob{$i}->setMob($mobInRoom);
                        $livemob{$i}->setRoom($room);
                        $livemob{$i}->setHp($mobHp);

                        $this->em->persist($livemob{$i});
                        $i++;
                    }
                }

                // подготовка объектов для записи в БД
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

    /**
     * Загрузка списка рас и импорт его в базу данных
     * @return bool
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
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

    /**
     * Прыжок в комнату по якорю и зоне
     * @param Player $char
     * @param        $arguments
     * @return array
     */
    public function jumpto(Player $char, $arguments) {
        $result = array();

        if ($arguments && $arguments[1] && $arguments[2]) {
            // приведение первого аргумента введенной команды в нижний регистр
            $room = mb_strtolower($arguments[1], 'UTF-8');
            $zone = mb_strtolower($arguments[2], 'UTF-8');


            $roomExist = $this->roomRepository->findByAnchor($room, $zone);
            $roomExist = $roomExist[0];

            if (!$roomExist) {
                $result["system"] = "Комната не найдена. Применение: jumpto [якорь-комнаты] [якорь-зоны]";
                return $result;
            }

            // перемещение
            $this->container->get('commandaction')->techGotoRoom($char, $roomExist);
            $roomName = $roomExist->getName();
            $result["system"] = "Ты прыгнул к '$roomName'.";

        } else {
            $result["system"] = "Применение: jumpto [якорь-комнаты] [якорь-зоны]";
        }


        return $result;
    }

    /**
     * Телепортация к персонажу
     * @param Player $char
     * @param        $arguments
     * @return array
     */
    public function jumptp(Player $char, $arguments) {
        $result = array();

        if ($arguments && $arguments[1]) {
            $charName = $char->getUsername();

            // приведение первого аргумента введенной команды в нижний регистр
            $targetPlayer = mb_strtolower($arguments[1], 'UTF-8');

            $playersOnlineIds = $this->container->get('datachannel')->getOnlineIds();
            $playersOnline = $this->playerRepository->findPlayersOnline($playersOnlineIds);

            foreach ($playersOnline as $player) {
                /** @var Player $player */
                $playerName = $player->getUsernameCanonical();
                $playerNameFull = $player->getUsername();

                if (strpos($playerName, $targetPlayer) !== false) {
                    // если персонаж найден
                    $result["system"] = "Ты прыгнул к $playerNameFull!";

                    // перемещение
                    $destinationRoom = $player->getRoom();
                    $char->setRoom($destinationRoom);

                    $this->em->persist($char);
                    $this->em->flush();

                    // отправка сообщения адресату
                    $result["3rd"] = array($player);
                    $result["3rdecho"] = array(
                        "system" => "$charName прыгнул к тебе!",
                    );

                    return $result;

                }
            }

        } else {
            $result["system"] = "Персонаж не найден. Применение: jumptp [имя-персонажа]";
        }

        return $result;
    }

}

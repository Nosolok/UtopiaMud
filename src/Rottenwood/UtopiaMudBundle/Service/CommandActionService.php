<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Rottenwood\UtopiaMudBundle\Entity\Livemob;
use Rottenwood\UtopiaMudBundle\Entity\Mob;
use Rottenwood\UtopiaMudBundle\Entity\Player;
use Rottenwood\UtopiaMudBundle\Entity\Race;
use Rottenwood\UtopiaMudBundle\Entity\Room;
use Rottenwood\UtopiaMudBundle\Repository;
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
    protected $roomRepository;
    protected $playerRepository;
    protected $mobRepository;
    private $livemobRepository;

    public function __construct(Container $container, EntityManager $em) {
        $this->container = $container;
        $this->em = $em;
        /** @var Repository\RoomRepository */
        $this->roomRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room');
        /** @var Repository\PlayerRepository */
        $this->playerRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Player');
        /** @var Repository\MobRepository */
        $this->mobRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Mob');
        /** @var Repository\LivemobRepository */
        $this->livemobRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Livemob');
    }

    /**
     * Техническая функция осмотра комнаты
     * @param \Rottenwood\UtopiaMudBundle\Entity\Room         $room
     * @param                                         integer $charId
     * @internal param \Rottenwood\UtopiaMudBundle\Entity\Player $char
     * @return mixed
     */
    public function techLook($room, $charId) {
        $roomId = $room->getId();
        $result = array();
        // осмотр комнаты
        $result["roomname"] = $room->getName();
        $result["roomdesc"] = $room->getRoomdesc();

        if ($exits = $this->techLookExits($room)) {
            $result["exits"] = $exits;
        } else {
            $result["exits"] = "no";
        }

        // персонажи
        $playersOnline = $this->container->get('datachannel')->getOnlineIds($charId);
        $playersInRoom = $this->roomRepository->findPlayersInRoom($roomId, $playersOnline);

        if ($playersInRoom) {
            foreach ($playersInRoom as $player) {
                $playerSex = $player->getSex();
                if ($playerSex == 2) {
                    $getNameFunction = "getNamef";
                } else {
                    $getNameFunction = "getName";
                }
                $result["players"][$player->getUsername()]["race"] = $player->getRace()->{$getNameFunction}();
            }
        }

        // мобы
        $mobsInRoom = $this->livemobRepository->findMobsInRoom($room);

        if ($mobsInRoom) {
            foreach ($mobsInRoom as $mob) {
                /** @var Livemob $mob */
                $mobName1 = $mob->getMob()->getName1();
                $mobShort = $mob->getMob()->getShortdesc();
                $result["mobs"][] = array(
                    "name"  => $mobName1,
                    "short" => $mobShort,
                );
            }
        }

        return $result;
    }

    /**
     * Сбор и отображение выходов из комнаты
     * @param \Rottenwood\UtopiaMudBundle\Entity\Room $room
     * @return array
     */
    public function techLookExits($room) {
        $result = array();
        if ($room->getNorth()) {
            $door = $room->getNorthdoor();
            $isDoor = $this->techCheckDoor($door);

            if (!$isDoor) {
                $result["n"] = 1;
            }

        }
        if ($room->getSouth()) {
            $door = $room->getSouthdoor();
            $isDoor = $this->techCheckDoor($door);

            if (!$isDoor) {
                $result["s"] = 1;
            }
        }
        if ($room->getEast()) {
            $door = $room->getEastdoor();
            $isDoor = $this->techCheckDoor($door);

            if (!$isDoor) {
                $result["e"] = 1;
            }

        }
        if ($room->getWest()) {
            $door = $room->getWestdoor();
            $isDoor = $this->techCheckDoor($door);

            if (!$isDoor) {
                $result["w"] = 1;
            }
        }
        if ($room->getUp()) {
            $door = $room->getUpdoor();
            $isDoor = $this->techCheckDoor($door);

            if (!$isDoor) {
                $result["u"] = 1;
            }
        }
        if ($room->getDown()) {
            $door = $room->getDowndoor();
            $isDoor = $this->techCheckDoor($door);

            if (!$isDoor) {
                $result["d"] = 1;
            }
        }

        return $result;
    }

    /**
     * Техническая функция перемещения персонажа
     * персонаж, комната назначения
     * @param Player                                  $char
     * @param \Rottenwood\UtopiaMudBundle\Entity\Room $room
     * @return bool
     */
    public function techGotoRoom($char, $room) {

        if ($room !== null) {
            $char->setRoom($room);
            $this->em->persist($char);
            $this->em->flush();
        }

        return true;
    }

    public function techMove(Player $char, $direction, $destinationRoomAnchor) {
        $result = array();
        $room = $char->getRoom();
        $charName = $char->getUsername();

        // если выхода не найдено
        if ($destinationRoomAnchor == null) {
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        /** @var Room $room */
        if (strpos($destinationRoomAnchor, ':')) {
            // если якорь содержит ссылку на другую зону
            list($destinationRoomAnchor, $zone) = explode(":", $destinationRoomAnchor);
        } else {
            $zone = $room->getZone();
        }

        // сообщение о перемещении
        if ($direction == "north") {
            $directionMessageEnter = "1:3:1"; // ушел на север
            $directionMessageLeave = "1:4:1"; // пришел с юга
        } elseif ($direction == "east") {
            $directionMessageEnter = "1:3:2"; // ушел на восток
            $directionMessageLeave = "1:4:2"; // пришел с востока
        } elseif ($direction == "south") {
            $directionMessageEnter = "1:3:3"; // ушел на юг
            $directionMessageLeave = "1:4:3"; // пришел с юга
        } elseif ($direction == "west") {
            $directionMessageEnter = "1:3:4"; // ушел на запад
            $directionMessageLeave = "1:4:4"; // пришел с запада
        } elseif ($direction == "down") {
            $directionMessageEnter = "1:3:6"; // ушел вниз
            $directionMessageLeave = "1:4:6"; // пришел снизу
        } else {
            $directionMessageEnter = "1:3:5"; // ушел наверх
            $directionMessageLeave = "1:4:5"; // пришел сверху
        }

        // перемещение в комнату назначения
        /** @method Repository\RoomRepository findByAnchor() */
        $destinationRoom = $this->roomRepository->findByAnchor($destinationRoomAnchor, $zone);
        /** @var Room $destinationRoom */
        // если комната не найдена в базе
        if (!$destinationRoom) {
            // TODO: добавить логирование ошибки
            $result["message"] = "0:3"; // нет выхода
            return $result;
        }

        $destinationRoom = $destinationRoom[0];
        $this->techGotoRoom($char, $destinationRoom);
        $result = $this->techLook($destinationRoom, $char->getId());

        // оповещение всех в комнате
        $playersOnline = $this->container->get('datachannel')->getOnlineIds($char->getId());
        $playersInRoom = $this->roomRepository->findPlayersInRoom($room->getId(), $playersOnline);

        if ($playersInRoom) {
            $result["3rd"] = $playersInRoom;
            $result["3rdecho"] = array();
            $result["3rdecho"]["message"] = $directionMessageEnter;
            $result["3rdecho"]["who"] = $charName;
        }

        // оповещение всех в комнате назначения
        $playersInRoom = $this->roomRepository->findPlayersInRoom($destinationRoom->getId(), $playersOnline);

        if ($playersInRoom) {
            $result["4rd"] = $playersInRoom;
            $result["4rdecho"] = array();
            $result["4rdecho"]["message"] = $directionMessageLeave;
            $result["4rdecho"]["who"] = $charName;
        }

        return $result;
    }

    /**
     * Проверка наличия двери
     * @param $door
     * @return array|bool
     */
    public function techCheckDoor($door) {
        $result = array();

        if (!is_array($door)) {
            return false;
        }

        if (array_key_exists("door", $door) && $door["door"] == "closed") {
            $result["message"] = "0:7:1";
            $result["gate"] = $door["doorname"][0];
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Техническая функция открытия дверей
     * @param $whatToOpen
     * @param array $doorNames
     * @param Room  $room
     * @param $zone
     * @return array
     */
    public function techOpen($whatToOpen, $doorNames, Room $room, $zone) {

        $result = array();
        $result["message"] = "0:2:1";
        $result["object"] = $whatToOpen;

        // проверка наличия двери
        foreach ($doorNames as $doorName) {

            // проверка по направлению двери
            $doorNameDirection = $doorName[4];
            if (strpos($doorNameDirection, $whatToOpen) !== false) {
                $result = $this->techOpenDir($room, $doorName, $doorNameDirection, $zone);
                break;
            }

            $doorNameDir = $doorName[0];
            // проверка по названию двери
            if (strpos($doorNameDir, $whatToOpen) !== false) {
                $result = $this->techOpenDir($room, $doorName, $doorNameDir, $zone);
                break;
            }
        }

        return $result;
    }

    /**
     * Техническая функция закрытия дверей
     * @param $whatToOpen
     * @param array $doorNames
     * @param Room  $room
     * @param $zone
     * @return array
     */
    public function techClose($whatToOpen, $doorNames, Room $room, $zone) {

        $result = array();
        $result["message"] = "0:2:1";
        $result["object"] = $whatToOpen;

        // проверка наличия двери
        foreach ($doorNames as $doorName) {

            // проверка по направлению двери
            $doorNameDirection = $doorName[4];
            if (strpos($doorNameDirection, $whatToOpen) !== false) {
                $result = $this->techCloseDir($room, $doorName, $doorNameDirection, $zone);
                break;
            }

            $doorNameDir = $doorName[0];
            // проверка по названию двери
            if (strpos($doorNameDir, $whatToOpen) !== false) {
                $result = $this->techCloseDir($room, $doorName, $doorNameDir, $zone);
                break;
            }
        }

        return $result;
    }

    /**
     * Техническй метод открытия двери по направлению
     * @param $room
     * @param $doorName
     * @param $doorNameDir
     * @param $zone
     * @return array
     */
    public function techOpenDir($room, $doorName, $doorNameDir, $zone) {
        $result = array();

        $result["message"] = "0:7:3";
        $result["object"] = $doorName[2];

        $openDoorDir = $doorName[1];

        $openDoorGet = "get{$openDoorDir}door";
        $openDoorSet = "set{$openDoorDir}door";
        $openDoorDirMethodRun = $room->{$openDoorGet}();

        // если дверь уже открыта
        if ($openDoorDirMethodRun["door"] == "open") {
            $result["message"] = "0:7:5";
            $result["object"] = $doorNameDir;
            return $result;
        }

        $openDoorDirMethodRun["door"] = "open";
        // изменения параметра комнаты: открыть дверь
        $room->{$openDoorSet}($openDoorDirMethodRun);

        // открытие двери в соседней комнате
        $roomDestinationGet = "get{$openDoorDir}";
        $roomDestinationSet = "set{$openDoorDir}";
        /** @var Room $roomDestination */
        $roomDestinationAnchor = $room->{$roomDestinationGet}();
        $roomDestination = $this->roomRepository->findByAnchor($roomDestinationAnchor, $zone);
        $roomDestination = $roomDestination[0];
        $openDestinationDoorDir = $doorName[3];
        $openDestinationDoorGet = "get{$openDestinationDoorDir}door";
        $openDestinationDoorSet = "set{$openDestinationDoorDir}door";
        $roomDestinationObj = $roomDestination->{$openDestinationDoorGet}();
        $roomDestinationObj["door"] = "open";
        $resultDestinationDoor = $roomDestination->{$openDestinationDoorSet}($roomDestinationObj);
        // изменения параметра соседней комнаты: открыть дверь
        $roomDestination->{$roomDestinationSet}($resultDestinationDoor);

        return $result;
    }

    /**
     * Техническй метод открытия двери по направлению
     * @param $room
     * @param $doorName
     * @param $doorNameDir
     * @param $zone
     * @return array
     */
    public function techCloseDir($room, $doorName, $doorNameDir, $zone) {
        $result = array();

        $result["message"] = "0:7:4";
        $result["object"] = $doorName[2];

        $openDoorDir = $doorName[1];

        $openDoorGet = "get{$openDoorDir}door";
        $openDoorSet = "set{$openDoorDir}door";
        $openDoorDirMethodRun = $room->{$openDoorGet}();

        // если дверь уже открыта
        if ($openDoorDirMethodRun["door"] == "closed") {
            $result["message"] = "0:7:6";
            $result["object"] = $doorNameDir;
            return $result;
        }

        $openDoorDirMethodRun["door"] = "closed";
        // изменения параметра комнаты: открыть дверь
        $room->{$openDoorSet}($openDoorDirMethodRun);

        // открытие двери в соседней комнате
        $roomDestinationGet = "get{$openDoorDir}";
        $roomDestinationSet = "set{$openDoorDir}";
        /** @var Room $roomDestination */
        $roomDestinationAnchor = $room->{$roomDestinationGet}();
        $roomDestination = $this->roomRepository->findByAnchor($roomDestinationAnchor, $zone);
        $roomDestination = $roomDestination[0];
        $openDestinationDoorDir = $doorName[3];
        $openDestinationDoorGet = "get{$openDestinationDoorDir}door";
        $openDestinationDoorSet = "set{$openDestinationDoorDir}door";
        $roomDestinationObj = $roomDestination->{$openDestinationDoorGet}();
        $roomDestinationObj["door"] = "closed";
        $resultDestinationDoor = $roomDestination->{$openDestinationDoorSet}($roomDestinationObj);
        // изменения параметра соседней комнаты: открыть дверь
        $roomDestination->{$roomDestinationSet}($resultDestinationDoor);

        return $result;
    }

    /**
     * Command: look
     * @param Player $char
     * @param        $arguments
     * @return array
     */
    public function look(Player $char, $arguments) {
        $result = array();
        /** @var Room $room */
        $room = $char->getRoom();
        $roomId = $room->getId();

        // если персонаж посмотрел на что-то
        if ($arguments) {
            // приведение первого аргумента введенной команды в нижний регистр
            $argument = mb_strtolower($arguments[1], 'UTF-8');

            // объекты в комнате
            $playersOnline = $this->container->get('datachannel')->getOnlineIds(0);
            $playersInRoom = $this->roomRepository->findPlayersInRoom($roomId, $playersOnline);
            $mobsInRoom = $this->livemobRepository->findMobsInRoom($room);

            $result["message"] = "0:2:1";
            $result["object"] = $argument;

            if ($playersInRoom) {
                foreach ($playersInRoom as $player) {
                    /** @var Player $player */
                    $playerName = $player->getUsernameCanonical();
                    $playerNameFull = $player->getUsername();

                    // проверка наличия имени
                    if (strpos($playerName, $argument) !== false) {
                        $playerDesc = $player->getLongDesc();
                        $result["message"] = "1:2";
                        $result["object"] = $playerNameFull;
                        $result["desc"] = $playerDesc;
                    }
                }
            }

            if ($mobsInRoom) {
                foreach ($mobsInRoom as $mobInRoom) {
                    /** @var Livemob $mobInRoom */
                    /** @var Mob $mobInRoomObject */
                    $mobInRoomObject = $mobInRoom->getMob();
                    $mobName = $mobInRoomObject->getName1();
                    // винительный падеж
                    $mobName4 = $mobInRoomObject->getName4();

                    // проверка наличия моба
                    if (strpos($mobName, $argument) !== false) {
                        $mobDesc = $mobInRoomObject->getLongdesc();
                        $result["message"] = "1:2";
                        $result["object"] = $mobName4;
                        $result["desc"] = $mobDesc;
                    }
                }
            }


            return $result;
        }

        $result = $this->techLook($room, 0);
        $result["message"] = "1:1"; // вы осмотрелись

        return $result;
    }

    public function north(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getNorth();
        $dir = preg_replace('~.*?:~', '', __METHOD__);

        // проверка наличия двери
        $door = $room->getNorthdoor();
        $isDoor = $this->techCheckDoor($door);

        if ($isDoor) {
            return $isDoor;
        }

        $result = $this->techMove($char, $dir, $destinationRoomAnchor);

        return $result;
    }

    public function east(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getEast();
        $dir = preg_replace('~.*?:~', '', __METHOD__);

        // проверка наличия двери
        $door = $room->getEastdoor();
        $isDoor = $this->techCheckDoor($door);

        if ($isDoor) {
            return $isDoor;
        }

        $result = $this->techMove($char, $dir, $destinationRoomAnchor);

        return $result;
    }

    public function south(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getSouth();
        $dir = preg_replace('~.*?:~', '', __METHOD__);

        // проверка наличия двери
        $door = $room->getSouthdoor();
        $isDoor = $this->techCheckDoor($door);

        if ($isDoor) {
            return $isDoor;
        }

        $result = $this->techMove($char, $dir, $destinationRoomAnchor);

        return $result;
    }

    public function west(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getWest();
        $dir = preg_replace('~.*?:~', '', __METHOD__);

        // проверка наличия двери
        $door = $room->getWestdoor();
        $isDoor = $this->techCheckDoor($door);

        if ($isDoor) {
            return $isDoor;
        }

        $result = $this->techMove($char, $dir, $destinationRoomAnchor);

        return $result;
    }

    public function up(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getUp();
        $dir = preg_replace('~.*?:~', '', __METHOD__);

        // проверка наличия двери
        $door = $room->getUpdoor();
        $isDoor = $this->techCheckDoor($door);

        if ($isDoor) {
            return $isDoor;
        }

        $result = $this->techMove($char, $dir, $destinationRoomAnchor);

        return $result;
    }

    public function down(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getDown();
        $dir = preg_replace('~.*?:~', '', __METHOD__);

        // проверка наличия двери
        $door = $room->getDowndoor();
        $isDoor = $this->techCheckDoor($door);

        if ($isDoor) {
            return $isDoor;
        }

        $result = $this->techMove($char, $dir, $destinationRoomAnchor);

        return $result;
    }

    public function say(Player $char, $phrase) {
        $result = array();
        /** @var Room $room */
        $room = $char->getRoom();
        $charName = $char->getUsername();

        // сбор фразы из массива слов
        $phrase = implode(" ", $phrase);

        // если фраза не задана
        if (!$phrase) {
            $result["message"] = "0:4:1"; // сказать: фраза не задана
            return $result;
        }

        // оповещение всех в комнате
        $playersOnline = $this->container->get('datachannel')->getOnlineIds($char->getId());
        $playersInRoom = $this->roomRepository->findPlayersInRoom($room->getId(), $playersOnline);

        $result["message"] = "2:1"; // сказал
        $result["who"] = "Ты";
        $result["say"] = $phrase;

        if ($playersInRoom) {
            $result["3rd"] = $playersInRoom;
            $result["3rdecho"] = array(
                "message" => $result["message"],
                "who"     => $charName,
                "say"     => $phrase,
            );
        }

        return $result;
    }

    public function shout(Player $char, $phrase) {
        $result = array();
        /** @var Room $room */
        $room = $char->getRoom();
        $zone = $room->getZone();
        $charName = $char->getUsername();

        // сбор фразы из массива слов
        $phrase = implode(" ", $phrase);

        // если фраза не задана
        if (!$phrase) {
            $result["message"] = "0:4:2"; // кричать: фраза не задана
            return $result;
        }

        // оповещение всех в зоне
        $playersOnline = $this->container->get('datachannel')->getOnlineIds($char->getId());
        $playersInZone = $this->roomRepository->findPlayersInZone($zone, $playersOnline);

        $result["message"] = "2:2"; // крикнул
        $result["who"] = "Ты";
        $result["shout"] = $phrase;

        if ($playersInZone) {
            $result["3rd"] = $playersInZone;
            $result["3rdecho"] = array(
                "message" => $result["message"],
                "who"     => $charName,
                "shout"   => $phrase,
            );
        }

        return $result;
    }

    public function chat(Player $char, $phrase) {
        $result = array();
        $charName = $char->getUsername();

        // сбор фразы из массива слов
        $phrase = implode(" ", $phrase);

        // если фраза не задана
        if (!$phrase) {
            $result["message"] = "0:4:3"; // чат: фраза не задана
            return $result;
        }

        // оповещение всех в мире
        $playersOnline = $this->container->get('datachannel')->getOnlineIds($char->getId());
        $playersOnlineObj = $this->playerRepository->findPlayersOnline($playersOnline);

        $result["message"] = "2:3"; // сообщил в чат
        $result["who"] = $charName;
        $result["ooc"] = $phrase;

        if ($playersOnlineObj) {
            $result["3rd"] = $playersOnlineObj;
            $result["3rdecho"] = array(
                "message" => $result["message"],
                "who"     => $result["who"],
                "ooc"     => $phrase,
            );
        }

        return $result;
    }

    /**
     * Команда "who". Список игроков онлайн
     * @internal param \Rottenwood\UtopiaMudBundle\Entity\Player $char
     * @return array
     */
    public function who() {
        // Список всех игроков онлайн
        $playersOnline = $this->container->get('datachannel')->getOnlineIds(0);
        $playersOnlineObj = $this->playerRepository->findPlayersOnline($playersOnline);

        $whoOnline = array();

        $whoOnlineCount = count($playersOnlineObj);

        foreach ($playersOnlineObj as $player) {
            /** @var Player $player */
            $playerUsername = $player->getUsername();
            $playerSex = $player->getSex();
            /** @var Race $playerRace */
            $playerRace = $player->getRace();
            if ($playerSex == 1) {
                $playerRaceName = $playerRace->getName();
            } else {
                $playerRaceName = $playerRace->getNamef();
            }

            $whoOnline[$playerUsername] = array(
                "race" => $playerRaceName,
            );
        }

        $result = array(
            "message"        => "3:1",
            "whoonline"      => $whoOnline,
            "whoonlinecount" => $whoOnlineCount,
        );

        return $result;
    }

    public function open(Player $char, $arguments) {
        $result = array();
        /** @var Room $room */
        $room = $char->getRoom();
        $zone = $room->getZone();
        $doorNorth = $room->getNorthdoor();
        $doorSouth = $room->getSouthdoor();
        $doorWest = $room->getWestdoor();
        $doorEast = $room->getEastdoor();
        $doorUp = $room->getUpdoor();
        $doorDown = $room->getDowndoor();

        $doorNames = array();
        if (array_key_exists("doorname", $doorNorth)) {
            $doorNorthName2 = $doorNorth["doorname"][1];
            $doorNames[] = array($doorNorth["doorname"][0], "North", $doorNorthName2, "South", "север");
        }
        if (array_key_exists("doorname", $doorSouth)) {
            $doorSouthName2 = $doorSouth["doorname"][1];
            $doorNames[] = array($doorSouth["doorname"][0], "South", $doorSouthName2, "North", "юг");
        }
        if (array_key_exists("doorname", $doorWest)) {
            $doorWestName2 = $doorWest["doorname"][1];
            $doorNames[] = array($doorWest["doorname"][0], "West", $doorWestName2, "East", "восток");
        }
        if (array_key_exists("doorname", $doorEast)) {
            $doorEastName2 = $doorEast["doorname"][1];
            $doorNames[] = array($doorEast["doorname"][0], "East", $doorEastName2, "West", "запад");
        }
        if (array_key_exists("doorname", $doorUp)) {
            $doorUpName2 = $doorUp["doorname"][1];
            $doorNames[] = array($doorUp["doorname"][0], "Up", $doorUpName2, "Down", "вниз");
        }
        if (array_key_exists("doorname", $doorDown)) {
            $doorDownName2 = $doorDown["doorname"][1];
            $doorNames[] = array($doorDown["doorname"][0], "Down", $doorDownName2, "Up", "вверх");
        }

        if ($arguments) {
            // приведение первого и второго аргумента введенной команды в нижний регистр
            $whatToOpen = mb_strtolower($arguments[1], 'UTF-8');

            $result = $this->techOpen($whatToOpen, $doorNames, $room, $zone);

        } else {
            $result["message"] = "0:7:2";
        }

        return $result;
    }

    public function close(Player $char, $arguments) {
        $result = array();
        /** @var Room $room */
        $room = $char->getRoom();
        $zone = $room->getZone();
        $doorNorth = $room->getNorthdoor();
        $doorSouth = $room->getSouthdoor();
        $doorWest = $room->getWestdoor();
        $doorEast = $room->getEastdoor();
        $doorUp = $room->getUpdoor();
        $doorDown = $room->getDowndoor();

        $doorNames = array();
        if (array_key_exists("doorname", $doorNorth)) {
            $doorNorthName2 = $doorNorth["doorname"][1];
            $doorNames[] = array($doorNorth["doorname"][0], "North", $doorNorthName2, "South", "север");
        }
        if (array_key_exists("doorname", $doorSouth)) {
            $doorSouthName2 = $doorSouth["doorname"][1];
            $doorNames[] = array($doorSouth["doorname"][0], "South", $doorSouthName2, "North", "юг");
        }
        if (array_key_exists("doorname", $doorWest)) {
            $doorWestName2 = $doorWest["doorname"][1];
            $doorNames[] = array($doorWest["doorname"][0], "West", $doorWestName2, "East", "запад");
        }
        if (array_key_exists("doorname", $doorEast)) {
            $doorEastName2 = $doorEast["doorname"][1];
            $doorNames[] = array($doorEast["doorname"][0], "East", $doorEastName2, "West", "восток");
        }
        if (array_key_exists("doorname", $doorUp)) {
            $doorUpName2 = $doorUp["doorname"][1];
            $doorNames[] = array($doorUp["doorname"][0], "Up", $doorUpName2, "Down", "вверх");
        }
        if (array_key_exists("doorname", $doorDown)) {
            $doorDownName2 = $doorDown["doorname"][1];
            $doorNames[] = array($doorDown["doorname"][0], "Down", $doorDownName2, "Up", "вниз");
        }

        if ($arguments) {
            // приведение первого аргумента введенной команды в нижний регистр
            $whatToOpen = mb_strtolower($arguments[1], 'UTF-8');

            $result = $this->techClose($whatToOpen, $doorNames, $room, $zone);

        } else {
            $result["message"] = "0:7:2";
        }

        return $result;
    }
}
<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Rottenwood\UtopiaMudBundle\Entity\Player;
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

    public function __construct(Container $container, EntityManager $em) {
        $this->container = $container;
        $this->em = $em;
        /** @var Repository\RoomRepository $roomRepository */
        $this->roomRepository = $this->em->getRepository('RottenwoodUtopiaMudBundle:Room');
    }

    /**
     * Техническая функция осмотра комнаты
     * @param \Rottenwood\UtopiaMudBundle\Entity\Room $room
     * @param                                         $charId
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
            $result["n"] = 1;
        }
        if ($room->getSouth()) {
            $result["s"] = 1;
        }
        if ($room->getEast()) {
            $result["e"] = 1;
        }
        if ($room->getWest()) {
            $result["w"] = 1;
        }
        if ($room->getUp()) {
            $result["u"] = 1;
        }
        if ($room->getDown()) {
            $result["d"] = 1;
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
        $char->setRoom($room);
        $this->em->persist($char);
        $this->em->flush();

        return true;
    }

    public function techMove(Player $char, $direction ,$destinationRoomAnchor) {
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
            $argument = $arguments[1];

            // персонажи в комнате
            $playersOnline = $this->container->get('datachannel')->getOnlineIds(0);
            $playersInRoom = $this->roomRepository->findPlayersInRoom($roomId, $playersOnline);

            $result["message"] = "0:2:1";
            $result["object"] = $argument;

            if ($playersInRoom) {
                foreach ($playersInRoom as $player) {
                    /** @var Player $player */
                    $playerName = $player->getUsername();

                    echo "$playerName, $argument\n";

                    // проверка наличия имени
                    if (strpos($playerName, $argument) !== false) {
                        $playerDesc = $player->getLongDesc();
                        $result["message"] = "1:2";
                        $result["object"] = $playerName;
                        $result["desc"] = $playerDesc;
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
        $result = $this->techMove($char, __METHOD__, $destinationRoomAnchor);

        return $result;
    }

    public function east(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getEast();
        $result = $this->techMove($char, __METHOD__, $destinationRoomAnchor);

        return $result;
    }

    public function south(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getSouth();
        $result = $this->techMove($char, __METHOD__, $destinationRoomAnchor);

        return $result;
    }

    public function west(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getWest();
        $result = $this->techMove($char, __METHOD__, $destinationRoomAnchor);

        return $result;
    }

    public function up(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getUp();
        $result = $this->techMove($char, __METHOD__, $destinationRoomAnchor);

        return $result;
    }

    public function down(Player $char) {
        /** @var Room $room */
        $room = $char->getRoom();
        $destinationRoomAnchor = $room->getDown();
        $result = $this->techMove($char, __METHOD__, $destinationRoomAnchor);

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
}
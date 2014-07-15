<?php
/**
 * Author: Rottenwood
 * Date Created: 06.07.14 15:11
 */

namespace Rottenwood\UtopiaMudBundle\Service;

use Rottenwood\UtopiaMudBundle\Entity\Player;

/**
 * Класс для хранения списка подключенных игроков
 * Class DataChannel
 * @package Rottenwood\UtopiaMudBundle\Entity
 */
class DataChannelService {

    /**
     * Список подключенных игроков
     * @var array
     */
    public $clients = array();

    /**
     * Список служебных
     * @var array
     */
    public $channels = array();

    public function setChannels($channel, $topic) {
        $this->channels[$channel] = $topic;
    }

    public function getChannel($channel) {
        return $this->channels[$channel];
    }

    /**
     * Добавление персонажа в список подключенных персонажей
     * @param        $hash
     * @param Player $client
     */
    public function add($hash, $client) {
        $this->clients[$hash] = $client;
    }

    /**
     * Проверка токена на уникальность
     * @param string $hash
     * @return bool
     */
    public function hashIsUnique($hash) {

        if (array_key_exists($hash, $this->clients)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Возвращает список ID игроков онлайн
     * @param $charId
     * @return array
     */
    public function getOnlineIds($charId) {
        $charsIds = array();

        foreach ($this->clients as $user) {
            $userId = $user->getId();
            if ($userId != $charId) {
                $charsIds[] = $user->getId();
            }
        }

        return $charsIds;
    }

    public function getByHash($hash) {

        $client = $this->clients[$hash];

        return $client;
    }

    public function channelOnline($channel) {
        if (array_key_exists($channel, $this->channels)) {
            return true;
        } else {
            return false;
        }
    }
}

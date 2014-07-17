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
     * Добавление персонажа в список подключенных персонажей
     * @param        $hash
     * @param Player $client
     */
    public function add($hash, $client) {
        $clientAlreadyInArray = in_array($client, $this->clients);

        if ($clientAlreadyInArray) {
            $oldHash = array_search($client, $this->clients);
            unset($this->clients[$oldHash]);
        }
        $this->clients[$hash] = $client;
    }

    /**
     * Удаление персонажа из списка подключенных персонажей
     * @param $hash
     */
    public function remove($hash) {
        unset($this->clients[$hash]);
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
     * @param $charIdToIgnore
     * @return array
     */
    public function getOnlineIds($charIdToIgnore) {
        $charsIds = array();

        foreach ($this->clients as $user) {
            $userId = $user->getId();
            if ($userId != $charIdToIgnore) {
                $charsIds[] = $user->getId();
            }
        }

        return $charsIds;
    }

    /**
     * Возвращает объект Player по токену
     * @param $hash
     * @return Player $client
     */
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

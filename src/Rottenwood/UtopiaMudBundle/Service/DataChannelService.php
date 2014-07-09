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

    public $clients = array();

    /**
     * Добавление персонажа в список подключенных персонажей
     * @param $hash
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
     * Возвращает форматированный список ID игроков онлайн
     * @return string
     */
    public function getOnlineIds() {
        $charsIds = array();

        foreach ($this->clients as $user) {
            $charsIds[] = $user->getId();
        }

        $onlineList = implode(', ', $charsIds);

        return $onlineList;
    }
}

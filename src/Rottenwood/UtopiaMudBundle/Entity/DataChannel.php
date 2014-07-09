<?php
/**
 * Author: Rottenwood
 * Date Created: 06.07.14 15:11
 */

namespace Rottenwood\UtopiaMudBundle\Entity;

/**
 * Класс для хранения списка подключенных игроков
 * Class DataChannel
 * @package Rottenwood\UtopiaMudBundle\Entity
 */
class DataChannel {

    public $clients = array();

    /**
     * Добавление персонажа в список подключенных персонажей
     * @param $hash
     * @param $client
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
}

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

    public function add($client) {
        $this->clients[] = $client;
    }

    public function addArray($client) {
        $this->clients[$client->getHash()] = $client;
    }

    public function clientIsUnique($client) {

        if (in_array($client, $this->clients)) {
            return true;
        } else {
            return false;
        }
    }

    public function hashIsUnique($hash) {

        if (array_key_exists($hash, $this->clients)) {
            return false;
        } else {
            return true;
        }
    }
}

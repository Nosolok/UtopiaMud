<?php

namespace Rottenwood\UtopiaMudBundle\Service;

/**
 * Сервис обработки введенных пользователем команд
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class NewCommandService {

    //    protected $user;
    protected $em;
    protected $kernel;
    protected $commandaction;
    protected $commandsystem;

    public function __construct() {

    }

    public function execute($command) {

        $result["message"] = "0:1"; // команда не найдена


        return $result;
    }

    /**
     * Рекурсивный поиск в массиве, используя первые X символов
     * Входные параметры: иголка, стог сена, количество символо
     * @param $needle
     * @param $haystack
     * @param $substr
     * @return bool|int|string
     */
    public function recursive_array_search_substr($needle, $haystack, $substr) {
        foreach ($haystack as $key => $value) {
            if (is_string($value)) {
                $value = mb_substr($value, 0, $substr, "utf-8");
            }
            $current_key = $key;
            if ($needle === $value OR (is_array($value) && $this->recursive_array_search_substr
                        ($needle, $value, $substr) !== false)
            ) {
                return $current_key;
            }
        }
        return false;
    }


}
<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

/**
 * Сервис обработки введенных пользователем команд
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class CommandService {

    protected $em;
    protected $kernel;
    protected $commandaction;

    public function __construct(EntityManager $em, Kernel $kernel, CommandActionService $commandaction) {
        $this->em = $em;
        $this->kernel = $kernel;
        $this->commandaction = $commandaction;
    }

    public function execute($command) {
        // парсинг файла со списком внутриигровых команд
        $path = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/config/commands.yml");
        $commands = Yaml::parse(file_get_contents($path));

        // разбитие команды на символы и их подсчет
        $count = count(preg_split('/(?<!^)(?!$)/u', $command));
        $run = $this->recursive_array_search_substr($command, $commands["commands"], $count);

        // проверка существования команды
        if ($run && method_exists($this->commandaction, $run)) {
            $commandtype = "command" . $commands["commands"][$run]["type"];
//            $result["command"] = $run;
//            $result["commandtype"] = $commandtype;
            // запуск команды
            $result = $this->{$commandtype}->$run();
        } else {
            $result["message"] = "0:1"; // команда не найдена
        }

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
                $value = mb_substr($value, 0, $substr,"utf-8");
            }
            $current_key = $key;
            if ($needle === $value OR (is_array($value) && $this->recursive_array_search_substr
                        ($needle, $value, $substr) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

}
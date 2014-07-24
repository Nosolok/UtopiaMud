<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Rottenwood\UtopiaMudBundle\Entity\Player;
use Symfony\Component\Config\Definition\Exception\Exception;
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
    protected $commandsystem;

    public function __construct(Kernel $kernel, CommandActionService $commandaction,
                                CommandSystemService $commandsystem) {
        $this->kernel = $kernel;
        $this->commandaction = $commandaction;
        $this->commandsystem = $commandsystem;
    }

    /**
     * Запуск команды и возврат результата
     * @param $command
     * @param $user     Player  Инициатор команды
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     * @return mixed
     */
    public function execute($command, Player $user) {
        $result = array();

        // парсинг файла со списком внутриигровых команд
        $path = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/config/commands.yml");
        if (!is_string($path)) {
            throw new Exception("Type of $path must be string.");
        }
        $commands = Yaml::parse(file_get_contents($path));

        // разбитие строки на команду и аргументы
        $arguments = explode(' ',trim($command));
        $command = mb_strtolower($arguments[0], 'UTF-8');
        unset($arguments[0]);

        // разбитие команды на символы и их подсчет (хак для русских символов)
        $count = count(preg_split('/(?<!^)(?!$)/u', $command));

        $run = $this->recursiveArraySearchSubstr($command, $commands["commands"], $count);
        $runLang = $this->recursiveArraySearchSubstrValue($command, $commands["commands"], $count);
        $langCommandFull = $runLang["alias"][1];

        // проверка существования команды
        if ($run && (method_exists($this->commandaction, $run) || method_exists($this->commandsystem, $run) )) {
            $commandtype = "command" . $commands["commands"][$run]["type"];

            // если тип команды служебный, проверяется ввод команды целиком
            if ($commandtype == "commandsystem" && ($command !== $langCommandFull && $command !== $run)) {
                $result["message"] = "0:5:1"; // просьба ввести команду целиком
                $result["cmd"] = $run;
                $result["cmdlang"] = $langCommandFull;
                return $result;
            }

            // если команда - "выход"
            if ($run == "quit") {
                if (!($run == $command || $command == "конец")) {
                    echo "[quit]\n";
                    $result["message"] = "0:5:1"; // просьба ввести команду целиком
                    $result["run"] = $run;
                    $result["command"] = $command;
                    return $result;
                }
            }
            // вывод в консоль полного имени команды
            echo "[$run]\n";
            // запуск команды
            $result = $this->{$commandtype}->$run($user, $arguments);
        } else {
            // вывод в консоль информации о ненайденной команде
            echo "[! command not found !]\n";
            $result["message"] = "0:1"; // команда не найдена
        }

        return $result;
    }

    /**
     * Рекурсивный поиск в массиве, используя первые X символов, возвращает ключ
     * Входные параметры: иголка, стог сена, количество символо
     * @param $needle
     * @param $haystack
     * @param integer $substr
     * @return string
     */
    public function recursiveArraySearchSubstr($needle, $haystack, $substr) {
        foreach ($haystack as $key => $value) {
            if (is_string($value)) {
                $value = mb_substr($value, 0, $substr, "utf-8");
            }
            $current_key = $key;
            if ($needle === $value || (is_array($value) && $this->recursiveArraySearchSubstr
                        ($needle, $value, $substr) !== false)
            ) {
                return $current_key;
            }
        }
        return false;
    }

    /**
     * Рекурсивный поиск в массиве, используя первые X символов, возвращает значение
     * Входные параметры: иголка, стог сена, количество символо
     * @param string $needle
     * @param $haystack
     * @param integer $substr
     * @internal param int $substr
     * @return string
     */
    public function recursiveArraySearchSubstrValue($needle, $haystack, $substr) {
        foreach ($haystack as $key => $value) {
            if (is_string($value)) {
                $value = mb_substr($value, 0, $substr, "utf-8");
            }
            $current_value = $value;
            if ($needle === $value || (is_array($value) && $this->recursiveArraySearchSubstr
                        ($needle, $value, $substr) !== false)
            ) {
                return $current_value;
            }
        }
        return false;
    }


}
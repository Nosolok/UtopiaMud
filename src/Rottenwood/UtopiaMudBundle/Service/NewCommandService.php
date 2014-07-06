<?php

namespace Rottenwood\UtopiaMudBundle\Service;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

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

    private $newCommandActionService;

    public function __construct(Kernel $kernel, NewCommandActionService $commandaction,
                                NewCommandSystemService $commandsystem) {
        $this->kernel = $kernel;
        $this->commandaction = $commandaction;
        $this->commandsystem = $commandsystem;
    }

    public function execute($command) {
        // парсинг файла со списком внутриигровых команд
        $path = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/config/commands.yml");
        $commands = Yaml::parse(file_get_contents($path));

        // разбитие команды на символы и их подсчет (хак для русских символов)
        $count = count(preg_split('/(?<!^)(?!$)/u', $command));
        $run = $this->recursive_array_search_substr($command, $commands["commands"], $count);

        // проверка существования команды
        if ($run && (method_exists($this->commandaction, $run) || method_exists($this->commandsystem, $run) )) {
//        if ($run && (method_exists($this->commandaction, $run) OR method_exists($this->commandsystem, $run))) {
            $commandtype = "command" . $commands["commands"][$run]["type"];
            // если команда - "выход"
            if ($run == "quit") {
                if (!($run == $command OR $command == "конец")) {
                    $result["message"] = "0:5:1"; // просьба ввести команду целиком
                    $result["run"] = $run;
                    $result["command"] = $command;
                    return $result;
                }
            }
            // запуск команды
            $result = $this->{$commandtype}->$run();
        } else {
            $result["message"] = "0:1"; // команда не найдена
        }

//        $result["message"] = "0:1"; // команда не найдена

//        $result = $run;

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
<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 16:05
 */

namespace Rottenwood\UtopiaMudBundle\Service;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

/**
 * Сервис служебных команд
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class CommandSystemService {

    private $kernel;

    public function __construct(Kernel $kernel) {
        $this->kernel = $kernel;
    }

    // конец (выход)
    public function quit() {
        $result["message"] = "0:5"; // выход
        return $result;
    }

    // форматирование списка зон, перевод зон из YAML в БД и наоборот
    public function format() {
        $zoneanchor = "medievaltown";

        // парсинг файла списка зон
        $path = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/zones/zonelist.yml");
        $zones = Yaml::parse(file_get_contents($path));

        // проверка наличия искомой зоны в файле списка зон
        if (array_key_exists($zoneanchor, $zones["zonelist"])) {
            // зона существует

            // парсинг выбранной зоны
            $zone = Yaml::parse(file_get_contents($this->kernel->locateResource
                ("@RottenwoodUtopiaMudBundle/Resources/zones/".$zoneanchor."/rooms.yml")));

            // создание объекта класса Зона

            // запись объекта в БД

        } else {

            // зоны не существует

        }


        $result["test"] = $zone;

        return $result;
    }

}

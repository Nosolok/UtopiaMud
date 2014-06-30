<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 16:05
 */

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Сервис служебных команд
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class CommandSystemService {

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    // конец (выход)
    public function quit() {
        $result["message"] = "0:5"; // выход
        return $result;
    }

}

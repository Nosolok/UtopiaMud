<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 16:05
 */

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;

/**
 * Сервис служебных команд
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class CommandSystemService {

    protected $em;
//    protected $kernel;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    // конец (выход)
    public function quit() {
        $result["message"] = "0:5"; // выход
        return $result;
    }

}

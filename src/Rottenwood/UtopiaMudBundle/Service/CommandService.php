<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Parser;

/**
 * Class PersonService
 * @package Rottenwood\UtopiaMudBundle\Service
 */
class CommandService {

    /**
     * Entity Manager
     * @var
     */
    protected $em;
    protected $kernel;

    /**
     * Конструктор
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, Kernel $kernel) {
        $this->em = $em;
        $this->kernel = $kernel;
    }

    public function execute($command) {
        /**
         * Путь к файлу списка игровых команд
         */
        $yaml = new Parser();
        $path = $this->kernel->locateResource("@RottenwoodUtopiaMudBundle/Resources/config/commands.yml");
        $value = $yaml->parse(file_get_contents($path));

        return $value;
    }

}
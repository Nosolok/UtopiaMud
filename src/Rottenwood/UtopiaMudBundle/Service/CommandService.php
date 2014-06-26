<?php

namespace Rottenwood\UtopiaMudBundle\Service;

use Doctrine\ORM\EntityManager;

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

    /**
     * Конструктор
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function look() {
        return 1;
    }

}
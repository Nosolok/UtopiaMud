<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Race
 * @ORM\Table(name="races")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\RaceRepository")
 */
class Race {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Мужское название расы
     * @var string
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * Женское название расы
     * @var string
     * @ORM\Column(name="namef", type="string", length=50)
     */
    private $namef;

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return Race
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set namef
     * @param string $namef
     * @return Race
     */
    public function setNamef($namef) {
        $this->namef = $namef;

        return $this;
    }

    /**
     * Get namef
     * @return string
     */
    public function getNamef() {
        return $this->namef;
    }

    public function __toString() {
        return $this->name;
    }
}

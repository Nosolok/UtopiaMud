<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mob
 * @ORM\Table(name="mobs")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Entity\MobRepository")
 */
class Mob {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Race")
     */
    private $race;

    /**
     * @var integer
     * @ORM\Column(name="sex", type="integer", length=1)
     */
    private $sex;

    /**
     * @var string
     * @ORM\Column(name="shortdesc", type="string", length=255)
     */
    private $shortdesc;

    /**
     * @var string
     * @ORM\Column(name="longdesc", type="string", length=500)
     */
    private $longdesc;

    /**
     * @var string
     * @ORM\Column(name="zone", type="string", length=255)
     */
    private $zone;

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
     * @return Mob
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
     * Set race
     * @param integer $race
     * @return Mob
     */
    public function setRace($race) {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     * @return integer
     */
    public function getRace() {
        return $this->race;
    }

    /**
     * Set sex
     * @param integer $sex
     * @return Mob
     */
    public function setSex($sex) {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     * @return integer
     */
    public function getSex() {
        return $this->sex;
    }

    /**
     * Set shortdesc
     * @param string $shortdesc
     * @return Mob
     */
    public function setShortdesc($shortdesc) {
        $this->shortdesc = $shortdesc;

        return $this;
    }

    /**
     * Get shortdesc
     * @return string
     */
    public function getShortdesc() {
        return $this->shortdesc;
    }

    /**
     * Set longdesc
     * @param string $longdesc
     * @return Mob
     */
    public function setLongdesc($longdesc) {
        $this->longdesc = $longdesc;

        return $this;
    }

    /**
     * Get longdesc
     * @return string
     */
    public function getLongdesc() {
        return $this->longdesc;
    }

    /**
     * @return string
     */
    public function getZone() {
        return $this->zone;
    }

    /**
     * @param string $zone
     */
    public function setZone($zone) {
        $this->zone = $zone;
    }
}

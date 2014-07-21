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
     * @var string
     * @ORM\Column(name="race", type="string", length=255)
     */
    private $race;

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
     * @var integer
     * @ORM\Column(name="ST", type="integer", length=2)
     */
    private $ST;

    /**
     * @var integer
     * @ORM\Column(name="DX", type="integer", length=2)
     */
    private $DX;

    /**
     * @var integer
     * @ORM\Column(name="IQ", type="integer", length=2)
     */
    private $IQ;

    /**
     * @param int $DX
     */
    public function setDX($DX) {
        $this->DX = $DX;
    }

    /**
     * @return int
     */
    public function getDX() {
        return $this->DX;
    }

    /**
     * @var integer
     * @ORM\Column(name="HT", type="integer", length=2)
     */
    private $HT;

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $HT
     */
    public function setHT($HT) {
        $this->HT = $HT;
    }

    /**
     * @return int
     */
    public function getHT() {
        return $this->HT;
    }

    /**
     * @param int $IQ
     */
    public function setIQ($IQ) {
        $this->IQ = $IQ;
    }

    /**
     * @return int
     */
    public function getIQ() {
        return $this->IQ;
    }

    /**
     * @param int $ST
     */
    public function setST($ST) {
        $this->ST = $ST;
    }

    /**
     * @return int
     */
    public function getST() {
        return $this->ST;
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

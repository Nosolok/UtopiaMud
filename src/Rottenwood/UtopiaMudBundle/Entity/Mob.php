<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mob
 * @ORM\Table(name="mobs")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\MobRepository")
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
     * @var integer
     * @ORM\Column(name="HT", type="integer", length=2)
     */
    private $HT;


    /**
     * @var string
     * @ORM\Column(name="name1", type="string", length=255)
     */
    private $name1;

    /**
     * @param string $name1
     */
    public function setName1($name1) {
        $this->name1 = $name1;
    }

    /**
     * @return string
     */
    public function getName1() {
        return $this->name1;
    }

    /**
     * @var string
     * @ORM\Column(name="name2", type="string", length=255)
     */
    private $name2;

    /**
     * @var string
     * @ORM\Column(name="name3", type="string", length=255)
     */
    private $name3;

    /**
     * @var string
     * @ORM\Column(name="name4", type="string", length=255)
     */
    private $name4;

    /**
     * @param string $name2
     */
    public function setName2($name2) {
        $this->name2 = $name2;
    }

    /**
     * @return string
     */
    public function getName2() {
        return $this->name2;
    }

    /**
     * @param string $name3
     */
    public function setName3($name3) {
        $this->name3 = $name3;
    }

    /**
     * @return string
     */
    public function getName3() {
        return $this->name3;
    }

    /**
     * @param string $name4
     */
    public function setName4($name4) {
        $this->name4 = $name4;
    }

    /**
     * @return string
     */
    public function getName4() {
        return $this->name4;
    }

    /**
     * @param string $name5
     */
    public function setName5($name5) {
        $this->name5 = $name5;
    }

    /**
     * @return string
     */
    public function getName5() {
        return $this->name5;
    }

    /**
     * @param string $name6
     */
    public function setName6($name6) {
        $this->name6 = $name6;
    }

    /**
     * @return string
     */
    public function getName6() {
        return $this->name6;
    }
    /**
     * @var string
     * @ORM\Column(name="name5", type="string", length=255)
     */
    private $name5;
    /**
     * @var string
     * @ORM\Column(name="name6", type="string", length=255)
     */
    private $name6;

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
     * @param string $race
     * @return Mob
     */
    public function setRace($race) {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     * @return string
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

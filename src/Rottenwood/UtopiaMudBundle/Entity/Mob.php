<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mob
 * @ORM\Table()
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
     * @ORM\Column(name="longdesc", type="string", length=500)
     */
    private $longdesc;

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
     * @var integer
     * @ORM\Column(name="ST", type="integer")
     */
    private $sT;

    /**
     * @var integer
     * @ORM\Column(name="IQ", type="integer")
     */
    private $iQ;

    /**
     * @var integer
     * @ORM\Column(name="DX", type="integer")
     */
    private $dX;

    /**
     * @var integer
     * @ORM\Column(name="HT", type="integer")
     */
    private $hT;

    /**
     * @var string
     * @ORM\Column(name="shortdesc", type="string", length=255)
     */
    private $shortdesc;


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
     * Set sT
     * @param integer $sT
     * @return Mob
     */
    public function setST($sT) {
        $this->sT = $sT;

        return $this;
    }

    /**
     * Get sT
     * @return integer
     */
    public function getST() {
        return $this->sT;
    }

    /**
     * Set iQ
     * @param integer $iQ
     * @return Mob
     */
    public function setIQ($iQ) {
        $this->iQ = $iQ;

        return $this;
    }

    /**
     * Get iQ
     * @return integer
     */
    public function getIQ() {
        return $this->iQ;
    }

    /**
     * Set dX
     * @param integer $dX
     * @return Mob
     */
    public function setDX($dX) {
        $this->dX = $dX;

        return $this;
    }

    /**
     * Get dX
     * @return integer
     */
    public function getDX() {
        return $this->dX;
    }

    /**
     * Set hT
     * @param integer $hT
     * @return Mob
     */
    public function setHT($hT) {
        $this->hT = $hT;

        return $this;
    }

    /**
     * Get hT
     * @return integer
     */
    public function getHT() {
        return $this->hT;
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
}

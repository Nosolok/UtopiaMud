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
     * Ключевое буквенное значение
     * @var string
     * @ORM\Column(name="anchor", type="string", length=50)
     */
    private $anchor;

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
     * @var integer
     * @ORM\Column(name="size", type="integer", length=2)
     */
    private $size;

    /**
     * @var integer
     * @ORM\Column(name="ST", type="integer", length=3)
     */
    private $ST;

    /**
     * @var integer
     * @ORM\Column(name="DX", type="integer", length=3)
     */
    private $DX;

    /**
     * @var integer
     * @ORM\Column(name="IQ", type="integer", length=3)
     */
    private $IQ;

    /**
     * @var integer
     * @ORM\Column(name="HT", type="integer", length=3)
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

    /**
     * Set anchor
     *
     * @param string $anchor
     * @return Race
     */
    public function setAnchor($anchor)
    {
        $this->anchor = $anchor;

        return $this;
    }

    /**
     * Get anchor
     *
     * @return string 
     */
    public function getAnchor()
    {
        return $this->anchor;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return Race
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set ST
     *
     * @param integer $sT
     * @return Race
     */
    public function setST($sT)
    {
        $this->ST = $sT;

        return $this;
    }

    /**
     * Get ST
     *
     * @return integer 
     */
    public function getST()
    {
        return $this->ST;
    }

    /**
     * Set DX
     *
     * @param integer $dX
     * @return Race
     */
    public function setDX($dX)
    {
        $this->DX = $dX;

        return $this;
    }

    /**
     * Get DX
     *
     * @return integer 
     */
    public function getDX()
    {
        return $this->DX;
    }

    /**
     * Set IQ
     *
     * @param integer $iQ
     * @return Race
     */
    public function setIQ($iQ)
    {
        $this->IQ = $iQ;

        return $this;
    }

    /**
     * Get IQ
     *
     * @return integer 
     */
    public function getIQ()
    {
        return $this->IQ;
    }

    /**
     * Set HT
     *
     * @param integer $hT
     * @return Race
     */
    public function setHT($hT)
    {
        $this->HT = $hT;

        return $this;
    }

    /**
     * Get HT
     *
     * @return integer 
     */
    public function getHT()
    {
        return $this->HT;
    }
}

<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 * @ORM\Table(name="items")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\ItemRepository")
 */
class Item {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="anchor", type="string", length=255)
     */
    private $anchor;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="name1", type="string", length=255)
     */
    private $name1;

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
     * @var array
     * @ORM\Column(name="extra", type="array")
     */
    private $extra = array();

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set anchor
     * @param string $anchor
     * @return Item
     */
    public function setAnchor($anchor) {
        $this->anchor = $anchor;

        return $this;
    }

    /**
     * Get anchor
     * @return string
     */
    public function getAnchor() {
        return $this->anchor;
    }

    /**
     * Set type
     * @param string $type
     * @return Item
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set name1
     * @param string $name1
     * @return Item
     */
    public function setName1($name1) {
        $this->name1 = $name1;

        return $this;
    }

    /**
     * Get name1
     * @return string
     */
    public function getName1() {
        return $this->name1;
    }

    /**
     * Set name2
     * @param string $name2
     * @return Item
     */
    public function setName2($name2) {
        $this->name2 = $name2;

        return $this;
    }

    /**
     * Get name2
     * @return string
     */
    public function getName2() {
        return $this->name2;
    }

    /**
     * Set name3
     * @param string $name3
     * @return Item
     */
    public function setName3($name3) {
        $this->name3 = $name3;

        return $this;
    }

    /**
     * Get name3
     * @return string
     */
    public function getName3() {
        return $this->name3;
    }

    /**
     * Set name4
     * @param string $name4
     * @return Item
     */
    public function setName4($name4) {
        $this->name4 = $name4;

        return $this;
    }

    /**
     * Get name4
     * @return string
     */
    public function getName4() {
        return $this->name4;
    }

    /**
     * Set name5
     * @param string $name5
     * @return Item
     */
    public function setName5($name5) {
        $this->name5 = $name5;

        return $this;
    }

    /**
     * Get name5
     * @return string
     */
    public function getName5() {
        return $this->name5;
    }

    /**
     * Set name6
     * @param string $name6
     * @return Item
     */
    public function setName6($name6) {
        $this->name6 = $name6;

        return $this;
    }

    /**
     * Get name6
     * @return string
     */
    public function getName6() {
        return $this->name6;
    }

    /**
     * Set shortdesc
     * @param string $shortdesc
     * @return Item
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
     * @return Item
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
     * @param array $extra
     */
    public function setExtra($extra) {
        $this->extra = $extra;
    }

    /**
     * @return array
     */
    public function getExtra() {
        return $this->extra;
    }

    /**
     * @param string $zone
     */
    public function setZone($zone) {
        $this->zone = $zone;
    }

    /**
     * @return string
     */
    public function getZone() {
        return $this->zone;
    }

}

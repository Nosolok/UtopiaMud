<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room
 * @ORM\Table(name="rooms")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\RoomRepository")
 */
class Room {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="zone", type="string", length=25)
     */
    private $zone;

    /**
     * @var string
     * @ORM\Column(name="anchor", type="string", length=30)
     */
    private $anchor;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=20)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="roomdesc", type="string", length=500)
     */
    private $roomdesc;

    /**
     * @var string
     * @ORM\Column(name="west", type="string", length=30, nullable=true)
     */
    private $west = "";

    /**
     * @var array
     * @ORM\Column(name="westdoor", type="array", nullable=true)
     */
    private $westdoor;

    /**
     * @var string
     * @ORM\Column(name="north", type="string", length=30, nullable=true)
     */
    private $north = "";

    /**
     * @var array
     * @ORM\Column(name="northdoor", type="array", nullable=true)
     */
    private $northdoor;

    /**
     * @var string
     * @ORM\Column(name="south", type="string", length=30, nullable=true)
     */
    private $south = "";

    /**
     * @var array
     * @ORM\Column(name="southdoor", type="array", nullable=true)
     */
    private $southdoor;

    /**
     * @var string
     * @ORM\Column(name="east", type="string", length=30, nullable=true)
     */
    private $east = "";

    /**
     * @var array
     * @ORM\Column(name="eastdoor", type="array", nullable=true)
     */
    private $eastdoor;

    /**
     * @var string
     * @ORM\Column(name="up", type="string", length=30, nullable=true)
     */
    private $up = "";

    /**
     * @var array
     * @ORM\Column(name="updoor", type="array", nullable=true)
     */
    private $updoor;

    /**
     * @var string
     * @ORM\Column(name="down", type="string", length=30, nullable=true)
     */
    private $down = "";

    /**
     * @var array
     * @ORM\Column(name="downdoor", type="array", nullable=true)
     */
    private $downdoor;

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
     * @return Room
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
     * Set roomdesc
     * @param string $roomdesc
     * @return Room
     */
    public function setRoomdesc($roomdesc) {
        $this->roomdesc = $roomdesc;

        return $this;
    }

    /**
     * Get roomdesc
     * @return string
     */
    public function getRoomdesc() {
        return $this->roomdesc;
    }

    /**
     * Set west
     * @param string $west
     * @return Room
     */
    public function setWest($west) {
        $this->west = $west;

        return $this;
    }

    /**
     * Get west
     * @return string
     */
    public function getWest() {
        return $this->west;
    }

    public function setNorth($north) {
        $this->north = $north;

        return $this;
    }

    public function getNorth() {
        return $this->north;
    }


    /**
     * Set south
     * @param string $south
     * @return Room
     */
    public function setSouth($south) {
        $this->south = $south;

        return $this;
    }

    /**
     * Get south
     * @return string
     */
    public function getSouth() {
        return $this->south;
    }

    /**
     * Set east
     * @param string $east
     * @return Room
     */
    public function setEast($east) {
        $this->east = $east;

        return $this;
    }

    /**
     * Get east
     * @return string
     */
    public function getEast() {
        return $this->east;
    }

    public function setUp($up) {
        $this->up = $up;

        return $this;
    }

    public function getUp() {
        return $this->up;
    }

    public function setDown($down) {
        $this->down = $down;

        return $this;
    }

    public function getDown() {
        return $this->down;
    }

    /**
     * Set zone
     * @param string $zone
     * @return Room
     */
    public function setZone($zone) {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     * @return string
     */
    public function getZone() {
        return $this->zone;
    }

    /**
     * Set anchor
     * @param string $anchor
     * @return Room
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
     * @return Room
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
     * @param array $downdoor
     */
    public function setDowndoor($downdoor) {
        $this->downdoor = $downdoor;
    }

    /**
     * @return array
     */
    public function getDowndoor() {
        return $this->downdoor;
    }

    /**
     * @param array $eastdoor
     */
    public function setEastdoor($eastdoor) {
        $this->eastdoor = $eastdoor;
    }

    /**
     * @return array
     */
    public function getEastdoor() {
        return $this->eastdoor;
    }

    /**
     * @param array $northdoor
     */
    public function setNorthdoor($northdoor) {
        $this->northdoor = $northdoor;
    }

    /**
     * @return array
     */
    public function getNorthdoor() {
        return $this->northdoor;
    }

    /**
     * @param array $southdoor
     */
    public function setSouthdoor($southdoor) {
        $this->southdoor = $southdoor;
    }

    /**
     * @return array
     */
    public function getSouthdoor() {
        return $this->southdoor;
    }

    /**
     * @param array $updoor
     */
    public function setUpdoor($updoor) {
        $this->updoor = $updoor;
    }

    /**
     * @return array
     */
    public function getUpdoor() {
        return $this->updoor;
    }

    /**
     * @param array $westdoor
     */
    public function setWestdoor($westdoor) {
        $this->westdoor = $westdoor;
    }

    /**
     * @return array
     */
    public function getWestdoor() {
        return $this->westdoor;
    }

}

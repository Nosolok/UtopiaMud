<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room
 * @ORM\Table(name="rooms")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Entity\RoomRepository")
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
     * @ORM\Column(name="name", type="string", length=25)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="roomdesc", type="string", length=500)
     */
    private $roomdesc;

    /**
     * @var array
     * @ORM\Column(name="west", type="simple_array")
     */
    private $west;

    /**
     * @var array
     * @ORM\Column(name="east", type="simple_array")
     */
    private $east;

    /**
     * @var array
     * @ORM\Column(name="north", type="simple_array")
     */
    private $north;

    /**
     * @var array
     * @ORM\Column(name="south", type="simple_array")
     */
    private $south;

    /**
     * @var array
     * @ORM\Column(name="up", type="simple_array")
     */
    private $up;

    /**
     * @var array
     * @ORM\Column(name="down", type="simple_array")
     */
    private $down;

    /**
     * @var array
     * @ORM\Column(name="flag", type="array")
     */
    private $flag;


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
     * @param array $west
     * @return Room
     */
    public function setWest($west) {
        $this->west = $west;

        return $this;
    }

    /**
     * Get west
     * @return array
     */
    public function getWest() {
        return $this->west;
    }

    /**
     * Set east
     * @param array $east
     * @return Room
     */
    public function setEast($east) {
        $this->east = $east;

        return $this;
    }

    /**
     * Get east
     * @return array
     */
    public function getEast() {
        return $this->east;
    }

    /**
     * Set north
     * @param array $north
     * @return Room
     */
    public function setNorth($north) {
        $this->north = $north;

        return $this;
    }

    /**
     * Get north
     * @return array
     */
    public function getNorth() {
        return $this->north;
    }

    /**
     * Set south
     * @param array $south
     * @return Room
     */
    public function setSouth($south) {
        $this->south = $south;

        return $this;
    }

    /**
     * Get south
     * @return array
     */
    public function getSouth() {
        return $this->south;
    }

    /**
     * Set up
     * @param array $up
     * @return Room
     */
    public function setUp($up) {
        $this->up = $up;

        return $this;
    }

    /**
     * Get up
     * @return array
     */
    public function getUp() {
        return $this->up;
    }

    /**
     * Set down
     * @param array $down
     * @return Room
     */
    public function setDown($down) {
        $this->down = $down;

        return $this;
    }

    /**
     * Get down
     * @return array
     */
    public function getDown() {
        return $this->down;
    }

    /**
     * Set flag
     * @param array $flag
     * @return Room
     */
    public function setFlag($flag) {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     * @return array
     */
    public function getFlag() {
        return $this->flag;
    }
}

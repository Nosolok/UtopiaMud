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
     * @var integer
     * @ORM\Column(name="type", type="integer", length=3)
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
     * @var integer
     * @ORM\Column(name="west", type="integer")
     */
    private $west;

    /**
     * @var integer
     * @ORM\Column(name="north", type="integer")
     */
    private $north;

    /**
     * @var integer
     * @ORM\Column(name="south", type="integer")
     */
    private $south;

    /**
     * @var integer
     * @ORM\Column(name="east", type="integer")
     */
    private $east;

    /**
     * @var integer
     * @ORM\Column(name="up", type="integer")
     */
    private $up;

    /**
     * @var integer
     * @ORM\Column(name="down", type="integer")
     */
    private $down;

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
     * @param integer $west
     * @return Room
     */
    public function setWest($west) {
        $this->west = $west;

        return $this;
    }

    /**
     * Get west
     * @return integer
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
     *
     * @param integer $south
     * @return Room
     */
    public function setSouth($south)
    {
        $this->south = $south;

        return $this;
    }

    /**
     * Get south
     *
     * @return integer 
     */
    public function getSouth()
    {
        return $this->south;
    }

    /**
     * Set east
     *
     * @param integer $east
     * @return Room
     */
    public function setEast($east)
    {
        $this->east = $east;

        return $this;
    }

    /**
     * Get east
     *
     * @return integer 
     */
    public function getEast()
    {
        return $this->east;
    }

    public function setUp($up)
    {
        $this->up = $up;

        return $this;
    }

    public function getUp()
    {
        return $this->up;
    }

    public function setDown($down)
    {
        $this->down = $down;

        return $this;
    }

    public function getDown()
    {
        return $this->down;
    }

    /**
     * Set zone
     *
     * @param string $zone
     * @return Room
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return string 
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set anchor
     *
     * @param string $anchor
     * @return Room
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
     * Set type
     *
     * @param integer $type
     * @return Room
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }
}

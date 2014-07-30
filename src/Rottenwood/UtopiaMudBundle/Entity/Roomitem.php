<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Roomitem
 * @ORM\Table(name="roomitems")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\RoomitemRepository")
 */
class Roomitem {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(name="item")
     **/
    private $item;

    /**
     * @ORM\ManyToOne(targetEntity="Room")
     * @ORM\JoinColumn(onDelete="SET NULL")
     **/
    private $room;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set item
     * @param Item $item
     * @return Roomitem
     */
    public function setItem($item) {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     * @return Item
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * Set room
     * @param Room $room
     * @return Roomitem
     */
    public function setRoom($room) {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     * @return Room
     */
    public function getRoom() {
        return $this->room;
    }
}

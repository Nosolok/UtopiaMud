<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inventory
 * @ORM\Table(name="inventories")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\InventoryRepository")
 */
class Inventory {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="player")
     **/
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(name="item")
     **/
    private $item;

    /**
     * @var string
     * @ORM\Column(name="wear", type="string", nullable=true)
     */
    private $wear;

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set player
     * @param Player $player
     * @return Inventory
     */
    public function setPlayer($player) {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     * @return Player
     */
    public function getPlayer() {
        return $this->player;
    }

    /**
     * Set item
     * @param Item $item
     * @return Inventory
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
     * @param string $wear
     */
    public function setWear($wear) {
        $this->wear = $wear;
    }

    /**
     * @return string
     */
    public function getWear() {
        return $this->wear;
    }
}

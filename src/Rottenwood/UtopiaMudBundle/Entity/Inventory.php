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
     * @ORM\JoinColumn(onDelete="SET NULL")
     **/
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(onDelete="SET NULL")
     **/
    private $item;

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
     * @param mixed $itemInContainer
     */
    public function setItemInContainer($itemInContainer) {
        $this->itemInContainer = $itemInContainer;
    }

    /**
     * @return mixed
     */
    public function getItemInContainer() {
        return $this->itemInContainer;
    }


}

<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mobitem
 * @ORM\Table(name="mobitems")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\MobitemRepository")
 */
class Mobitem {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="item", type="string", length=255)
     */
    private $item;

    /**
     * @var string
     * @ORM\Column(name="mob", type="string", length=255)
     */
    private $mob;

    /**
     * @var string
     * @ORM\Column(name="slot", type="string", length=255)
     */
    private $slot;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set item
     * @param string $item
     * @return Mobitem
     */
    public function setItem($item) {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     * @return string
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * Set mob
     * @param string $mob
     * @return Mobitem
     */
    public function setMob($mob) {
        $this->mob = $mob;

        return $this;
    }

    /**
     * Get mob
     * @return string
     */
    public function getMob() {
        return $this->mob;
    }

    /**
     * Set slot
     * @param string $slot
     * @return Mobitem
     */
    public function setSlot($slot) {
        $this->slot = $slot;

        return $this;
    }

    /**
     * Get slot
     * @return string
     */
    public function getSlot() {
        return $this->slot;
    }
}

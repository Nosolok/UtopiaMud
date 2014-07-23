<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livemob
 * @ORM\Table(name="livemobs")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\LivemobRepository")
 */
class Livemob {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Room")
     * @ORM\JoinColumn(onDelete="SET NULL")
     **/
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity="Mob")
     * @ORM\JoinColumn(onDelete="SET NULL")
     **/
    private $mob;

    /**
     * @var integer
     * @ORM\Column(name="hp", type="integer")
     */
    private $hp;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set hp
     * @param integer $hp
     * @return Livemobs
     */
    public function setHp($hp) {
        $this->hp = $hp;

        return $this;
    }

    /**
     * Get hp
     * @return integer
     */
    public function getHp() {
        return $this->hp;
    }

    /**
     * @param mixed $mob
     */
    public function setMob($mob) {
        $this->mob = $mob;
    }

    /**
     * @return mixed
     */
    public function getMob() {
        return $this->mob;
    }

    /**
     * @param mixed $room
     */
    public function setRoom($room) {
        $this->room = $room;
    }

    /**
     * @return mixed
     */
    public function getRoom() {
        return $this->room;
    }
}

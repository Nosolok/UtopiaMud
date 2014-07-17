<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 1:52
 */
namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="players")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\PlayerRepository")
 */
class Player extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Раса персонажа
     * @ORM\ManyToOne(targetEntity="Race")
     */
    private $race;

    /**
     * Пол персонажа
     * @var integer
     * @ORM\Column(name="sex", type="integer", length=1)
     */
    private $sex;

    /**
     * Описание персонажа
     * @var string
     * @ORM\Column(name="long_desc", type="string", length=500)
     */
    private $longDesc = "Ничем не примечательная личность.";

    /**
     * Последний токен персонажа
     * @var string
     * @ORM\Column(name="hash", type="string", length=26, nullable=true)
     */
    private $hash;

    /**
     * Комната в которой находится персонаж
     * @ORM\ManyToOne(targetEntity="Room")
     * @ORM\JoinColumn(name="room", referencedColumnName="id")
     */
    private $room;

    /**
     * @param string $longDesc
     */
    public function setLongDesc($longDesc) {
        $this->longDesc = $longDesc;
    }

    /**
     * @return string
     */
    public function getLongDesc() {
        return $this->longDesc;
    }

    public function getRace() {
        return $this->race;
    }

    public function setRace($race) {
        $this->race = $race;

        return $this;
    }

    public function getRoom() {
        return $this->room;
    }

    public function setRoom($room) {
        $this->room = $room;

        return $this;
    }

    public function getSex() {
        return $this->sex;
    }

    public function setSex($sex) {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set hash
     * @param string $hash
     * @return Player
     */
    public function setHash($hash) {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     * @return string
     */
    public function getHash() {
        return $this->hash;
    }
}

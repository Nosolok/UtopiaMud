<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 1:52
 */
namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="players")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\PlayerRepository")
 */
class Player extends BaseUser {

    public function __construct() {
        parent::__construct();
        // your own logic
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Race")
     */
    private $race;

    /**
     * @var integer
     * @ORM\Column(name="sex", type="integer", length=1)
     */
    private $sex;

    /**
     * @var integer
     * @ORM\Column(name="room", type="integer", length=255, nullable=true)
     */
    private $room;


    public function getRace() {
        return $this->race;
    }

    public function setRace($race) {
        $this->race = $race;

        return $this;
    }

    public function getRaces() {
        return $this->races;
    }

    public function setRaces($races) {
        $this->races = $races;

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
}
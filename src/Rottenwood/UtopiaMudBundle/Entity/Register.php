<?php
/**
 * Created by PhpStorm.
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
 * @ORM\Table(name="register")
 */
class Register extends BaseUser {

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
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Пожалуйста, введите свое имя.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="15",
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $name;
}
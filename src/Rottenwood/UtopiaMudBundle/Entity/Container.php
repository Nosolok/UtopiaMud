<?php

namespace Rottenwood\UtopiaMudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Container
 * @ORM\Table(name="containers")
 * @ORM\Entity(repositoryClass="Rottenwood\UtopiaMudBundle\Repository\ContainerRepository")
 */
class Container {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(onDelete="SET NULL")
     **/
    private $item;

    /**
     * @ORM\ManyToOne(targetEntity="Inventory")
     * @ORM\JoinColumn(onDelete="SET NULL")
     **/
    private $container;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set container
     * @param string $container
     * @return Container
     */
    public function setContainer($container) {
        $this->container = $container;

        return $this;
    }

    /**
     * Get container
     * @return Inventory
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * Set item
     * @param string $item
     * @return Container
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
}

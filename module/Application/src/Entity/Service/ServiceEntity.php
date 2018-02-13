<?php

namespace Application\Entity\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Services")
 */
class ServiceEntity {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(
     *      name="id",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $id;

    /**
     * @ORM\Column(
     *      name="price",
     *      type="float",
     *      nullable=false,
     *      options={"unsigned":true}
     * )
     */
    protected $price;

    /**
     * @ORM\OneToMany(
     *      targetEntity="ServiceTranslationEntity",
     *      mappedBy="service",
     *      indexBy="localeId",
     *      fetch="EXTRA_LAZY",
     *      orphanRemoval=true,
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"localeId"="ASC"})
     */
    protected $translations;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="Application\Entity\Room\RoomEntity",
     *      mappedBy="services",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY"
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $rooms;

    public function __construct() {
        $this->translations = new ArrayCollection();
        $this->rooms = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function getRooms() {
        return $this->rooms;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setTranslations($translations) {
        $this->translations = $translations;
    }

    public function setRooms($rooms) {
        $this->rooms = $rooms;
    }

}

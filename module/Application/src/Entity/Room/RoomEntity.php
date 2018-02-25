<?php

namespace Application\Entity\Room;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Rooms")
 */
class RoomEntity {

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
     *      targetEntity="RoomTranslationEntity",
     *      mappedBy="room",
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
     *      targetEntity="Application\Entity\Picture\PictureEntity",
     *      inversedBy="rooms",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY",
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     * @ORM\JoinTable(
     *      name="RoomPictureRelations",
     *      joinColumns={@ORM\JoinColumn(
     *           name="roomId",
     *           referencedColumnName="id"
     * )},
     *      inverseJoinColumns={@ORM\JoinColumn(
     *           name="pictureId",
     *           referencedColumnName="id"
     * )}
     * )
     */
    protected $pictures;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="Application\Entity\Service\ServiceEntity",
     *      inversedBy="rooms",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY",
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\JoinTable(
     *      name="RoomServiceRelations",
     *      joinColumns={@ORM\JoinColumn(
     *           name="roomId",
     *           referencedColumnName="id"
     * )},
     *      inverseJoinColumns={@ORM\JoinColumn(
     *           name="serviceId",
     *           referencedColumnName="id"
     * )}
     * )
     */
    protected $services;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Application\Entity\Reservation\ReservationEntity",
     *      mappedBy="reservation",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY",
     *      orphanRemoval=true,
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $reservations;

    public function __construct() {
        $this->translations = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->reservations = new ArrayCollection();
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

    public function getPictures() {
        return $this->pictures;
    }

    public function getServices() {
        return $this->services;
    }

    public function getReservations() {
        return $this->reservations;
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

    public function setPictures($pictures) {
        $this->pictures = $pictures;
    }

    public function setServices($services) {
        $this->services = $services;
    }

    public function setReservations($reservations) {
        $this->reservations = $reservations;
    }

}

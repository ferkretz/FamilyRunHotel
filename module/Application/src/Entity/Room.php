<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Room")
 */
class Room {

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
     * @ORM\Column(
     *      name="currency",
     *      type="string",
     *      length=20,
     *      nullable=false
     * )
     */
    protected $currency;

    /**
     * @ORM\Column(
     *      name="summary",
     *      type="string",
     *      length=60,
     *      nullable=false
     * )
     */
    protected $summary;

    /**
     * @ORM\Column(
     *      name="description",
     *      type="text"
     * )
     */
    protected $description;

    /**
     * @ORM\OneToMany(
     *      targetEntity="RoomTranslation",
     *      mappedBy="room",
     *      indexBy="locale",
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"locale"="ASC"})
     */
    protected $translations;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="RoomService",
     *      inversedBy="rooms"
     * )
     * @ORM\JoinTable(
     *      name="RoomServiceRelations",
     *      joinColumns={@ORM\JoinColumn(
     *           name="roomId",
     *           referencedColumnName="id"
     * )},
     *      inverseJoinColumns={@ORM\JoinColumn(
     *           name="roomServiceId",
     *           referencedColumnName="id"
     * )}
     * )
     */
    protected $services;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="Picture",
     *      inversedBy="rooms"
     * )
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

    public function __construct() {
        $this->translations = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getTranslation($locale) {
        return $this->translations[$locale];
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function getServices() {
        return $this->services;
    }

    public function getPictures() {
        return $this->pictures;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setTranslation(RoomTranslation $translation) {
        $this->translations[$translation->getLocale()] = $translation;
    }

    public function setTranslations(ArrayCollection $translations) {
        $this->translations = $translations;
    }

    public function addRoomService(RoomService $roomService) {
        $this->roomServices[] = $roomService;
    }

    public function setRoomServices(ArrayCollection $roomServices) {
        $this->roomServices = $roomServices;
    }

    public function addPicture(Picture $picture) {
        $this->pictures[] = $picture;
    }

    public function setPictures(ArrayCollection $pictures) {
        $this->pictures = $pictures;
    }

}

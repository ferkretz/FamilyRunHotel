<?php

namespace Administration\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Rooms")
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
     * @ORM\Column(
     *      name="transCount",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $transCount;

    /**
     * @ORM\OneToMany(
     *      targetEntity="RoomTranslation",
     *      mappedBy="room",
     *      indexBy="locale",
     *      fetch="EXTRA_LAZY",
     *      orphanRemoval=true,
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
        $this->transCount = 0;
    }

    public function getData($locale = FALSE) {
        $data = get_object_vars($this);

        if ($this->translations[$locale]) {
            $data = array_merge($data, $this->translations[$locale]->getData());
        }

        return $data;
    }

    public function setData($data) {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['price'])) {
            $this->price = $data['price'];
        }
        if (isset($data['currency'])) {
            $this->currency = $data['currency'];
        }
        if (isset($data['summary'])) {
            $this->summary = $data['summary'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['translationSummary'])) {
            $translation = $this->getTranslation($data['translationLocale'], TRUE);
            $translation->setData($data);
        }
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

    public function getTransCount() {
        return $this->transCount;
    }

    public function getTranslation($locale,
                                   $create = FALSE) {
        if (!isset($this->translations[$locale])) {
            return $create ? $this->createTranslation($locale) : FALSE;
        }

        return $this->translations[$locale];
    }

    public function createTranslation($locale) {
        if (isset($this->translations[$locale])) {
            return FALSE;
        }

        $translation = new RoomServiceTranslation();
        $translation->setRoomService($this);
        $this->translations[$locale] = $translation;
        $this->transCount = $this->translations->count();

        return $translation;
    }

    public function removeTranslation($locale) {
        if (!isset($this->translations[$locale])) {
            return FALSE;
        }

        $this->translations->remove($locale);
        $this->transCount = $this->translations->count();

        return TRUE;
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

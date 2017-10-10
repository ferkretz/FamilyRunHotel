<?php

namespace Administration\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="RoomServices")
 */
class RoomService {

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
     *      targetEntity="RoomServiceTranslation",
     *      mappedBy="roomService",
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
     *      targetEntity="Room",
     *      inversedBy="services"
     * )
     */
    protected $rooms;

    public function __construct() {
        $this->translations = new ArrayCollection();
        $this->rooms = new ArrayCollection();
        $this->transCount = 0;
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

    public function getTranslation($locale) {
        return $this->translations->get($locale);
    }

    public function setTranslation($locale,
                                   RoomServiceTranslation $translation) {
        $newTranslation = $this->translations->get($locale);

        if (!$newTranslation) {
            $newTranslation = new RoomServiceTranslation();
            $newTranslation->setRoom($this);
            $newTranslation->setLocale($locale);
            $this->translations->set($locale, $newTranslation);
            $this->transCount = $this->translations->count();
        }

        $newTranslation->setSummary($translation->getSummary());
        $newTranslation->setDescription($translation->getDescription());
    }

    public function removeTranslation($locale) {
        if (isset($this->translations[$locale])) {
            unset($this->translations[$locale]);
            $this->transCount = $this->translations->count();
        }
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function getRooms() {
        return $this->rooms;
    }

    public function addRoom(Room $room) {
        $this->rooms[] = $room;
    }

    public function setRooms(ArrayCollection $rooms) {
        $this->rooms = $rooms;
    }

}

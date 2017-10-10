<?php

namespace Administration\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Pictures")
 */
class Picture {

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
     *      name="uploaded",
     *      type="datetime",
     *      nullable=false
     * )
     */
    protected $uploaded;

    /**
     * @ORM\Column(
     *      name="license",
     *      type="string",
     *      length=60,
     *      nullable=false
     * )
     */
    protected $license;

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
     *      name="transCount",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $transCount;

    /**
     * @ORM\OneToMany(
     *      targetEntity="PictureTranslation",
     *      mappedBy="picture",
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
     *      inversedBy="pictures"
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

    public function getUploaded() {
        return $this->uploaded;
    }

    public function getLicense() {
        return $this->license;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function getRooms() {
        return $this->rooms;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUploaded($uploaded) {
        $this->uploaded = $uploaded;
    }

    public function setLicense($license) {
        $this->license = $license;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function getTransCount() {
        return $this->transCount;
    }

    public function getTranslation($locale) {
        return $this->translations->get($locale);
    }

    public function setTranslation($locale,
                                   PictureTranslation $translation) {
        $newTranslation = $this->translations->get($locale);

        if (!$newTranslation) {
            $newTranslation = new PictureTranslation();
            $newTranslation->setPicture($this);
            $newTranslation->setLocale($locale);
            $this->translations->set($locale, $newTranslation);
            $this->transCount = $this->translations->count();
        }

        $newTranslation->setSummary($translation->getSummary());
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

    public function addRoom(Room $room) {
        $this->rooms[] = $room;
    }

    public function setRooms(ArrayCollection $rooms) {
        $this->rooms = $rooms;
    }

}

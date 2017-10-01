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
     *      name="filename",
     *      type="string",
     *      length=60,
     *      nullable=false
     * )
     */
    protected $filename;

    /**
     * @ORM\OneToMany(
     *      targetEntity="PictureTranslation",
     *      mappedBy="picture",
     *      indexBy="locale",
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

    public function getFilename() {
        return $this->filename;
    }

    public function getTranslation($locale) {
        return $this->translations[$locale];
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

    public function setUploaded($uploaded) {
        $this->uploaded = $uploaded;
    }

    public function setLicense($license) {
        $this->license = $license;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function setFilename($filename) {
        $this->filename = $filename;
    }

    public function setTranslation(PictureTranslation $translation) {
        $this->translations[$translation->getLocale()] = $translation;
    }

    public function setTranslations(ArrayCollection $translations) {
        $this->translations = $translations;
    }

    public function addRoom(Room $room) {
        $this->rooms[] = $room;
    }

    public function setRooms(ArrayCollection $rooms) {
        $this->rooms = $rooms;
    }

}

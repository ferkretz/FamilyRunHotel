<?php

namespace Application\Entity\Room;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="RoomTranslations")
 */
class RoomTranslationEntity {

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
     *      name="roomId",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $roomId;

    /**
     * @ORM\Column(
     *      name="localeId",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $localeId;

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
     * @ORM\ManyToOne(
     *      targetEntity="RoomEntity",
     *      inversedBy="translations",
     *      fetch="LAZY",
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\JoinColumn(
     *      name="roomId",
     *      referencedColumnName="id",
     *      nullable=false
     * ),
     */
    protected $room;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Application\Entity\Locale\LocaleEntity",
     *      fetch="LAZY"
     * )
     * @ORM\JoinColumn(
     *      name="localeId",
     *      referencedColumnName="id",
     *      nullable=false
     * ),
     */
    protected $locale;

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getRoomId() {
        return $this->roomId;
    }

    public function getLocaleId() {
        return $this->localeId;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getRoom() {
        return $this->room;
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setRoomId($roomId) {
        $this->roomId = $roomId;
    }

    public function setLocaleId($localeId) {
        $this->localeId = $localeId;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setRoom($room) {
        $this->room = $room;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

}

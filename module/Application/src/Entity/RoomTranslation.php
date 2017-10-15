<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="RoomTranslations")
 */
class RoomTranslation {

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
     *      name="locale",
     *      type="string",
     *      length=5,
     *      nullable=false
     * )
     */
    protected $locale;

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
     *      targetEntity="Room",
     *      inversedBy="translations",
     * )
     * @ORM\JoinColumn(
     *      name="roomId",
     *      referencedColumnName="id",
     *      nullable=false
     * ),
     */
    protected $room;

    public function getId() {
        return $this->id;
    }

    public function getLocale() {
        return $this->locale;
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

    public function setId($id) {
        $this->id = $id;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setRoom(Room $room) {
        $this->room = $room;
    }

}
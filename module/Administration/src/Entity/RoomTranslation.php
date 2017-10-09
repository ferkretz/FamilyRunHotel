<?php

namespace Administration\Entity;

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
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\JoinColumn(
     *      name="roomId",
     *      referencedColumnName="id",
     *      nullable=false
     * ),
     */
    protected $room;

    public function getData() {
        $data['translationId'] = $this->id;
        $data['translationLocale'] = $this->locale;
        $data['translationSummary'] = $this->summary;
        $data['translationDescription'] = $this->description;

        return $data;
    }

    public function setData($data) {
        if (isset($data['translationId'])) {
            $this->id = $data['translationId'];
        }
        if (isset($data['translationLocale'])) {
            $this->locale = $data['translationLocale'];
        }
        if (isset($data['translationSummary'])) {
            $this->summary = $data['translationSummary'];
        }
        if (isset($data['translationDescription'])) {
            $this->description = $data['translationDescription'];
        }
    }

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

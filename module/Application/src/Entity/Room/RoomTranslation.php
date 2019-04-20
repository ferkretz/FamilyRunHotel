<?php

namespace Application\Entity\Room;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="RoomTranslations",
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"roomId", "locale"})}
 * )
 */
class RoomTranslation {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(
     *      name="id",
     *      type="integer",
     *      nullable=false
     * )
     */
    protected $id;

    /**
     * @ORM\Column(
     *      name="roomId",
     *      type="integer",
     *      nullable=false
     * )
     */
    protected $roomId;

    /**
     * @ORM\Column(
     *      name="locale",
     *      type="string",
     *      length=20,
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
     *      type="text",
     *      nullable=true
     * )
     */
    protected $description;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Application\Entity\Room\Room",
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

    public function __construct() {
        
    }

    public function get(string $id) {
        switch ($id) {
            case 'id':
                return $this->getId();
            case 'roomId':
                return $this->getServiceId();
            case 'locale':
                return $this->getLocale();
            case 'summary':
                return $this->getSummary();
            case 'description':
                return $this->getDescription();
        }

        $idList = explode('.', $id);
        switch ($idList[0]) {
            case 'room':
                return $this->room->get($idList[1]);
        }

        return null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getRoomId(): int {
        return $this->roomId;
    }

    public function getLocale(): string {
        return $this->locale;
    }

    public function getSummary(): string {
        return $this->summary;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getRoom(): Room {
        return $this->room;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setRoomId(int $roomId) {
        $this->roomId = $roomId;
    }

    public function setLocale(string $locale) {
        $this->locale = $locale;
    }

    public function setSummary(string $summary) {
        $this->summary = $summary;
    }

    public function setDescription(?string $description) {
        $this->description = $description;
    }

    public function setRoom(Room $room) {
        $this->room = $room;
    }

}

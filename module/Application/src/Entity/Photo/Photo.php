<?php

namespace Application\Entity\Photo;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Photos")
 */
class Photo {

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
     *      name="uploaded",
     *      type="datetime",
     *      nullable=false
     * )
     */
    protected $uploaded;

    /**
     * @ORM\Column(
     *      name="content",
     *      type="blob",
     *      nullable=true
     * )
     */
    protected $content;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="Application\Entity\Room\Room",
     *      mappedBy="photos",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY"
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $rooms;

    public function __construct() {
        $this->rooms = new ArrayCollection();
    }

    public function get(string $id) {
        switch ($id) {
            case 'id':
                return $this->getId();
            case 'uploaded':
                return $this->getUploaded();
            case 'content':
                return $this->getContent();
        }

        $idList = explode('.', $id);
        switch ($idList[0]) {
            case 'rooms':
                return $this->rooms->containsKey($idList[1]) ? $this->rooms->get($idList[1]) : null;
            case 'room':
                return $this->rooms->containsKey($idList[1]) ? $this->rooms->get($idList[1])->get($idList[2]) : null;
        }

        return null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUploaded(): \DateTime {
        return $this->uploaded;
    }

    public function getContent() {
        return $this->content;
    }

    public function getRooms() {
        return $this->rooms;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setUploaded(\DateTime $uploaded) {
        $this->uploaded = $uploaded;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setRooms(ArrayCollection $rooms) {
        $this->rooms = $rooms;
    }

}

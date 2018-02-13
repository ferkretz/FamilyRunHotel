<?php

namespace Application\Entity\Picture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Pictures")
 */
class PictureEntity {

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
     *      name="content",
     *      type="blob",
     *      nullable=false
     * )
     */
    protected $content;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="Application\Entity\Room\RoomEntity",
     *      mappedBy="pictures",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY"
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $rooms;

    public function __construct() {
        $this->rooms = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getUploaded() {
        return $this->uploaded;
    }

    public function getContent() {
        return $this->content;
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

    public function setContent($content) {
        $this->content = $content;
    }

    public function setRooms($rooms) {
        $this->rooms = $rooms;
    }

}

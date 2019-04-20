<?php

namespace Application\Entity\Room;

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
     *      nullable=false
     * )
     */
    protected $id;

    /**
     * @ORM\Column(
     *      name="price",
     *      type="float",
     *      nullable=false
     * )
     */
    protected $price;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Application\Entity\Room\RoomTranslation",
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
     *      targetEntity="Application\Entity\Photo\Photo",
     *      inversedBy="rooms",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY",
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     * @ORM\JoinTable(
     *      name="RoomPhotoRelations",
     *      joinColumns={@ORM\JoinColumn(
     *           name="roomId",
     *           referencedColumnName="id"
     * )},
     *      inverseJoinColumns={@ORM\JoinColumn(
     *           name="photoId",
     *           referencedColumnName="id"
     * )}
     * )
     */
    protected $photos;

    /**
     * @ORM\ManyToMany(
     *      targetEntity="Application\Entity\Service\Service",
     *      inversedBy="rooms",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY",
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\JoinTable(
     *      name="RoomServiceRelations",
     *      joinColumns={@ORM\JoinColumn(
     *           name="roomId",
     *           referencedColumnName="id"
     * )},
     *      inverseJoinColumns={@ORM\JoinColumn(
     *           name="serviceId",
     *           referencedColumnName="id"
     * )}
     * )
     */
    protected $services;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Application\Entity\Reservation\Reservation",
     *      mappedBy="reservation",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY",
     *      orphanRemoval=true,
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $reservations;

    public function __construct() {
        $this->translations = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function get(string $id) {
        switch ($id) {
            case 'id':
                return $this->getId();
            case 'price':
                return $this->getPrice();
        }

        $idList = explode('.', $id);
        switch ($idList[0]) {
            case 'translations':
                return $this->translations->containsKey($idList[1]) ? $this->translations->get($idList[1]) : null;
            case 'photos':
                return $this->photos->containsKey($idList[1]) ? $this->photos->get($idList[1]) : null;
            case 'services':
                return $this->services->containsKey($idList[1]) ? $this->services->get($idList[1]) : null;
            case 'reservations':
                return $this->reservations->containsKey($idList[1]) ? $this->reservations->get($idList[1]) : null;
            case 'translation':
                return $this->translations->containsKey($idList[1]) ? $this->translations->get($idList[1])->get($idList[2]) : null;
            case 'photo':
                return $this->photos->containsKey($idList[1]) ? $this->photos->get($idList[1])->get($idList[2]) : null;
            case 'service':
                return $this->services->containsKey($idList[1]) ? $this->services->get($idList[1])->get($idList[2]) : null;
            case 'reservation':
                return $this->reservations->containsKey($idList[1]) ? $this->reservations->get($idList[1])->get($idList[2]) : null;
        }

        return null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function getPhotos() {
        return $this->photos;
    }

    public function getServices() {
        return $this->services;
    }

    public function getReservations() {
        return $this->reservations;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setPrice(float $price) {
        $this->price = $price;
    }

    public function setTranslations(ArrayCollection $translations) {
        $this->translations = $translations;
    }

    public function setPhotos(ArrayCollection $photos) {
        $this->photos = $photos;
    }

    public function setServices(ArrayCollection $services) {
        $this->services = $services;
    }

    public function setReservations(ArrayCollection $reservations) {
        $this->reservations = $reservations;
    }

}

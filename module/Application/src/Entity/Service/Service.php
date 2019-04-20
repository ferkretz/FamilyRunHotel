<?php

namespace Application\Entity\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Services")
 */
class Service {

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
     *      targetEntity="Application\Entity\Service\ServiceTranslation",
     *      mappedBy="service",
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
     *      targetEntity="Application\Entity\Room\Room",
     *      mappedBy="services",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY"
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $rooms;

    public function __construct() {
        $this->translations = new ArrayCollection();
        $this->rooms = new ArrayCollection();
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
            case 'rooms':
                return $this->rooms->containsKey($idList[1]) ? $this->rooms->get($idList[1]) : null;
            case 'translation':
                return $this->translations->containsKey($idList[1]) ? $this->translations->get($idList[1])->get($idList[2]) : null;
            case 'room':
                return $this->rooms->containsKey($idList[1]) ? $this->rooms->get($idList[1])->get($idList[2]) : null;
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

    public function getRooms() {
        return $this->rooms;
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

    public function setRooms(ArrayCollection $rooms) {
        $this->rooms = $rooms;
    }

}

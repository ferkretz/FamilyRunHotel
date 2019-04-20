<?php

namespace Application\Entity\Point;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Points")
 */
class Point {

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
     *      name="latitude",
     *      type="float",
     *      nullable=false
     * )
     */
    protected $latitude;

    /**
     * @ORM\Column(
     *      name="longitude",
     *      type="float",
     *      nullable=false
     * )
     */
    protected $longitude;

    /**
     * @ORM\Column(
     *      name="icon",
     *      type="string",
     *      length=2,
     *      nullable=false
     * )
     */
    protected $icon;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Application\Entity\Point\PointTranslation",
     *      mappedBy="point",
     *      indexBy="locale",
     *      fetch="EXTRA_LAZY",
     *      orphanRemoval=true,
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"locale"="ASC"})
     */
    protected $translations;

    public function __construct() {
        $this->translations = new ArrayCollection();
    }

    public function get(string $id) {
        switch ($id) {
            case 'id':
                return $this->getId();
            case 'latitude':
                return $this->getLatitude();
            case 'longitude':
                return $this->getLongitude();
            case 'icon':
                return $this->getIcon();
        }

        $idList = explode('.', $id);
        switch ($idList[0]) {
            case 'translations':
                return $this->translations->containsKey($idList[1]) ? $this->translations->get($idList[1]) : null;
            case 'translation':
                return $this->translations->containsKey($idList[1]) ? $this->translations->get($idList[1])->get($idList[2]) : null;
        }

        return null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getLatitude(): float {
        return $this->latitude;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }

    public function getIcon(): string {
        return $this->icon;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setLatitude(float $latitude) {
        $this->latitude = $latitude;
    }

    public function setLongitude(float $longitude) {
        $this->longitude = $longitude;
    }

    public function setIcon(string $icon) {
        $this->icon = $icon;
    }

    public function setTranslations(ArrayCollection $translations) {
        $this->translations = $translations;
    }

}

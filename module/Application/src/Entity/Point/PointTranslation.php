<?php

namespace Application\Entity\Point;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="PointTranslations",
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"pointId", "locale"})}
 * )
 */
class PointTranslation {

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
     *      name="pointId",
     *      type="integer",
     *      nullable=false
     * )
     */
    protected $pointId;

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
     *      targetEntity="Application\Entity\Point\Point",
     *      inversedBy="translations",
     *      fetch="LAZY",
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\JoinColumn(
     *      name="pointId",
     *      referencedColumnName="id",
     *      nullable=false
     * ),
     */
    protected $point;

    public function __construct() {
        
    }

    public function get(string $id) {
        switch ($id) {
            case 'id':
                return $this->getId();
            case 'pointId':
                return $this->getPointId();
            case 'locale':
                return $this->getLocale();
            case 'summary':
                return $this->getSummary();
            case 'description':
                return $this->getDescription();
        }

        $idList = explode('.', $id);
        switch ($idList[0]) {
            case 'point':
                return $this->point->get($idList[1]);
        }

        return null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getPointId(): int {
        return $this->pointId;
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

    public function getPoint(): Point {
        return $this->point;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setPointId(int $pointId) {
        $this->pointId = $pointId;
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

    public function setPoint(Point $point) {
        $this->point = $point;
    }

}

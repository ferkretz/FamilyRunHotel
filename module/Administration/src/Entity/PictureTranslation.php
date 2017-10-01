<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="PictureTranslations")
 */
class PictureTranslation {

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
     * @ORM\ManyToOne(
     *      targetEntity="Picture",
     *      inversedBy="translations",
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\JoinColumn(
     *      name="pictureId",
     *      referencedColumnName="id",
     *      nullable=false
     * ),
     */
    protected $picture;

    public function getId() {
        return $this->id;
    }

    public function getLocale() {
        return $this->locale;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function getPicture() {
        return $this->picture;
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

    public function setPicture(Picture $picture) {
        $this->picture = $picture;
    }

}

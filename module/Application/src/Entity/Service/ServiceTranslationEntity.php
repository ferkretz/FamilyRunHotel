<?php

namespace Application\Entity\Service;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ServiceTranslations")
 */
class ServiceTranslationEntity {

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
     *      name="serviceId",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $serviceId;

    /**
     * @ORM\Column(
     *      name="localeId",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $localeId;

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
     *      targetEntity="ServiceEntity",
     *      inversedBy="translations",
     *      fetch="LAZY",
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\JoinColumn(
     *      name="serviceId",
     *      referencedColumnName="id",
     *      nullable=false
     * ),
     */
    protected $service;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Application\Entity\Locale\LocaleEntity",
     *      fetch="LAZY"
     * )
     * @ORM\JoinColumn(
     *      name="localeId",
     *      referencedColumnName="id",
     *      nullable=false
     * ),
     */
    protected $locale;

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getServiceId() {
        return $this->serviceId;
    }

    public function getLocaleId() {
        return $this->localeId;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getService() {
        return $this->service;
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setServiceId($serviceId) {
        $this->serviceId = $serviceId;
    }

    public function setLocaleId($localeId) {
        $this->localeId = $localeId;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setService($service) {
        $this->service = $service;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

}

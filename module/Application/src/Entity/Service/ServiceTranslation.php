<?php

namespace Application\Entity\Service;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="ServiceTranslations",
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"serviceId", "locale"})}
 * )
 */
class ServiceTranslation {

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
     *      name="serviceId",
     *      type="integer",
     *      nullable=false
     * )
     */
    protected $serviceId;

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
     *      targetEntity="Application\Entity\Service\Service",
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

    public function __construct() {

    }

    public function get(string $id) {
        switch ($id) {
            case 'id':
                return $this->getId();
            case 'serviceId':
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
            case 'service':
                return $this->service->get($idList[1]);
        }

        return null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getServiceId(): int {
        return $this->serviceId;
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

    public function getService(): Service {
        return $this->service;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setServiceId(int $serviceId) {
        $this->serviceId = $serviceId;
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

    public function setService(Service $service) {
        $this->service = $service;
    }

}

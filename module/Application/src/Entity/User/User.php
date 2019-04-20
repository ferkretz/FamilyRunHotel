<?php

namespace Application\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 */
class User {

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
     *      name="email",
     *      type="string",
     *      length=160,
     *      unique=true,
     *      nullable=false
     * )
     */
    protected $email;

    /**
     * @ORM\Column(
     *      name="password",
     *      type="string",
     *      length=60,
     *      unique=true,
     *      nullable=false
     * )
     */
    protected $password;

    /**
     * @ORM\Column(
     *      name="displayName",
     *      type="string",
     *      length=60,
     *      nullable=true
     * )
     */
    protected $displayName;

    /**
     * @ORM\Column(
     *      name="realName",
     *      type="string",
     *      length=60,
     *      nullable=false
     * )
     */
    protected $realName;

    /**
     * @ORM\Column(
     *      name="address",
     *      type="string",
     *      length=160,
     *      nullable=true
     * )
     */
    protected $address;

    /**
     * @ORM\Column(
     *      name="phone",
     *      type="string",
     *      length=30,
     *      nullable=true
     * )
     */
    protected $phone;

    /**
     * @ORM\Column(
     *      name="registered",
     *      type="datetime",
     *      nullable=false
     * )
     */
    protected $registered;

    /**
     * @ORM\Column(
     *      name="role",
     *      type="string",
     *      length=30,
     *      nullable=false
     * )
     */
    protected $role;

    /**
     * @ORM\Column(
     *      name="activationKey",
     *      type="string",
     *      length=60,
     *      unique=true,
     *      nullable=true
     * )
     */
    protected $activationKey;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Application\Entity\Option\Option",
     *      mappedBy="user",
     *      indexBy="name",
     *      fetch="EXTRA_LAZY",
     *      orphanRemoval=true,
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"name"="ASC"})
     */
    protected $options;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Application\Entity\Reservation\Reservation",
     *      mappedBy="user",
     *      indexBy="id",
     *      fetch="EXTRA_LAZY",
     *      orphanRemoval=true,
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $reservations;

    public function __construct() {
        $this->options = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function get(string $id) {
        switch ($id) {
            case 'id':
                return $this->getId();
            case 'email':
                return $this->getEmail();
            case 'password':
                return $this->getPassword();
            case 'displayName':
                return $this->getDisplayName();
            case 'realName':
                return $this->getRealName();
            case 'address':
                return $this->getAddress();
            case 'phone':
                return $this->getPhone();
            case 'registered':
                return $this->getRegistered();
            case 'role':
                return $this->getRole();
            case 'activationKey':
                return $this->getActivationKey();
        }

        $idList = explode('.', $id);
        switch ($idList[0]) {
            case 'options':
                return $this->options->containsKey($idList[1]) ? $this->options->get($idList[1]) : null;
            case 'reservations':
                return $this->reservations->containsKey($idList[1]) ? $this->reservations->get($idList[1]) : null;
            case 'option':
                return $this->options->containsKey($idList[1]) ? $this->options->get($idList[1])->get($idList[2]) : null;
            case 'reservation':
                return $this->reservations->containsKey($idList[1]) ? $this->reservations->get($idList[1])->get($idList[2]) : null;
        }

        return null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getDisplayName(): ?string {
        return $this->displayName;
    }

    public function getRealName(): string {
        return $this->realName;
    }

    public function getAddress(): ?string {
        return $this->address;
    }

    public function getPhone(): ?string {
        return $this->phone;
    }

    public function getRegistered(): \DateTime {
        return $this->registered;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getActivationKey(): ?string {
        return $this->activationKey;
    }

    public function getOptions() {
        return $this->options;
    }

    public function getReservations() {
        return $this->reservations;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function setDisplayName(?string $displayName) {
        $this->displayName = $displayName;
    }

    public function setRealName(string $realName) {
        $this->realName = $realName;
    }

    public function setAddress(?string $address) {
        $this->address = $address;
    }

    public function setPhone(?string $phone) {
        $this->phone = $phone;
    }

    public function setRegistered(\DateTime $registered) {
        $this->registered = $registered;
    }

    public function setRole(string $role) {
        $this->role = $role;
    }

    public function setActivationKey(?string $activationKey) {
        $this->activationKey = $activationKey;
    }

    public function setOptions(ArrayCollection $options) {
        $this->options = $options;
    }

    public function updateOptionValueByName($name,
                                            $value) {
        $optionEntity = $this->options->get($name);

        if (!$optionEntity) {
            $optionEntity = new UserOption();
            $optionEntity->setUser($this);
            $optionEntity->setName($name);
            $optionEntity->setValue($value);
            $this->getOptions()->add($optionEntity);
        }

        $optionEntity->setValue($value);
    }

    public function setReservations(ArrayCollection $reservations) {
        $this->reservations = $reservations;
    }

}

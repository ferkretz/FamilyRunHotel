<?php

namespace Application\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Application\Entity\User\UserOptionEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 */
class UserEntity {

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
     *      nullable=true
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
     *      targetEntity="UserOptionEntity",
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
     *      targetEntity="Application\Entity\Reservation\ReservationEntity",
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
        $this->options = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getDisplayName() {
        return $this->displayName;
    }

    public function getRealName() {
        return $this->realName;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getRegistered() {
        return $this->registered;
    }

    public function getRole() {
        return $this->role;
    }

    public function getActivationKey() {
        return $this->activationKey;
    }

    public function getOptions() {
        return $this->options;
    }

    public function getReservations() {
        return $this->reservations;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
    }

    public function setRealName($realName) {
        $this->realName = $realName;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setRegistered($registered) {
        $this->registered = $registered;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setActivationKey($activationKey) {
        $this->activationKey = $activationKey;
    }

    public function setOptions($options) {
        $this->options = $options;
    }

    public function updateOptionValueByName($name,
                                            $value) {
        $optionEntity = $this->options->get($name);

        if (!$optionEntity) {
            $optionEntity = new UserOptionEntity();
            $optionEntity->setUser($this);
            $optionEntity->setName($name);
            $optionEntity->setValue($value);
            $this->getOptions()->add($optionEntity);
        }

        $optionEntity->setValue($value);
    }

    public function setReservations($reservations) {
        $this->reservations = $reservations;
    }

}

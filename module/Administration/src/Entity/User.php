<?php

namespace Administration\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Crypt\Password\Bcrypt;

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

    /*
     * @ORM\OneToMany(
     *      targetEntity="Reservation",
     *      mappedBy="user",
     *      cascade={"all","merge","persist","refresh","remove"}
     * )
     */
    protected $reservations;

    public function __construct() {
        $this->reservations = new ArrayCollection();
    }

    public function getData() {
        return get_object_vars($this);
    }

    public function setData($data) {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['email'])) {
            $this->email = $data['email'];
        }
        if (isset($data['password'])) {
            $bcrypt = new Bcrypt();
            $this->password = $bcrypt->create($data['password']);
        }
        if (isset($data['displayName'])) {
            $this->displayName = $data['displayName'];
        }
        if (isset($data['realName'])) {
            $this->realName = $data['realName'];
        }
        if (isset($data['address'])) {
            $this->address = $data['address'];
        }
        if (isset($data['phone'])) {
            $this->phone = $data['phone'];
        }
        if (isset($data['registered'])) {
            $this->registered = $data['registered'];
        }
        if (isset($data['role'])) {
            $this->role = $data['role'];
        }
        if (isset($data['activationKey'])) {
            $this->activationKey = $data['activationKey'];
        }
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

    public function addReservations(Reservation $reservation) {
        $this->reservations[] = $reservation;
    }

    public function setReservations($reservations) {
        $this->reservations = $reservations;
    }

}

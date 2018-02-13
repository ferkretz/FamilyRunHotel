<?php

namespace Application\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="UserOptions")
 */
class UserOptionEntity {

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
     *      name="userId",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $userId;

    /**
     * @ORM\Column(
     *      name="name",
     *      type="string",
     *      length=60,
     *      nullable=false
     * )
     */
    protected $name;

    /**
     * @ORM\Column(
     *      name="value",
     *      type="text"
     * )
     */
    protected $value;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="UserEntity",
     *      inversedBy="options",
     *      fetch="LAZY"
     * )
     * @ORM\JoinColumn(
     *      name="userId",
     *      referencedColumnName="id",
     *      nullable=false
     * ),
     */
    protected $user;

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getName() {
        return $this->name;
    }

    public function getValue() {
        return $this->value;
    }

    public function getUser() {
        return $this->user;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function setUser($user) {
        $this->user = $user;
    }

}

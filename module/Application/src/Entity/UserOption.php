<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="UserOptions")
 */
class UserOption {

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
     *      targetEntity="User",
     *      inversedBy="options",
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

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getValue() {
        return $this->value;
    }

    public function getUser() {
        return $this->user;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setValue($value) {
        $this->value = $value;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

}

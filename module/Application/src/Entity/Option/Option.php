<?php

namespace Application\Entity\Option;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="Options",
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"name", "userId"})}
 * )
 */
class Option {

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
     *      name="name",
     *      type="string",
     *      length=60,
     *      nullable=false
     * )
     */
    protected $name;

    /**
     * @ORM\Column(
     *      name="userId",
     *      type="integer",
     *      nullable=false
     * )
     */
    protected $userId;

    /**
     * @ORM\Column(
     *      name="value",
     *      type="text",
     *      nullable=true
     * )
     */
    protected $value;

    public function __construct() {
        
    }

    public function get(string $id) {
        switch ($id) {
            case 'id':
                return $this->getId();
            case 'name':
                return $this->getName();
            case 'userId':
                return $this->getUserId();
            case 'value':
                return $this->getValue();
        }

        return null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function getValue(): ?string {
        return $this->value;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setName(string $name) {
        $this->key = $name;
    }

    public function setUserId(int $userId) {
        $this->userId = $userId;
    }

    public function setValue(?string $value) {
        $this->value = $value;
    }

}

<?php

namespace Application\Entity\Reservation;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Reservations")
 */
class Reservation {

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
     *      name="roomId",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $roomId;

    /**
     * @ORM\Column(
     *      name="month",
     *      type="date",
     *      nullable=false
     * )
     */
    protected $month;

    /**
     * @ORM\Column(
     *      name="dayMask",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $dayMask;

    /**
     * @ORM\Column(
     *      name="statusMask",
     *      type="integer",
     *      length=10,
     *      options={"unsigned":true}
     * )
     */
    protected $statusMask;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Application\Entity\Room\Room",
     *      inversedBy="reservations",
     *      fetch="LAZY"
     * )
     * @ORM\JoinColumn(
     *      name="roomId",
     *      referencedColumnName="id",
     *      nullable=false
     * ),
     */
    protected $room;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Application\Entity\User\User",
     *      inversedBy="reservations",
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

    public function getMonth() {
        return $this->month;
    }

    public function getDayMask() {
        return $this->dayMask;
    }

    public function getStatusMask() {
        return $this->statusMask;
    }

    public function getRoom() {
        return $this->room;
    }

    public function getUser() {
        return $this->user;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setMonth($month) {
        $this->month = $month;
    }

    public function setDayMask($dayMask) {
        $this->dayMask = $dayMask;
    }

    public function setStatusMask($statusMask) {
        $this->statusMask = $statusMask;
    }

    public function setRoom($room) {
        $this->room = $room;
    }

    public function setUser($user) {
        $this->user = $user;
    }

}

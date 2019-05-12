<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Member
 *
 * @ORM\Table(name="members")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MemberRepository")
 */
class Member
{
    const STATUS_REGISTERED = 1;
    const STATUS_EXPECTANT = 2;
    const STATUS_REJECTED = 3;
    const STATUS_CONFIRMED = 4;
    const STATUS_WAITING_ON_CONFIRM = 5;
    const STATUS_FINAL_CONFIRMED = 6;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Babysitter
     *
     * @ORM\ManyToOne(targetEntity="Babysitter",inversedBy="members",cascade={"persist"})
     * @ORM\JoinColumn(name="babysitter_id", referencedColumnName="id",nullable=false,onDelete="CASCADE",nullable=false)
     */
    private $babysitter;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var MemberGroup
     *
     * @ORM\ManyToOne(targetEntity="MemberGroup")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id",nullable=false,onDelete="CASCADE",nullable=false)
     */
    private $group;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="born_at", type="datetime")
     */
    private $bornAt;

    /**
     * @var TShirtSize
     *
     * @ORM\ManyToOne(targetEntity="TShirtSize")
     * @ORM\JoinColumn(name="t_shirt_size_id", referencedColumnName="id",nullable=false,onDelete="CASCADE",nullable=false)
     */
    private $tShirtSize;

    /**
     * @var string
     *
     * @ORM\Column(name="prohibited_food", type="string", length=255)
     */
    private $prohibitedFood;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_expectant", type="boolean",nullable=false)
     */
    private $expectant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rejected_at", type="datetime",nullable=true)
     */
    private $rejectedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="status_id", type="integer")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setExpectant(true);
        $this->status = self::STATUS_REGISTERED;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set babysitterId
     *
     * @param Babysitter $babysitter
     *
     * @return Member
     */
    public function setBabysitter(Babysitter $babysitter = null)
    {
        $this->babysitter = $babysitter;

        return $this;
    }

    /**
     * Get babysitterId
     *
     * @return Babysitter
     */
    public function getBabysitter()
    {
        return $this->babysitter;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Member
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Member
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set bornDate
     *
     * @param \DateTime $bornAt
     *
     * @return Member
     */
    public function setBornAt($bornAt)
    {
        $this->bornAt = $bornAt;

        return $this;
    }

    /**
     * Get bornDate
     *
     * @return \DateTime
     */
    public function getBornAt()
    {
        return $this->bornAt;
    }

    /**
     * Set tShirtSize
     *
     * @param TShirtSize $tShirtSize
     *
     * @return Member
     */
    public function setTShirtSize(TShirtSize $tShirtSize = null)
    {
        $this->tShirtSize = $tShirtSize;

        return $this;
    }

    /**
     * Get tShirtSize
     *
     * @return TShirtSize
     */
    public function getTShirtSize()
    {
        return $this->tShirtSize;
    }

    /**
     * Set prohibitedFood
     *
     * @param string $prohibitedFood
     *
     * @return Member
     */
    public function setProhibitedFood($prohibitedFood)
    {
        $this->prohibitedFood = $prohibitedFood;

        return $this;
    }

    /**
     * Get prohibitedFood
     *
     * @return string
     */
    public function getProhibitedFood()
    {
        return $this->prohibitedFood;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Member
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param MemberGroup $group
     * @return Member
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return MemberGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param bool $expectant
     * @return Member
     */
    public function setExpectant($expectant)
    {
        $this->expectant = $expectant;
        return $this;
    }

    /**
     * @return bool
     */
    public function isExpectant()
    {
        return $this->expectant;
    }

    /**
     * @return \DateTime
     */
    public function getRejectedAt()
    {
        return $this->rejectedAt;
    }

    /**
     * @param int $status
     * @return Member
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatusWaitingOnConfirm()
    {
        $this->status = self::STATUS_WAITING_ON_CONFIRM;
    }

    public function setStatusFinalConfirmed()
    {
        $this->status = self::STATUS_FINAL_CONFIRMED;
    }

    public function setStatusExpectant()
    {
        $this->setExpectant(true);
        $this->status = self::STATUS_EXPECTANT;
    }

    public function setStatusConfirmed()
    {
        $this->setExpectant(false);
        $this->status = self::STATUS_CONFIRMED;
    }

    public function setStatusRejected()
    {
        $this->rejectedAt = new \DateTime();
        $this->status = self::STATUS_REJECTED;
    }
}


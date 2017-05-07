<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Babysitter
 *
 * @ORM\Table(name="babysitters")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BabysitterRepository")
 */
class Babysitter
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     */
    private $city;

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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=20)
     */
    private $telephone;

    /**
     * @var bool
     *
     * @ORM\Column(name="allow_terms", type="boolean")
     */
    private $allowTerms;

    /**
     * @var string
     *
     * @ORM\Column(name="allow_marketing", type="boolean")
     */
    private $allowMarketing;

    /**
     * @var Member[]
     *
     * @ORM\OneToMany(targetEntity="Member", mappedBy="babysitter",cascade={"persist"})
     */
    private $members;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->members=new ArrayCollection();
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Babysitter
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
     * @return Babysitter
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
     * Set email
     *
     * @param string $email
     *
     * @return Babysitter
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Babysitter
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Babysitter
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
     * @param bool $allowTerms
     * @return Babysitter
     */
    public function setAllowTerms($allowTerms)
    {
        $this->allowTerms = $allowTerms;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowTerms()
    {
        return $this->allowTerms;
    }

    /**
     * @param string $allowMarketing
     * @return Babysitter
     */
    public function setAllowMarketing($allowMarketing)
    {
        $this->allowMarketing = $allowMarketing;
        return $this;
    }

    /**
     * @return string
     */
    public function getAllowMarketing()
    {
        return $this->allowMarketing;
    }

    /**
     * @param City $city
     * @return Babysitter
     */
    public function setCity(City $city=null)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return Member[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param Member $member
     */
    public function addMember(Member $member){
        $member->setBabysitter($this);
        $this->members->add($member);
    }

}


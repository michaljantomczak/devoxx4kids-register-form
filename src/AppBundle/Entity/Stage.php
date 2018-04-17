<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stage
 *
 * @ORM\Table(name="stages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StageRepository")
 */
class Stage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id",nullable=false,onDelete="CASCADE",nullable=false)
     */
    private $event;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begin_at", type="datetime")
     */
    private $beginAt;

    /**
     * @var int
     *
     * @ORM\Column(name="count_required_members", type="integer")
     */
    private $countRequiredMembers;


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
     * Set eventId
     *
     * @param integer $event
     *
     * @return Stage
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set beginAt
     *
     * @param \DateTime $beginAt
     *
     * @return Stage
     */
    public function setBeginAt($beginAt)
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    /**
     * Get beginAt
     *
     * @return \DateTime
     */
    public function getBeginAt()
    {
        return $this->beginAt;
    }

    /**
     * Set countRequiredMembers
     *
     * @param integer $countRequiredMembers
     *
     * @return Stage
     */
    public function setCountRequiredMembers($countRequiredMembers)
    {
        $this->countRequiredMembers = $countRequiredMembers;

        return $this;
    }

    /**
     * Get countRequiredMembers
     *
     * @return int
     */
    public function getCountRequiredMembers()
    {
        return $this->countRequiredMembers;
    }
}


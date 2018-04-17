<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MemberGroup
 *
 * @ORM\Table(name="groups")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MemberGroupRepository")
 */
class MemberGroup
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="begin_old_member", type="integer")
     */
    private $beginOldMember;

    /**
     * @var int
     *
     * @ORM\Column(name="end_old_member", type="integer")
     */
    private $endOldMember;


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
     * Set name
     *
     * @param string $name
     *
     * @return MemberGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set beginOldMember
     *
     * @param integer $beginOldMember
     *
     * @return MemberGroup
     */
    public function setBeginOldMember($beginOldMember)
    {
        $this->beginOldMember = $beginOldMember;

        return $this;
    }

    /**
     * Get beginOldMember
     *
     * @return int
     */
    public function getBeginOldMember()
    {
        return $this->beginOldMember;
    }

    /**
     * Set endOldMember
     *
     * @param integer $endOldMember
     *
     * @return MemberGroup
     */
    public function setEndOldMember($endOldMember)
    {
        $this->endOldMember = $endOldMember;

        return $this;
    }

    /**
     * Get endOldMember
     *
     * @return int
     */
    public function getEndOldMember()
    {
        return $this->endOldMember;
    }
}


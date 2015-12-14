<?php

namespace LibraryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Loan
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LibraryBundle\Entity\LoanRepository")
 */
class Loan
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date")
     */
    private $startDate;

    /**
     *
     * @ORM\ManyToOne(targetEntity="LibraryBundle\Entity\Book", inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="onGoing", type="boolean")
     */
    private $onGoing = true;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Loan
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set book
     *
     * @param \LibraryBundle\Entity\Book $book
     *
     * @return Loan
     */
    public function setBook(\LibraryBundle\Entity\Book $book)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \LibraryBundle\Entity\Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Loan
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set onGoing
     *
     * @param boolean $onGoing
     *
     * @return Loan
     */
    public function setOnGoing($onGoing)
    {
        $this->onGoing = $onGoing;

        return $this;
    }

    /**
     * Get onGoing
     *
     * @return boolean
     */
    public function getOnGoing()
    {
        return $this->onGoing;
    }

    public function isLate()
    {
        $late = new \DateTime() > ($this->getStartDate()->add(new \DateInterval('P3D')));

        return ($this->getOnGoing() && $late);
    }
}

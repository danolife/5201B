<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\OneToMany(targetEntity="LibraryBundle\Entity\Loan", mappedBy="user")
     */
    private $loans;

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
     * Add loan
     *
     * @param \LibraryBundle\Entity\Loan $loan
     *
     * @return User
     */
    public function addLoan(\LibraryBundle\Entity\Loan $loan)
    {
        $this->loans[] = $loan;

        return $this;
    }

    /**
     * Remove loan
     *
     * @param \LibraryBundle\Entity\Loan $loan
     */
    public function removeLoan(\LibraryBundle\Entity\Loan $loan)
    {
        $this->loans->removeElement($loan);
    }

    /**
     * Get loans
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLoans()
    {
        return $this->loans;
    }
}

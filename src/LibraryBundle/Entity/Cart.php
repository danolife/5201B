<?php
/**
 * Created by PhpStorm.
 * User: damien
 * Date: 10/12/15
 * Time: 16:05
 */

namespace LibraryBundle\Entity;


class Cart
{
    private $books;

    /**
     * Cart constructor.
     * @param $bookList
     */
    public function __construct()
    {
        $this->books = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getBooks()
    {
        return $this->books;
    }

    public function addBook(Book $book)
    {
        $this->books[] = $book;

        return $this;
    }

    public function removeBook(Book $book)
    {
        $this->books->removeElement($book);
    }

    public function getSize()
    {
        return $this->books->count();
    }
}
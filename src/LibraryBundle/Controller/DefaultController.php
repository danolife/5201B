<?php

namespace LibraryBundle\Controller;

use LibraryBundle\Entity\Loan;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository("LibraryBundle:Book");
    	$books = $repo->findAll();

        return $this->render('LibraryBundle:Default:index.html.twig', array('books' => $books));
    }

    public function categoryAction($slug)
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository("LibraryBundle:Category");
    	$category = $repo->findOneBySlug($slug);
    	$books = $category->getBooks();

    	return $this->render('LibraryBundle:Default:category.html.twig', array('category' => $category, 'books' => $books));
    }

    public function bookAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Book");
        $book = $repo->findOneBySlug($slug);

    	return $this->render('LibraryBundle:Default:book.html.twig', array('book' => $book));
    }

    public function borrowAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Book");
        $book = $repo->findOneBySlug($slug);
        $loan = new Loan();
        $loan->setBook($book);
        $loan->setUser($this->getUser());
        $loan->setStartDate(new \DateTime());
        $em->persist($loan);
        $em->flush();
        return $this->redirectToRoute('library_book', array('slug' => $slug));
    }

    public function authorAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Author");
        $author = $repo->findOneBySlug($slug);
        $books = $author->getBooks();

    	return $this->render('LibraryBundle:Default:author.html.twig', array('author' => $author, 'books' => $books));
    }

    public function newsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Book");
        $books = $repo->findByIsNew(1);
        
        return $this->render('LibraryBundle:Default:nouveautes.html.twig', array('books' => $books));
    }

    // liste les categories dans le menu
    public function categoriesAction()
    {
        $em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository("LibraryBundle:Category");
    	$categories = $repo->findAll();

        return $this->render('LibraryBundle:Default:categories.html.twig', array('categories' => $categories));
    }
}

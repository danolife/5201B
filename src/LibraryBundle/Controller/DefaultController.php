<?php

namespace LibraryBundle\Controller;

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
    	$category = $repo->findOneBy(array("slug" => $slug));
    	$books = $category->getBooks();

    	return $this->render('LibraryBundle:Default:category.html.twig', array('category' => $category, 'books' => $books));
    }

    public function bookAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Book");
        $book = $repo->findOneBy(array("slug" => $slug));

    	return $this->render('LibraryBundle:Default:book.html.twig', array('book' => $book));
    }

    public function authorAction($slug)
    {
    	return $this->render('LibraryBundle:Default:author.html.twig');
    }

    public function newsAction()
    {
        $books = null;
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

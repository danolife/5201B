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
    	return $this->render('LibraryBundle:Default:category.html.twig');
    }

    public function bookAction($slug)
    {
    	return $this->render('LibraryBundle:Default:book.html.twig');
    }

    public function authorAction($slug)
    {
    	return $this->render('LibraryBundle:Default:author.html.twig');
    }
}

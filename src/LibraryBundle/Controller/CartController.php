<?php

namespace LibraryBundle\Controller;

use LibraryBundle\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CartController extends Controller
{
    public function removeFromCartAction($slug)
    {
        $this->get('session')->get('cart')->removeBook($slug);
        return $this->redirectToRoute('library_show_cart');
    }

    public function showCartAction()
    {
        $cart = $this->get('session')->get('cart');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Book");
        $books = array();

        foreach ($cart->getBooks() as $slug) {
            $book = $repo->findOneBySlug($slug);
            $books[] = $book;
        }
        return $this->render('LibraryBundle:Cart:showCart.html.twig', array('books' => $books));
    }

    public function addToCartAction($slug)
    {
        if(!$this->get('session')->has('cart'))
        {
            $cart = new Cart();
            $this->get('session')->set('cart',$cart);
        }
        else
        {
            $cart = $this->get('session')->get('cart');
        }
        $cart->addBook($slug);
        return $this->redirectToRoute('library_book', array('slug' => $slug));
    }
}

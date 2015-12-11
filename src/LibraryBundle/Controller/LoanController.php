<?php

namespace LibraryBundle\Controller;

use LibraryBundle\Entity\Loan;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoanController extends Controller
{
    public function checkoutAction()
    {
        $cart = $this->get('session')->get('cart');
        $em = $this->getDoctrine()->getEntityManager();
        $bookRepo = $em->getRepository('LibraryBundle:Book');
        foreach ($cart->getBooks() as $slug) {
            $book = $bookRepo->findOneBySlug($slug);
            $loan = new Loan();
            $loan->setBook($book);
            $loan->setUser($this->getUser());
            $loan->setStartDate(new \DateTime());
            $em->persist($loan);
        }
        $em->flush();
        return $this->redirectToRoute('fos_user_profile_show');
    }
}

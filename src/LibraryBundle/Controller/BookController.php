<?php

namespace LibraryBundle\Controller;

use LibraryBundle\Entity\Book;
use LibraryBundle\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookController extends Controller
{
    public function bookAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Book");
        $book = $repo->findOneBySlug($slug);
        $bookInCart = false;
        if($this->get('session')->has('cart') && $this->get('session')->get('cart')->getBooks()->contains($slug))
        {
            $bookInCart = true;
        }
        return $this->render('LibraryBundle:Book:book.html.twig', array('book' => $book, 'bookInCart' => $bookInCart));
    }

    public function editBookAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Book");
        $book = $repo->findOneBySlug($slug);
        $form = $this->get('form.factory')->create(new BookType(), $book);

        if($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Livre bien modifié');

            return $this->redirect($this->generateUrl('library_book', array('slug'=>$book->getSlug())));
        }

        return $this->render('LibraryBundle:Book:addBook.html.twig', array('form'=>$form->createView()));
    }

    public function removeBookAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository("LibraryBundle:Book")->findOneBySlug($slug);

        if (null === $book) {
            throw new NotFoundHttpException("Ce livre n'existe pas");
        }

        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            $em->remove($book);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "Le livre a bien été supprimé.");

            return $this->redirectToRoute('library_homepage');
        }

        return $this->render('LibraryBundle:Book:removeBook.html.twig', array('slug' => $slug, 'form' => $form->createView()));
    }

    public function addBookAction(Request $request)
    {
        $book = new Book();
        $form = $this->get('form.factory')->create(new BookType(), $book);

        if($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Livre bien ajouté');

            return $this->redirect($this->generateUrl('library_book', array('slug'=>$book->getSlug())));
        }

        return $this->render('LibraryBundle:Book:addBook.html.twig', array('form'=>$form->createView()));
    }
}

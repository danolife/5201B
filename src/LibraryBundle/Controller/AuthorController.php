<?php

namespace LibraryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorController extends Controller
{
    public function authorAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Author");
        $author = $repo->findOneBySlug($slug);
        $books = $author->getBooks();

        return $this->render('LibraryBundle:Author:author.html.twig', array('author' => $author, 'books' => $books));
    }

    public function addAuthorAction(Request $request)
    {
        $author = new Author();
        $form = $this->get('form.factory')->create(new AuthorType(), $author);

        if($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Auteur bien ajouté');

            return $this->redirect($this->generateUrl('library_author', array('slug'=>$author->getSlug())));
        }

        return $this->render('LibraryBundle:Author:addAuthor.html.twig', array('form'=>$form->createView()));
    }

    public function editAuthorAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Author");
        $author = $repo->findOneBySlug($slug);
        $form = $this->get('form.factory')->create(new AuthorType(), $author);

        if($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Auteur bien modifié');

            return $this->redirect($this->generateUrl('library_author', array('slug'=>$author->getSlug())));
        }

        return $this->render('LibraryBundle:Author:addAuthor.html.twig', array('form'=>$form->createView()));
    }

    public function removeAuthorAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Author");
        $author = $repo->findOneBySlug($slug);

        if (null === $author) {
            throw new NotFoundHttpException("Cet auteur n'existe pas");
        }

        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            $em->remove($author);
            $em->flush();
            return $this->redirectToRoute('library_homepage');
        }

        return $this->render('LibraryBundle:Author:removeAuthor.html.twig', array('slug' => $slug, 'form' => $form->createView()));
    }
}

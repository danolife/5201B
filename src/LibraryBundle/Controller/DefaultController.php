<?php

namespace LibraryBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use LibraryBundle\Entity\Cart;
use LibraryBundle\Entity\Category;
use LibraryBundle\Entity\Author;
use LibraryBundle\Entity\Book;
use LibraryBundle\Entity\Loan;
use LibraryBundle\Form\AuthorType;
use LibraryBundle\Form\BookType;
use LibraryBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
	public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository("LibraryBundle:Book");
    	$books = $repo->findAll();

        return $this->render('LibraryBundle:Default:index.html.twig', array('books' => $books));
    }

    public function loansOverviewAction()
    {
        $users = $this->get('fos_user.user_manager')->findUsers();
        return $this->render('LibraryBundle:Default:loansOverview.html.twig', array('users' => $users));
    }

    public function newsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Book");
        $books = $repo->findByIsNew(1);

        return $this->render('LibraryBundle:Default:nouveautes.html.twig', array('books' => $books));
    }

    public function searchAction(Request $request)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->add('recherche', 'text')
            ->add('livre','checkbox', array('required' => false))
            ->add('categorie', 'checkbox', array('required' => false))
            ->add('auteur', 'checkbox', array('required' => false))
            ->add('valider', 'submit');

        $resultBook = array();
        $resultCategory = array();
        $resultAuthor = array();
        if ($form->handleRequest($request)->isValid()) {
            $text = $form->get('recherche')->getData();
            $em = $this->getDoctrine()->getEntityManager();
            if($form->get('livre')->getData())
            {
                $bookRepo = $em->getRepository('LibraryBundle:Book');
                $resultBook = $bookRepo->findLike($text);
            }
            if($form->get('categorie')->getData())
            {
                $categoryRepo = $em->getRepository('LibraryBundle:Category');
                $resultCategory = $categoryRepo->findLike($text);
            }
            if($form->get('auteur')->getData())
            {
                $authorRepo = $em->getRepository('LibraryBundle:Author');
                $resultAuthor = $authorRepo->findLike($text);
            }
        }

        $resultFound = (count($resultBook)>0 || count($resultAuthor)>0 || count($resultCategory) > 0);

        return $this->render('LibraryBundle:Default:search.html.twig', array(
            'form' => $form->createView(),
            'resultFound' => $resultFound,
            'resultCategory' => $resultCategory,
            'resultBook' => $resultBook,
            'resultAuthor' => $resultAuthor,
            'formSubmitted' => $request->isMethod('POST')));
    }

    public function adminAction()
    {
        return $this->render('LibraryBundle:Default:admin.html.twig');
    }

    public function userManagerAction(){
        $users = $this->get('fos_user.user_manager')->findUsers();
        return $this->render('LibraryBundle:Default:userManager.html.twig', array('users' => $users));
    }

    public function userChangeStatusAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->findOneById($id);
        if(!$user->hasRole('ROLE_ADMIN'))
        {
            $user->setEnabled(1-$user->isEnabled());
            $em->flush();
        }


        return $this->redirectToRoute('library_user_manager');
    }

}

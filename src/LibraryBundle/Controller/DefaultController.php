<?php

namespace LibraryBundle\Controller;

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

    public function categoryAction($slug)
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository("LibraryBundle:Category");
    	$category = $repo->findOneBySlug($slug);
    	$books = $category->getBooks();

    	return $this->render('LibraryBundle:Default:category.html.twig', array('category' => $category, 'books' => $books));
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

        return $this->render('LibraryBundle:Default:addAuthor.html.twig', array('form'=>$form->createView()));
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

        return $this->render('LibraryBundle:Default:addBook.html.twig', array('form'=>$form->createView()));
    }

    public function editCategoryAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Category");
        $category = $repo->findOneBySlug($slug);
        $form = $this->get('form.factory')->create(new CategoryType(), $category);

        if($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Catégorie bien modifiée');

            return $this->redirect($this->generateUrl('library_category', array('slug'=>$category->getSlug())));
        }

        return $this->render('LibraryBundle:Default:addCategory.html.twig', array('form'=>$form->createView()));
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

        return $this->render('LibraryBundle:Default:removeAuthor.html.twig', array('slug' => $slug, 'form' => $form->createView()));
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

        return $this->render('LibraryBundle:Default:removeBook.html.twig', array('slug' => $slug, 'form' => $form->createView()));
    }

    public function removeCategoryAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Category");
        $category = $repo->findOneBySlug($slug);
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('library_homepage');
    }

    // liste les categories dans le menu
    public function categoriesAction()
    {
        $em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository("LibraryBundle:Category");
    	$categories = $repo->findAll();

        return $this->render('LibraryBundle:Default:categories.html.twig', array('categories' => $categories));
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

        return $this->render('LibraryBundle:Default:addBook.html.twig', array('form'=>$form->createView()));
    }

    public function addCategoryAction(Request $request)
    {
        $category = new Category();
        $form = $this->get('form.factory')->create(new CategoryType(), $category);

        if($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Catégorie bien ajoutée');

            return $this->redirect($this->generateUrl('library_category', array('slug'=>$category->getSlug())));
        }

        return $this->render('LibraryBundle:Default:addCategory.html.twig', array('form'=>$form->createView()));
    }

    public function adminAction()
    {
        return $this->render('LibraryBundle:Default:admin.html.twig');
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

        return $this->render('LibraryBundle:Default:addAuthor.html.twig', array('form'=>$form->createView()));
    }

}

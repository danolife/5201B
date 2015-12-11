<?php

namespace LibraryBundle\Controller;

use LibraryBundle\Entity\Category;
use LibraryBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    public function categoryAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Category");
        $category = $repo->findOneBySlug($slug);
        $books = $category->getBooks();

        return $this->render('LibraryBundle:Category:category.html.twig', array('category' => $category, 'books' => $books));
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

        return $this->render('LibraryBundle:Category:addCategory.html.twig', array('form'=>$form->createView()));
    }

    public function removeCategoryAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Category");
        $category = $repo->findOneBySlug($slug);

        if (null === $category) {
            throw new NotFoundHttpException("Cette catégorie n'existe pas");
        }

        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            $em->remove($category);
            $em->flush();
            return $this->redirectToRoute('library_homepage');
        }

        return $this->render('LibraryBundle:Category:removeCategory.html.twig', array('slug' => $slug, 'form' => $form->createView()));
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

        return $this->render('LibraryBundle:Category:addCategory.html.twig', array('form'=>$form->createView()));
    }

    // liste les categories dans le menu
    public function categoriesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("LibraryBundle:Category");
        $categories = $repo->findAll();

        return $this->render('LibraryBundle:Category:categories.html.twig', array('categories' => $categories));
    }
}

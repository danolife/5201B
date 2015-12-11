<?php

namespace LibraryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BookController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }
}

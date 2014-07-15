<?php

namespace JamesMannion\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JamesMannionForumBundle:Default:index.html.twig');
    }
}

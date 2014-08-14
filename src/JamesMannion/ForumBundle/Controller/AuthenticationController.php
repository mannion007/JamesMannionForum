<?php

namespace JamesMannion\ForumBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use JamesMannion\ForumBundle\Form\Authentication\AuthenticationLoginForm;

class AuthenticationController extends BaseController
{
    public function loginAction(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        $form = $this->createForm(new AuthenticationLoginForm());
        return $this->render('JamesMannionForumBundle:Authentication:login.html.twig', array(
            'appConfig'     => $this->appConfig,
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'form'          => $form->createView(),
            'error'         => $error
        ));
    }

    public function loginFormAction()
    {
        $form = $this->createForm(new AuthenticationLoginForm());

        $parameters = array(
            'form'          => $form->createView()
        );

        return $this->render(
            'JamesMannionForumBundle:Authentication:loginForm.html.twig',
            $parameters
        );
    }
}
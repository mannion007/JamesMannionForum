<?php

namespace JamesMannion\ForumBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use JamesMannion\ForumBundle\Entity\Login;
use JamesMannion\ForumBundle\Entity\User;

class AuthenticationHandler extends ContainerAware implements AuthenticationSuccessHandlerInterface
{
    function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();
        $login = new Login();
        $login->setIp($request->getClientIp());
        $user->addLogin($login);
        $login->setUser($token->getUser());
        $this->container->get('doctrine')->getManager()->flush();

        return new RedirectResponse($this->container->get('router')->generate('JamesMannionForumBundle_homepage'));
    }
}
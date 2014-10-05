<?php
/**
 * Created by PhpStorm.
 * User: jmannion
 * Date: 04/08/14
 * Time: 22:35
 */

namespace JamesMannion\ForumBundle\Controller;

use JamesMannion\ForumBundle\Entity\Avatar;
use JamesMannion\ForumBundle\Event\UserEvent;
use JamesMannion\ForumBundle\Form\User\UserUpdateForm;
use JamesMannion\ForumBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use JamesMannion\ForumBundle\Entity\User;
use JamesMannion\ForumBundle\Form\User\UserCreateForm;
use JamesMannion\ForumBundle\Constants\PageTitle;

class UserController extends BaseController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usersToShow = $em->getRepository('JamesMannionForumBundle:User')->findAll();
        return $this->render('JamesMannionForumBundle:User:index.html.twig', array(
            'appConfig'     => $this->appConfig,
            'pageTitle'     => PageTitle::THREADS_LIST,
            'contentTitle'  => 'Registered Users',
            'users'         => $usersToShow
        ));
    }

    public function showAction(User $userToShow)
    {
        return $this->render('JamesMannionForumBundle:User:show.html.twig', array(
            'appConfig'     => $this->appConfig,
            'pageTitle'     => PageTitle::USER_SHOW,
            'contentTitle'  => 'User ' . $userToShow->getUsername(),
            'user'          => $userToShow
        ));
    }



    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $userToCreate = new User();

        $form = $this->createForm(
            new UserCreateForm(),
            $userToCreate);

        if ('POST' == $request->getMethod()) {
            $form->submit($request);
        }

        if ($form->isValid()) {

            $userToCreate->setPassword(
                password_hash(
                    $form->get('password')
                        ->getData(),
                    PASSWORD_BCRYPT
                )
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($userToCreate);
            $em->flush();

            $dispatcher = $this->container->get('event_dispatcher');
            $dispatcher->dispatch('userRegistered', new UserEvent($userToCreate));

            return $this->render(
                'JamesMannionForumBundle:User:created.html.twig',
                array(
                    'appConfig'     => $this->appConfig,
                    'pageTitle'     => PageTitle::USER_CREATED,
                    'createdUser'   => $userToCreate)
            );
        }

        return $this->render(
            'JamesMannionForumBundle:User:create.html.twig',
            array(
                'appConfig'     => $this->appConfig,
                'pageTitle'     => PageTitle::USER_CREATE,
                'form'          => $form->createView()));
    }

    public function editAction()
    {
        $user = $this->getUser();
        $avatar = new Avatar();
        $editForm = $this->createEditForm($avatar);
        return $this->render('JamesMannionForumBundle:User:edit.html.twig', array(
            'appConfig'     => $this->appConfig,
            'user'          => $this->getUser(),
            'form'          => $editForm->createView()
        ));
    }

    public function updateAction(Request $request)
    {
        $editForm = $this->createEditForm();
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            /** @var Avatar $uploadedAvatar */
            $uploadedAvatar = $editForm->getData();
            if(null != $uploadedAvatar){
                $this->getUser()->setAvatar($uploadedAvatar);
                $uploadedAvatar->setUser($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($uploadedAvatar);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('userEdit'));
        }

        return $this->render('JamesMannionForumBundle:User:edit.html.twig', array(
            'appConfig'     => $this->appConfig,
            'user'          => $this->getUser(),
            'form'     => $editForm->createView()
        ));
    }

    private function createEditForm()
    {
        $form = $this->createForm(new UserUpdateForm(), new Avatar(), array(
            'action' => $this->generateUrl('userUpdate', array('id' => $this->getUser()->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }
}
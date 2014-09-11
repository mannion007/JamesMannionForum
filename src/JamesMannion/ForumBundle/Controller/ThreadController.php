<?php

namespace JamesMannion\ForumBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JamesMannion\ForumBundle\Entity\Thread;
use JamesMannion\ForumBundle\Entity\Room;
use JamesMannion\ForumBundle\Form\ThreadType;
use JamesMannion\ForumBundle\Constants\AppConfig;
use JamesMannion\ForumBundle\Constants\PageTitle;
use JamesMannion\ForumBundle\Constants\SuccessFlash;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends BaseController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminIndexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $threadsToShow = $em->getRepository('JamesMannionForumBundle:Thread')->findAll();
        return $this->render('JamesMannionForumBundle:Thread:index.html.twig', array(
            'appConfig'     => $this->appConfig,
            'pageTitle'     => PageTitle::THREADS_LIST,
            'title'         => PageTitle::THREADS_LIST,
            'threads'       => $threadsToShow
        ));
    }

    /**
     * @param Thread $threadToWatchToggle
     * @return Response
     */
    public function watchToggleAction(Thread $threadToWatchToggle)
    {
        if(true === $threadToWatchToggle->hasWatcher($this->getUser())) {
            $threadToWatchToggle->removeWatcher($this->getUser());
            $watchStatus = false;
        } else {
            $threadToWatchToggle->addWatcher($this->getUser());
            $watchStatus = true;
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->merge($threadToWatchToggle);
        $entityManager->flush();

        $response = array("watchStatus" => $watchStatus, "code" => 100, "success" => true);
        return new Response(json_encode($response));
    }

    /**
     * @param Room $roomToCreateThreadIn
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Room $roomToCreateThreadIn, Request $request)
    {
        $threadToCreate = new Thread();

        $form = $this->createCreateForm($roomToCreateThreadIn, $threadToCreate);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $threadToCreate->setAuthor($this->getUser());
            $threadToCreate->setRoom($roomToCreateThreadIn);

            $post = current(current($threadToCreate->getPosts()));
            $post->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($threadToCreate);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                SuccessFlash::THREAD_CREATED_SUCCESSFULLY
            );

            return $this->redirect($this->generateUrl('room_show', array('id' => $roomToCreateThreadIn->getId())));
        }

        return $this->render('JamesMannionForumBundle:Thread:new.html.twig', array(
            'systemName'    => AppConfig::SYSTEM_NAME,
            'title'         => PageTitle::THREADS_NEW,
            'entity'        => $threadToCreate,
            'form'          => $form->createView(),
        ));
    }

    /**
     * @param Room $roomToCreateThreadIn
     * @param Thread $threadToCreate
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Room $roomToCreateThreadIn, Thread $threadToCreate)
    {
        $form = $this->createForm(new ThreadType(), $threadToCreate, array(
            'action' => $this->generateUrl('thread_create', array('id' => $roomToCreateThreadIn->getId())),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * @param Room $roomToCreateThreadIn
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Room $roomToCreateThreadIn)
    {
        $threadToCreate = new Thread();
        $form   = $this->createCreateForm($roomToCreateThreadIn, $threadToCreate);
        return $this->render('JamesMannionForumBundle:Thread:new.html.twig', array(
            'appConfig'     => $this->appConfig,
            'pageTitle'     => PageTitle::THREADS_NEW,
            'contentTitle'  => 'Create new Thread in ' . $roomToCreateThreadIn->getName(),
            'thread'        => $threadToCreate,
            'room'          => $roomToCreateThreadIn,
            'form'          => $form->createView())
        );
    }

    /**
     * @param Thread $threadToShow
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction(Thread $threadToShow)
    {
        $threadToShow->addView();
        $em = $this->getDoctrine()->getManager();
        $em->persist($threadToShow);
        $em->flush();
        return $this->render('JamesMannionForumBundle:Thread:show.html.twig', array(
            'appConfig'     => $this->appConfig,
            'pageTitle'     => PageTitle::THREADS_SHOW . ' "' . $threadToShow->getTitle() . '"',
            'thread'        => $threadToShow,
            'posts'         => $threadToShow->getPosts(),
        ));
    }

    /**
     * @param Thread $threadToEdit
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Thread $threadToEdit)
    {
        $editForm = $this->createEditForm($threadToEdit);
        $deleteForm = $this->createDeleteForm($threadToEdit);

        return $this->render('JamesMannionForumBundle:Thread:edit.html.twig', array(
            'appConfig'     => $this->appConfig,
            'pageTitle'     => PageTitle::THREADS_EDIT . '"' . $threadToEdit->getTitle() . '"',
            'contentTitle'  => 'Edit Thread "' . $threadToEdit->getTitle() . '"',
            'thread'        => $threadToEdit,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param Thread $threadToCreate
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Thread $threadToCreate)
    {
        $form = $this->createForm(new ThreadType(), $threadToCreate, array(
            'action' => $this->generateUrl('thread_update', array('id' => $threadToCreate->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * @param Request $request
     * @param Thread $threadToUpdate
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Thread $threadToUpdate)
    {
        $deleteForm = $this->createDeleteForm($threadToUpdate);
        $editForm = $this->createEditForm($threadToUpdate);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('thread_edit', array('id' => $threadToUpdate->getId())));
        }

        return $this->render('JamesMannionForumBundle:Thread:edit.html.twig', array(
            'thread'      => $threadToUpdate,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Thread $threadToDelete
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Thread $threadToDelete)
    {
        $form = $this->createDeleteForm($threadToDelete);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($threadToDelete);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('thread'));
    }

    /**
     * @param Thread $threadToDelete
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Thread $threadToDelete)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('thread_delete', array('id' => $threadToDelete->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}

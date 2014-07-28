<?php

namespace JamesMannion\ForumBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JamesMannion\ForumBundle\Entity\Thread;
use JamesMannion\ForumBundle\Entity\Room;
use JamesMannion\ForumBundle\Form\ThreadType;
use JamesMannion\ForumBundle\Constants\Config;
use JamesMannion\ForumBundle\Constants\Title;
use JamesMannion\ForumBundle\Constants\SuccessFlash;

/**
 * Thread controller.
 *
 */
class ThreadController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $threadsToShow = $em->getRepository('JamesMannionForumBundle:Thread')->findAll();

        return $this->render('JamesMannionForumBundle:Thread:index.html.twig', array(
            'systemName'    => Config::SYSTEM_NAME,
            'title'         => Title::THREADS_LIST,
            'threads'       => $threadsToShow,
        ));
    }

    /**
     * @param $roomId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createAction($roomId, Request $request)
    {
        $threadToCreate = new Thread();

        $form = $this->createCreateForm($roomId, $threadToCreate);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Room $room */
            $room = $em->getRepository('JamesMannionForumBundle:Room')->find($roomId);

            $threadToCreate->setAuthor($this->getUser());
            $threadToCreate->setRoom($room);

            $post = current(current($threadToCreate->getPosts()));

            $post->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($threadToCreate);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                SuccessFlash::THREAD_CREATED_SUCCESSFULLY
            );

            return $this->redirect(
                $this->generateUrl(
                    'room_show',
                    array('roomId' => $room->getId())
                )
            );
        }

        return $this->render('JamesMannionForumBundle:Thread:new.html.twig', array(
            'systemName'    => Config::SYSTEM_NAME,
            'title'         => Title::THREADS_NEW,
            'entity'        => $threadToCreate,
            'form'          => $form->createView(),
        ));
    }

    /**
     * @param $roomId
     * @param Thread $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm($roomId, Thread $entity)
    {
        $form = $this->createForm(new ThreadType(), $entity, array(
            'action' => $this->generateUrl('thread_create', array('roomId' => $roomId)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * @param $roomId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function newAction($roomId)
    {
        $threadToCreate = new Thread();
        $form   = $this->createCreateForm($roomId, $threadToCreate);

        return $this->render('JamesMannionForumBundle:Thread:new.html.twig', array(
            'systemName'    => Config::SYSTEM_NAME,
            'title'         => Title::THREADS_NEW,
            'thread'        => $threadToCreate,
            'roomId'        => $roomId,
            'form'          => $form->createView()
        ));
    }

    /**
     * @param $threadId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($threadId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Thread $threadToShow */
        $threadToShow = $em->getRepository('JamesMannionForumBundle:Thread')->find($threadId);

        if (!$threadToShow) {
            throw $this->createNotFoundException('Unable to find Thread entity.');
        }

        $threadToShow->addView();
        $em->persist($threadToShow);
        $em->flush();

        return $this->render('JamesMannionForumBundle:Thread:show.html.twig', array(
            'systemName'    => Config::SYSTEM_NAME,
            'title'         => Title::THREADS_SHOW,
            'thread'        => $threadToShow,
            'posts'         => $threadToShow->getPosts(),
        ));
    }

    /**
     * Displays a form to edit an existing Thread entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JamesMannionForumBundle:Thread')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Thread entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JamesMannionForumBundle:Thread:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Thread entity.
    *
    * @param Thread $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Thread $entity)
    {
        $form = $this->createForm(new ThreadType(), $entity, array(
            'action' => $this->generateUrl('thread_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Thread entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JamesMannionForumBundle:Thread')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Thread entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('thread_edit', array('id' => $id)));
        }

        return $this->render('JamesMannionForumBundle:Thread:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Thread entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JamesMannionForumBundle:Thread')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Thread entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('thread'));
    }

    /**
     * Creates a form to delete a Thread entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('thread_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

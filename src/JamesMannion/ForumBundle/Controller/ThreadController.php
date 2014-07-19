<?php

namespace JamesMannion\ForumBundle\Controller;

use JamesMannion\ForumBundle\Constants\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JamesMannion\ForumBundle\Entity\Thread;
use JamesMannion\ForumBundle\Entity\Room;
use JamesMannion\ForumBundle\Form\ThreadType;
use JamesMannion\ForumBundle\Constants\Config;
use JamesMannion\ForumBundle\Constants\Title;

/**
 * Thread controller.
 *
 */
class ThreadController extends Controller
{

    /**
     * Lists all Thread entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JamesMannionForumBundle:Thread')->findAll();

        return $this->render('JamesMannionForumBundle:Thread:index.html.twig', array(
            'systemName'    => Config::SYSTEM_NAME,
            'title'     => Title::THREADS_LIST,
            'entities'      => $entities,
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new Thread();
        $entity->setCreated(new \DateTime());
        $entity->setAuthor($this->getUser());

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('thread_show', array('id' => $entity->getId())));
        }

        return $this->render('JamesMannionForumBundle:Thread:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Thread entity.
     *
     * @param Thread $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Thread $entity)
    {
        $form = $this->createForm(new ThreadType(), $entity, array(
            'action' => $this->generateUrl('thread_create'),
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
        $em = $this->getDoctrine()->getManager();
        /** @var Room $roomEntity */
        $roomEntity = $em->getRepository('JamesMannionForumBundle:Room')->find($roomId);

        if (!$roomEntity) {
            throw $this->createNotFoundException(Exception::ROOM_NOT_FOUND);
        }
        /** @var Thread $entity */
        $entity = new Thread();
        $entity->setRoom($roomEntity);

        $form   = $this->createCreateForm($entity);

        return $this->render('JamesMannionForumBundle:Thread:new.html.twig', array(
            'systemName'    => Config::SYSTEM_NAME,
            'title'         => Title::THREADS_NEW,
            'entity'        => $entity,
            'roomEntity'    => $roomEntity,
            'form'          => $form->createView()
        ));
    }

    /**
     * Finds and displays a Thread entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JamesMannionForumBundle:Thread')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Thread entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JamesMannionForumBundle:Thread:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
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

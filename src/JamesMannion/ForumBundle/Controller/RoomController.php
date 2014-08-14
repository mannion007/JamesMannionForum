<?php

namespace JamesMannion\ForumBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JamesMannion\ForumBundle\Entity\Room;
use JamesMannion\ForumBundle\Form\RoomType;
use JamesMannion\ForumBundle\Constants\AppConfig;
use JamesMannion\ForumBundle\Constants\Title;


class RoomController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $roomsToShow = $em->getRepository('JamesMannionForumBundle:Room')->findAll();

        return $this->render('JamesMannionForumBundle:Room:index.html.twig', array(
            'systemName'    => AppConfig::SYSTEM_NAME,
            'title'         => Title::ROOMS_LIST,
            'rooms'    => $roomsToShow
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminIndexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('JamesMannionForumBundle:Room')->findAll();
        return $this->render('JamesMannionForumBundle:Room:Admin:index.html.twig', array(
            'systemName'    => AppConfig::SYSTEM_NAME,
            'title'         => Title::ROOMS_LIST,
            'entities'      => $entities,
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminCreateAction(Request $request)
    {
        $roomToCreate = new Room();
        $form = $this->createCreateForm($roomToCreate);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($roomToCreate);
            $em->flush();
            return $this->redirect($this->generateUrl('room_show', array('id' => $entity->getId())));
        }

        return $this->render('JamesMannionForumBundle:Room:new.html.twig', array(
            'room'  => $roomToCreate,
            'form'  => $form->createView(),
        ));
    }

    /**
     * @param Room $room
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Room $room)
    {
        $form = $this->createForm(new RoomType(), $room, array(
            'action' => $this->generateUrl('room_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $roomToCreate = new Room();
        $form   = $this->createCreateForm($roomToCreate);
        return $this->render('JamesMannionForumBundle:Room:new.html.twig', array(
            'room'      => $roomToCreate,
            'form'      => $form->createView(),
        ));
    }

    /**
     * @param Room $roomToShow
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Room $roomToShow)
    {
        $threadsToShow = $roomToShow->getThreads();
        $deleteForm = $this->createDeleteForm($roomToShow);

        return $this->render('JamesMannionForumBundle:Room:show.html.twig', array(
            'systemName'    => AppConfig::SYSTEM_NAME,
            'title'         => $roomToShow->getName(),
            'room'          => $roomToShow,
            'threads'       => $threadsToShow,
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param Room $roomToEdit
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Room $roomToEdit)
    {
        $editForm = $this->createEditForm($roomToEdit);
        $deleteForm = $this->createDeleteForm($roomToEdit);

        return $this->render('JamesMannionForumBundle:Room:edit.html.twig', array(
            'room'          => $roomToEdit,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param Room $room
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Room $room)
    {
        $form = $this->createForm(new RoomType(), $room, array(
            'action' => $this->generateUrl('room_update', array('id' => $room->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * @param Request $request
     * @param Room $roomToUpdate
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Room $roomToUpdate)
    {

        $deleteForm = $this->createDeleteForm($roomToUpdate);
        $editForm = $this->createEditForm($roomToUpdate);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('room_edit', array('id' => $roomToUpdate->getId())));
        }

        return $this->render('JamesMannionForumBundle:Room:edit.html.twig', array(
            'room'          => $roomToUpdate,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $roomToDelete
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction(Request $request, $roomToDelete)
    {
        $form = $this->createDeleteForm($roomToDelete);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($roomToDelete);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('room'));
    }

    /**
     * @param Room $room
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Room $room)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('room_delete', array('id' => $room->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}

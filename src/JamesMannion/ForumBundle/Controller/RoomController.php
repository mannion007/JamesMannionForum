<?php

namespace JamesMannion\ForumBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use JamesMannion\ForumBundle\Entity\Room;
use JamesMannion\ForumBundle\Form\RoomType;
use JamesMannion\ForumBundle\Constants\AppConfig;
use JamesMannion\ForumBundle\Constants\PageTitle;

class RoomController extends BaseController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $roomsToShow = $em->getRepository('JamesMannionForumBundle:Room')->findAll();
        return $this->render('JamesMannionForumBundle:Room:index.html.twig', array(
            'appConfig'     => $this->appConfig,
            'systemName'    => AppConfig::SYSTEM_NAME,
            'title'         => PageTitle::ROOMS_LIST,
            'rooms'         => $roomsToShow
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
            'appConfig'     => $this->appConfig,
            'systemName'    => AppConfig::SYSTEM_NAME,
            'title'         => PageTitle::ROOMS_LIST,
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
        $form = $this->adminCreateCreateForm($roomToCreate);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($roomToCreate);
            $em->flush();
            return $this->redirect($this->generateUrl('room_show', array('id' => $roomToCreate->getId())));
        }
        return $this->render('JamesMannionForumBundle:Room:new.html.twig', array(
            'appConfig'     => $this->appConfig,
            'room'  => $roomToCreate,
            'form'  => $form->createView(),
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminNewAction()
    {
        $roomToCreate = new Room();
        $form   = $this->adminCreateCreateForm($roomToCreate);
        return $this->render('JamesMannionForumBundle:Room:new.html.twig', array(
            'appConfig'     => $this->appConfig,
            'room'          => $roomToCreate,
            'form'          => $form->createView(),
        ));
    }

    /**
     * @param Room $roomToShow
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Room $roomToShow)
    {
        $threadsToShow = $roomToShow->getThreads();
        $deleteForm = $this->adminCreateDeleteForm($roomToShow);
        return $this->render('JamesMannionForumBundle:Room:show.html.twig', array(
            'appConfig'     => $this->appConfig,
            'pageTitle'     => PageTitle::ROOMS_SHOW . ' "' . $roomToShow->getName() . '"',
            'contentTitle'  => $roomToShow->getName(),
            'room'          => $roomToShow,
            'threads'       => $threadsToShow,
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param Room $roomToEdit
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminEditAction(Room $roomToEdit)
    {
        $editForm = $this->adminCreateEditForm($roomToEdit);
        $deleteForm = $this->adminCreateDeleteForm($roomToEdit);
        return $this->render('JamesMannionForumBundle:Room:edit.html.twig', array(
            'appConfig'     => $this->appConfig,
            'room'          => $roomToEdit,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Room $roomToUpdate
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminUpdateAction(Request $request, Room $roomToUpdate)
    {
        $deleteForm = $this->adminCreateDeleteForm($roomToUpdate);
        $editForm = $this->adminCreateEditForm($roomToUpdate);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('room_edit', array('id' => $roomToUpdate->getId())));
        }
        return $this->render('JamesMannionForumBundle:Room:edit.html.twig', array(
            'appConfig'     => $this->appConfig,
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
    public function adminDeleteAction(Request $request, $roomToDelete)
    {
        $form = $this->adminCreateDeleteForm($roomToDelete);
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
    private function adminCreateCreateForm(Room $room)
    {
        $form = $this->createForm(new RoomType(), $room, array(
            'action' => $this->generateUrl('room_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * @param Room $roomToEdit
     * @return \Symfony\Component\Form\Form
     */
    private function adminCreateEditForm(Room $roomToEdit)
    {
        $form = $this->createForm(new RoomType(), $roomToEdit, array(
            'action' => $this->generateUrl('room_update', array('id' => $roomToEdit->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * @param Room $room
     * @return \Symfony\Component\Form\Form
     */
    private function adminCreateDeleteForm(Room $room)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('room_delete', array('id' => $room->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}

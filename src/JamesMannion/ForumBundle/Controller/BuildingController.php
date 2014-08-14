<?php

namespace JamesMannion\ForumBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JamesMannion\ForumBundle\Constants\AppConfig;
use JamesMannion\ForumBundle\Entity\Building;
use JamesMannion\ForumBundle\Form\BuildingType;
use JamesMannion\ForumBundle\Constants\Title;

class BuildingController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $buildingsToShow = $em->getRepository('JamesMannionForumBundle:Building')->findAll();

//        $paginator = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//            $buildingsToShow,
//            $this->get('request')->query->get('page', 1),
//            AppConfig::BUILDINGS_PER_PAGE
//        );

        return $this->render('JamesMannionForumBundle:Building:index.html.twig', array(
            'systemName'    => AppConfig::SYSTEM_NAME,
            'title'         => Title::BUILDING_LIST,
            'buildings'     => $buildingsToShow
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $buildingToCreate = new Building();
        $form = $this->createCreateForm($buildingToCreate);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($buildingToCreate);
            $em->flush();
            return $this->redirect($this->generateUrl('building_show', array('id' => $buildingToCreate->getId())));
        }

        return $this->render('JamesMannionForumBundle:Building:new.html.twig', array(
            'building'  => $buildingToCreate,
            'form'      => $form->createView(),
        ));
    }

    /**
     * @param Building $buildingToCreate
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Building $buildingToCreate)
    {
        $form = $this->createForm(new BuildingType(), $buildingToCreate, array(
            'action' => $this->generateUrl('building_create'),
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
        $buildingToCreate = new Building();
        $form   = $this->createCreateForm($buildingToCreate);

        return $this->render('JamesMannionForumBundle:Building:new.html.twig', array(
            'building'  => $buildingToCreate,
            'form'      => $form->createView(),
        ));
    }

    /**
     * @param Building $buildingToShow
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction(Building $buildingToShow)
    {
        $deleteForm = $this->createDeleteForm($buildingToShow);

        return $this->render('JamesMannionForumBundle:Building:show.html.twig', array(
            'building'      => $buildingToShow,
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param $buildingToEdit
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Building $buildingToEdit)
    {
        $editForm = $this->createEditForm($buildingToEdit);
        $deleteForm = $this->createDeleteForm($buildingToEdit);

        return $this->render('JamesMannionForumBundle:Building:edit.html.twig', array(
            'building'      => $buildingToEdit,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param Building $buildingToCreate
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Building $buildingToCreate)
    {
        $form = $this->createForm(new BuildingType(), $buildingToCreate, array(
            'action' => $this->generateUrl('building_update', array('id' => $buildingToCreate->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * @param Request $request
     * @param $buildingToUpdate
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, Building $buildingToUpdate)
    {
        $deleteForm = $this->createDeleteForm($buildingToUpdate);
        $editForm = $this->createEditForm($buildingToUpdate);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('building_edit', array('id' => $buildingToUpdate->getId())));
        }

        return $this->render('JamesMannionForumBundle:Building:edit.html.twig', array(
            'building'    => $buildingToUpdate,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Building $buildingToDelete
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Building $buildingToDelete)
    {
        $form = $this->createDeleteForm($buildingToDelete);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($buildingToDelete);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('building'));
    }

    /**
     * @param Building $buildingToDelete
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Building $buildingToDelete)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('building_delete', array('id' => $buildingToDelete->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}

<?php

namespace JamesMannion\ForumBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JamesMannion\ForumBundle\Constants\Config;
use JamesMannion\ForumBundle\Entity\Building;
use JamesMannion\ForumBundle\Form\BuildingType;
use JamesMannion\ForumBundle\Constants\Title;

/**
 * Building controller.
 *
 */
class BuildingController extends Controller
{

    /**
     * Lists all Building entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $buildingsToShow = $em->getRepository('JamesMannionForumBundle:Building')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $buildingsToShow,
            $this->get('request')->query->get('page', 1),
            Config::BUILDINGS_PER_PAGE
        );

        return $this->render('JamesMannionForumBundle:Building:index.html.twig', array(
            'systemName'    => Config::SYSTEM_NAME,
            'title'         => Title::BUILDING_LIST,
            'pagination'    => $pagination
        ));
    }
    /**
     * Creates a new Building entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Building();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('building_show', array('id' => $entity->getId())));
        }

        return $this->render('JamesMannionForumBundle:Building:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Building entity.
     *
     * @param Building $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Building $entity)
    {
        $form = $this->createForm(new BuildingType(), $entity, array(
            'action' => $this->generateUrl('building_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Building entity.
     *
     */
    public function newAction()
    {
        $entity = new Building();
        $form   = $this->createCreateForm($entity);

        return $this->render('JamesMannionForumBundle:Building:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Building entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JamesMannionForumBundle:Building')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Building entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JamesMannionForumBundle:Building:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Building entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JamesMannionForumBundle:Building')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Building entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JamesMannionForumBundle:Building:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Building entity.
    *
    * @param Building $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Building $entity)
    {
        $form = $this->createForm(new BuildingType(), $entity, array(
            'action' => $this->generateUrl('building_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Building entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JamesMannionForumBundle:Building')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Building entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('building_edit', array('id' => $id)));
        }

        return $this->render('JamesMannionForumBundle:Building:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Building entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JamesMannionForumBundle:Building')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Building entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('building'));
    }

    /**
     * Creates a form to delete a Building entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('building_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

<?php

namespace JamesMannion\ForumBundle\Controller;

use JamesMannion\ForumBundle\Constants\SuccessFlash;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JamesMannion\ForumBundle\Entity\Post;
use JamesMannion\ForumBundle\Entity\Thread;
use JamesMannion\ForumBundle\Form\PostType;
use JamesMannion\ForumBundle\Constants\Config;
use JamesMannion\ForumBundle\Constants\Title;

class PostController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JamesMannionForumBundle:Post')->findAll();

        return $this->render('JamesMannionForumBundle:Post:index.html.twig', array(
            'systemName'    => Config::SYSTEM_NAME,
            'title'         => Title::POSTS_LIST,
            'entities'      => $entities,
        ));
    }

    /**
     * @param $threadId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction($threadId, Request $request)
    {
        $postToCreate = new Post();

        $form = $this->createCreateForm($threadId, $postToCreate);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Thread $thread */
            $thread = $em->getRepository('JamesMannionForumBundle:Thread')->find($threadId);
            $postToCreate->setThread($thread);
            $postToCreate->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($postToCreate);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                SuccessFlash::POST_CREATED_SUCCESSFULLY
            );

            return $this->redirect(
                $this->generateUrl(
                    'thread_show',
                    array('threadId' => $thread->getId())
                )
            );
        }

        return $this->render('JamesMannionForumBundle:Post:new.html.twig', array(
            'post' => $postToCreate,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @param $threadId
     * @param Post $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm($threadId, Post $entity)
    {
        $form = $this->createForm(new PostType(), $entity, array(
            'action' => $this->generateUrl('post_create', array('threadId' => $threadId)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * @param $threadId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction($threadId)
    {
        $postToCreate = new Post();
        $form = $this->createCreateForm($threadId, $postToCreate);

        return $this->render('JamesMannionForumBundle:Post:new.html.twig', array(
            'systemName'    => Config::SYSTEM_NAME,
            'title'         => Title::POSTS_NEW,
            'post'          => $postToCreate,
            'threadId'      => $threadId,
            'form'          => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $postToShow = $em->getRepository('JamesMannionForumBundle:Post')->find($id);

        if (!$postToShow) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JamesMannionForumBundle:Post:show.html.twig', array(
            'post'      => $postToShow,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JamesMannionForumBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JamesMannionForumBundle:Post:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Post $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Post $entity)
    {
        $form = $this->createForm(new PostType(), $entity, array(
            'action' => $this->generateUrl('post_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JamesMannionForumBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('post_edit', array('id' => $id)));
        }

        return $this->render('JamesMannionForumBundle:Post:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JamesMannionForumBundle:Post')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Post entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('post'));
    }

    /**
     * @param $id
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

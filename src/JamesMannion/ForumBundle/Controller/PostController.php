<?php

namespace JamesMannion\ForumBundle\Controller;

use JamesMannion\ForumBundle\Constants\SuccessFlash;
use JamesMannion\ForumBundle\Event\PostEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JamesMannion\ForumBundle\Entity\Post;
use JamesMannion\ForumBundle\Entity\Thread;
use JamesMannion\ForumBundle\Form\PostType;
use JamesMannion\ForumBundle\Constants\AppConfig;
use JamesMannion\ForumBundle\Constants\Title;

class PostController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $postsToShow = $em->getRepository('JamesMannionForumBundle:Post')->findAll();

        return $this->render('JamesMannionForumBundle:Post:index.html.twig', array(
            'systemName'    => AppConfig::SYSTEM_NAME,
            'title'         => Title::POSTS_LIST,
            'posts'         => $postsToShow,
        ));
    }

    /**
     * @param Thread $thread
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Thread $thread, Request $request)
    {
        $postToCreate = new Post();

        $form = $this->createCreateForm($thread, $postToCreate);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $postToCreate->setThread($thread);
            $postToCreate->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($postToCreate);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                SuccessFlash::POST_CREATED_SUCCESSFULLY
            );

            $dispatcher = $this->container->get('event_dispatcher');
            $dispatcher->dispatch('onPostCreatedEvent', new PostEvent($postToCreate));

            return $this->redirect($this->generateUrl('thread_show',array('id' => $thread->getId())));
        }
        return $this->render('JamesMannionForumBundle:Post:new.html.twig', array(
            'post' => $postToCreate,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @param Thread $threadToCreatePostIn
     * @param Post $postToCreate
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Thread $threadToCreatePostIn, Post $postToCreate)
    {
        $form = $this->createForm(new PostType(), $postToCreate, array(
            'action' => $this->generateUrl('post_create', array('id' => $threadToCreatePostIn->getId())),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * @param Thread $threadToCreatePostIn
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Thread $threadToCreatePostIn)
    {
        $postToCreate = new Post();
        $form = $this->createCreateForm($threadToCreatePostIn, $postToCreate);
        return $this->render('JamesMannionForumBundle:Post:new.html.twig', array(
            'systemName'    => AppConfig::SYSTEM_NAME,
            'title'         => Title::POSTS_NEW,
            'post'          => $postToCreate,
            'thread'        => $threadToCreatePostIn,
            'form'          => $form->createView(),
        ));
    }

    /**
     * @param Post $postToShow
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction(Post $postToShow)
    {
        $deleteForm = $this->createDeleteForm($postToShow);
        return $this->render('JamesMannionForumBundle:Post:show.html.twig', array(
            'post'          => $postToShow,
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param Post $postToEdit
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Post $postToEdit)
    {
        $editForm = $this->createEditForm($postToEdit);
        $deleteForm = $this->createDeleteForm($postToEdit);

        return $this->render('JamesMannionForumBundle:Post:edit.html.twig', array(
            'post'          => $postToEdit,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param Post $post
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Post $post)
    {
        $form = $this->createForm(new PostType(), $post, array(
            'action' => $this->generateUrl('post_update', array('id' => $post->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createEditForm($post);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('post_edit', array('id' => $post->getId())));
        }

        return $this->render('JamesMannionForumBundle:Post:edit.html.twig', array(
            'post'          => $post,
            'edit_form'     => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $postToDelete
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $postToDelete)
    {
        $form = $this->createDeleteForm($postToDelete);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($postToDelete);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('post'));
    }

    /**
     * @param Post $postToDelete
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Post $postToDelete)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $postToDelete->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}

<?php

namespace JamesMannion\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JamesMannion\ForumBundle\Entity\Enquiry;
use JamesMannion\ForumBundle\Form\EnquiryType;
use JamesMannion\ForumBundle\Constants\Config;
use JamesMannion\ForumBundle\Constants\Title;

class EnquiryController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $entity = new Enquiry();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('enquiry_show', array('id' => $entity->getId())));
        }

        return $this->render('JamesMannionForumBundle:Enquiry:new.html.twig', array(
            'systemName'    => Config::SYSTEM_NAME,
            'title'         => Title::ENQUIRIES,
            'entity'        => $entity,
            'form'          => $form->createView(),
        ));
    }

    /**
     * @param Enquiry $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Enquiry $entity)
    {
        $form = $this->createForm(new EnquiryType(), $entity, array(
            'action' => $this->generateUrl('enquiry'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

}

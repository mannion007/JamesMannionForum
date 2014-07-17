<?php

namespace JamesMannion\ForumBundle\Controller;

use JamesMannion\ForumBundle\Constants\Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JamesMannion\ForumBundle\Constants\Title;


class ContentController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {

        return $this->render(
            'JamesMannionForumBundle:Content:index.html.twig',
            array(
                'systemName'    => Config::SYSTEM_NAME,
                'pageTitle'     => Title::HOMEPAGE,
                'contentTitle'  => 'Welcome!',
                'content'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut quis tempor diam,
                                    non mattis ligula. Cras eget suscipit mi. Mauris eget massa euismod, lobortis
                                    tortor in, gravida felis. Nulla facilisi. Nunc in rutrum eros. Maecenas blandit
                                    magna id dui interdum, et commodo purus pulvinar. Nulla facilisi. Sed vitae euismod
                                    felis, sed pulvinar libero. Integer ut risus leo. Vestibulum vitae ornare metus.
                                    Mauris eget tortor ornare, vehicula velit at, pretium eros.'
            )
        );
    }

}

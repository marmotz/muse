<?php

namespace Mattlab\MuseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->redirect(
            $this->generateurl(
                'mattlab_muse_album_display',
                array(
                    'albumPath'   => '',
                    'page'        => 1,
                    'itemPerPage' => $this->container->getParameter('muse.default.item_per_page'),
                )
            )
        );
    }
}

<?php

namespace Mattlab\MuseBundle\Controller;


use Mattlab\MuseBundle\Entity\Gallery;
use Mattlab\MuseBundle\Entity\Item;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AlbumController extends Controller
{
    public function displayAction(Request $request, $albumPath, $page, $itemPerPage)
    {
        $session = $request->getSession();

        $gallery = new Gallery(
            $this->container,
            $albumPath,
            $session->has('user') ? $session->get('user') : null
        );

        $session->set('lastAlbumPath',   $album->getPath());
        $session->set('lastPage',        $page);
        $session->set('lastItemPerPage', $itemPerPage);

        return array(
            'gallery'   => $gallery,
            'page'      => $page,
            'nbPerPage' => $itemPerPage,
        );
    }

}

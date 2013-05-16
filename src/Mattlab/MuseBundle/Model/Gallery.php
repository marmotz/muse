<?php

namespace Mattlab\MuseBundle\Model;


use Doctrine\ORM\EntityManager;
use Mattlab\MuseBundle\Entity\User;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Finder\Finder;

class Gallery {
    protected $itemFactory;
    protected $entityManager;
    protected $album;
    protected $user;
    protected $protections = array();
    protected $items = null;


    public function __construct(Container $container, $albumPath, User $user = null)
    {
        $this
            ->setItemFactory($container->get('muse.item_factory'))
            ->setEntityManager($container->get('doctrine.orm.default_entity_manager'))
            ->setAlbumPath($albumPath)
            ->setUser($user)
        ;
    }


    public function setItemFactory(ItemFactory $itemFactory)
    {
        $this->itemFactory = $itemFactory;

        return $this;
    }

    public function getItemFactory()
    {
        return $this->itemFactory;
    }


    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }


    public function setAlbumPath($albumPath)
    {
        return $this->setAlbum(
            $this->getItemFactory()->get($albumPath)
        );
    }


    public function setAlbum(Album $album)
    {
        $this->album = $album;

        return $this;
    }

    public function getAlbum()
    {
        return $this->album;
    }


    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }


    public function loadProtections()
    {
        $this->protections = array();

        $protections = $this
            ->getEntityManager()
            ->getRepository('Muse\Entity\Protection')
            ->findByAlbumPath(
                $this->getAlbum()->getRelativePath()
            )
        ;

        foreach($protections as $protection) {
            $this->protections[$protection->getPath()] = $protection;
        }

        return $this;
    }

    public function getProtections()
    {
        return $this->protections;
    }

    public function hasProtection($path = null)
    {
        if($path === null) {
            $path = $this->getAlbum()->getRelativePath();
        }

        return isset($this->protections[$path]);
    }

    public function getProtection($path = null)
    {
        if($path === null) {
            $path = $this->getAlbum()->getRelativePath();
        }

        return $this->hasProtection($path) ? $this->protections[$path] : null;
    }

    public function hasParentProtection()
    {
        foreach($this->getAlbum()->getParentPaths() as $key => $path) {
            if($this->hasProtection($path)) {
                return true;
            }
        }

        return false;
    }


    protected function getFinder()
    {
        $finder = new Finder();
        $finder
            ->in($this->getAlbum()->getPath())
            ->sortByName()
            ->depth(0)
        ;

        return $finder;
    }

    public function loadAlbums()
    {
        return $this->addItems(
            $this->getFinder()
            ->directories()
        );
    }

    public function loadPhotos()
    {
        return $this->addItems(
            $this->getFinder()
            ->files()
            ->name('/\.(jpe|jpg|jpeg|png|webp|svg|gif|bmp|muse)$/i')
        );
    }


    protected function addItems($items) {
        foreach($items as $item) {
            $this->addItem($item);
        }

        return $this;
    }

    protected function addItem($item) {
        $this->items[] = $this->getItemFactory()->get($item);

        return $this;
    }


    public function setItems($items)
    {
        if(is_array($items)) {
            $this->items = array_values(
                array_filter(
                    $items,
                    function($item) {
                        return $item instanceof Item;
                    }
                )
            );
        }
        else {
            $this->items = null;
        }

        return $this;
    }


    public function getItems()
    {
        return $this->items ?: array();
    }


    public function getAccessiblesItems()
    {
        if($this->items === null)
        {
            $this->items = array();

            $this
                ->loadProtections()
                ->loadAlbums()
                ->loadPhotos()
            ;
        }

        $gallery = $this;

        return array_values(
            array_filter(
                $this->getItems(),
                function($item) use($gallery) {
                    return $gallery->canAccess(
                        $item->getRelativePath()
                    );
                }
            )
        );
    }


    public function canAccess($path) {
        return
               !$this->hasProtection($path)
            || (
                   $this->getUser() !== null
                && $this->getUser()->getId() === $this->getProtection($path)->getProtector()->getId()
            )
        ;
    }


    public function getPath() {
        return $this->getAlbum()->getRelativePath();
    }

    public function getParentPath() {
        return dirname($this->getPath());
    }

    public function getBreadCrumb() {
        return explode('/', $this->getPath());
    }

    public function isRoot() {
        return $this->getItemFactory()->getGalleryRootPath() === $this->getAlbum()->getPath();
    }
}
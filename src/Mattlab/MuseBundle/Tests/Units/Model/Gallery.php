<?php

namespace Mattlab\MuseBundle\Tests\Units\Model;

use Mattlab\MuseBundle\Tests\Units\BaseTest;

use Mattlab\MuseBundle\Model\Gallery                        as TestedClass;
use Mattlab\MuseBundle\Entity\Protection;
use Mattlab\MuseBundle\Entity\User;

use mock\Doctrine\ORM\EntityManager                         as mockEntityManager;
use mock\Doctrine\ORM\EntityRepository                      as mockRepository;
use mock\Mattlab\MuseBundle\Model\Album                     as mockAlbum;
use mock\Mattlab\MuseBundle\Entity\User                     as mockUser;
use mock\Mattlab\MuseBundle\Model\ItemFactory               as mockItemFactory;
use mock\Symfony\Component\DependencyInjection\Container    as mockContainer;


class Gallery extends BaseTest
{
    public function testConstruct()
    {
        $this
            ->if(list($gallery, $mockItemFactory, $mockEntityManager, $albumPath, $mockUser) = $this->generateGallery())
                ->object($gallery->getItemFactory())
                    ->isIdenticalTo($mockItemFactory)
                ->object($gallery->getEntityManager())
                    ->isIdenticalTo($mockEntityManager)
                ->object($album = $gallery->getAlbum())
                    ->isInstanceOf('Mattlab\MuseBundle\Model\Album')
                ->string($album->getRelativePath())
                    ->isEqualTo($albumPath)
                ->object($gallery->getUser())
                    ->isIdenticalTo($mockUser)
        ;
    }


    public function testSetGetItemFactory()
    {
        $this
            ->if(list($gallery) = $this->generateGallery())
            ->and($mockItemFactory = new mockItemFactory($this->getGalleryRootPath()))
                ->object($gallery->setItemFactory($mockItemFactory))
                    ->isIdenticalTo($gallery)
                ->object($gallery->getItemFactory())
                    ->isIdenticalTo($mockItemFactory)
        ;
    }


    public function testSetGetEntityManager()
    {
        $this
            ->if(list($gallery) = $this->generateGallery())
            ->and($this->mockGenerator->orphanize('__construct'))
            ->and($mockEntityManager = new mockEntityManager)
                ->object($gallery->setEntityManager($mockEntityManager))
                    ->isIdenticalTo($gallery)
                ->object($gallery->getEntityManager())
                    ->isIdenticalTo($mockEntityManager)
        ;
    }


    public function testSetGetAlbum()
    {
        $this
            ->if(list($gallery) = $this->generateGallery())
            ->and($albumPath = 'album1')
                ->object($gallery->setAlbumPath($albumPath))
                    ->isIdenticalTo($gallery)
                ->object($album = $gallery->getAlbum())
                    ->isInstanceOf('Mattlab\MuseBundle\Model\Album')
                ->string($album->getRelativePath())
                    ->isEqualTo($albumPath)

            ->if($mockItemFactory = new mockItemFactory($this->getGalleryRootPath()))
            ->and($mockAlbum = $mockItemFactory->get($albumPath))
                ->object($gallery->setAlbum($mockAlbum))
                    ->isIdenticalTo($gallery)
                ->object($gallery->getAlbum())
                    ->isIdenticalTo($mockAlbum)
        ;
    }


    public function testSetGenerateMockUser()
    {
        $this
            ->if(list($gallery) = $this->generateGallery())
            ->and($mockUser = new mockUser)
                ->object($gallery->setUser($mockUser))
                    ->isIdenticalTo($gallery)
                ->object($gallery->getUser())
                    ->isIdenticalTo($mockUser)
        ;
    }


    public function testProtections()
    {
        $this
            // no protection
            ->if(list($mockEntityManager, $mockRepository) = $this->generateMockEntityManager())
            ->and(list($gallery, , , $albumPath) = $this->generateGallery($mockEntityManager))
            ->and($this->calling($mockRepository)->findByAlbumPath = array())
                ->object($gallery->loadProtections())
                    ->isIdenticalTo($gallery)
                ->array($gallery->getProtections())
                    ->isEmpty()
                ->boolean($gallery->hasProtection())
                    ->isFalse()
                ->boolean($gallery->hasProtection($albumPath))
                    ->isFalse()
                ->variable($gallery->getProtection())
                    ->isNull()
                ->variable($gallery->getProtection($albumPath))
                    ->isNull()

            // 1 protection = albumPath
            ->if($protection = $this->generateProtection($albumPath))
            ->and($this->calling($mockRepository)->findByAlbumPath = array($protection))
                ->object($gallery->loadProtections())
                    ->isIdenticalTo($gallery)
                ->boolean($gallery->hasProtection())
                    ->isTrue()
                ->boolean($gallery->hasProtection($albumPath))
                    ->isTrue()
                ->object($gallery->getProtection())
                    ->isIdenticalTo($protection)
                ->object($gallery->getProtection($albumPath))
                    ->isIdenticalTo($protection)

            // 1 random protection
            ->if(list($mockEntityManager, $mockRepository) = $this->generateMockEntityManager())
            ->and(list($gallery) = $this->generateGallery($mockEntityManager))
            ->and($protection1 = $this->generateProtection($protection1Path = uniqid()))
            ->and($this->calling($mockRepository)->findByAlbumPath = array($protection1))
                ->object($gallery->loadProtections())
                    ->isIdenticalTo($gallery)
                ->array($gallery->getProtections())
                    ->isEqualTo(
                        array(
                            $protection1Path => $protection1
                        )
                    )
                ->object($gallery->getProtection($protection1Path))
                    ->isIdenticalTo($protection1)
                ->boolean($gallery->hasProtection($protection1Path))
                    ->isTrue()
                ->boolean($gallery->hasProtection(uniqid()))
                    ->isFalse()

            // 2 random protections
            ->if($protection2 = $this->generateProtection($protection2Path = uniqid()))
            ->and($this->calling($mockRepository)->findByAlbumPath = array($protection1, $protection2))
                ->object($gallery->loadProtections())
                    ->isIdenticalTo($gallery)
                ->array($gallery->getProtections())
                    ->isEqualTo(
                        array(
                            $protection1Path => $protection1,
                            $protection2Path => $protection2
                        )
                    )
                ->object($gallery->getProtection($protection1Path))
                    ->isIdenticalTo($protection1)
                ->object($gallery->getProtection($protection2Path))
                    ->isIdenticalTo($protection2)
                ->boolean($gallery->hasProtection($protection1Path))
                    ->isTrue()
                ->boolean($gallery->hasProtection($protection2Path))
                    ->isTrue()
                ->boolean($gallery->hasProtection(uniqid()))
                    ->isFalse()


            // hasParentProtection, no protection
            ->if(list($mockEntityManager, $mockRepository) = $this->generateMockEntityManager())
            ->and(list($gallery) = $this->generateGallery($mockEntityManager, $albumPath = 'album1/album1.1/album1.1.1'))
            ->and($this->calling($mockRepository)->findByAlbumPath = array())
            ->and($gallery->loadProtections())
                ->boolean($gallery->hasParentProtection())
                    ->isFalse()

            // hasParentProtection, 1 protection = albumPath
            ->if($protection1 = $this->generateProtection($albumPath))
            ->and($this->calling($mockRepository)->findByAlbumPath = array($protection1))
            ->and($gallery->loadProtections())
                ->boolean($gallery->hasParentProtection())
                    ->isFalse()

            // hasParentProtection, 2 protections = albumPath + parent
            ->if($protection2 = $this->generateProtection(dirname($albumPath)))
            ->and($this->calling($mockRepository)->findByAlbumPath = array($protection1, $protection2))
            ->and($gallery->loadProtections())
                ->boolean($gallery->hasParentProtection())
                    ->isTrue()

            // hasParentProtection, 2 protections = albumPath + grand parent
            ->if($protection3 = $this->generateProtection(dirname(dirname($albumPath))))
            ->and($this->calling($mockRepository)->findByAlbumPath = array($protection1, $protection3))
            ->and($gallery->loadProtections())
                ->boolean($gallery->hasParentProtection())
                    ->isTrue()

            // hasParentProtection, 2 protections = albumPath + parent + grand parent
            ->if($this->calling($mockRepository)->findByAlbumPath = array($protection1, $protection2, $protection3))
            ->and($gallery->loadProtections())
                ->boolean($gallery->hasParentProtection())
                    ->isTrue()
        ;
    }


    public function testSetGetItems()
    {
        $this
            ->if(list($gallery, $mockItemFactory) = $this->generateGallery())
                ->array($gallery->getItems())
                    ->isEmpty()

                ->object($gallery->setItems(null))
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEmpty()

                ->object($gallery->setItems(uniqid()))
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEmpty()

                ->object($gallery->setItems(array()))
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEmpty()

                ->object($gallery->setItems(array(uniqid())))
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEmpty()

                ->object(
                    $gallery->setItems(
                        array(
                            $mockItemFactory->get('album1'),
                            $mockItemFactory->get('album1/photo1.jpg')
                        )
                    )
                )
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEqualTo(
                        array(
                            $mockItemFactory->get('album1'),
                            $mockItemFactory->get('album1/photo1.jpg')
                        )
                    )

                ->object(
                    $gallery->setItems(
                        array(
                            $mockItemFactory->get('album1'),
                            $mockItemFactory->get('album1/photo1.jpg'),
                            uniqid()
                        )
                    )
                )
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEqualTo(
                        array(
                            $mockItemFactory->get('album1'),
                            $mockItemFactory->get('album1/photo1.jpg')
                        )
                    )
        ;
    }


    public function testLoadAlbums()
    {
        $this
            ->if(list($gallery, $mockItemFactory) = $this->generateGallery(null, 'album1'))
                ->object($gallery->loadAlbums())
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEqualTo(
                        array(
                            $mockItemFactory->get('album1/album1.1'),
                            $mockItemFactory->get('album1/album1.2'),
                            $mockItemFactory->get('album1/album1.3'),
                        )
                    )

            ->if(list($gallery) = $this->generateGallery(null, 'album1/album1.2'))
                ->object($gallery->loadAlbums())
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEmpty()
        ;
    }


    public function testLoadPhotos()
    {
        $this
            ->if(list($gallery, $mockItemFactory) = $this->generateGallery(null, 'album1'))
                ->object($gallery->loadPhotos())
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEqualTo(
                        array(
                            $mockItemFactory->get('album1/photo1.jpg'),
                            $mockItemFactory->get('album1/photo2.jpg'),
                        )
                    )

            ->if(list($gallery) = $this->generateGallery(null, 'album1/album1.1'))
                ->object($gallery->loadPhotos())
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEqualTo(
                        array(
                            $mockItemFactory->get('album1/album1.1/photo1.jpg.muse'),
                        )
                    )

            ->if(list($gallery) = $this->generateGallery(null, 'album1/album1.2'))
                ->object($gallery->loadPhotos())
                    ->isIdenticalTo($gallery)
                ->array($gallery->getItems())
                    ->isEmpty()
        ;
    }


    public function testCanAccess()
    {
        $this
            ->if(list($mockEntityManager, $mockRepository) = $this->generateMockEntityManager())
            ->and(list($gallery, , , $albumPath) = $this->generateGallery($mockEntityManager, 'album1'))
            ->and($this->calling($mockRepository)->findByAlbumPath = array())
                ->object($gallery->loadProtections())
                    ->isIdenticalTo($gallery)
                ->boolean($gallery->canAccess($albumPath))
                    ->isTrue()

            ->if($protection = $this->generateProtection($albumPath, $user = $this->generateMockUser(1)))
            ->and($this->calling($mockRepository)->findByAlbumPath = array($protection))
                ->object($gallery->setUser($user))
                    ->isIdenticalTo($gallery)
                ->object($gallery->loadProtections())
                    ->isIdenticalTo($gallery)
                ->boolean($gallery->canAccess($albumPath))
                    ->isTrue()

            ->if($protection = $this->generateProtection($albumPath, $this->generateMockUser(1)))
            ->and($this->calling($mockRepository)->findByAlbumPath = array($protection))
                ->object($gallery->setUser($this->generateMockUser(2)))
                    ->isIdenticalTo($gallery)
                ->object($gallery->loadProtections())
                    ->isIdenticalTo($gallery)
                ->boolean($gallery->canAccess($albumPath))
                    ->isFalse()
        ;
    }


    public function testGetAccessiblesItems()
    {
        $this
            ->if(list($mockEntityManager, $mockRepository) = $this->generateMockEntityManager())
            ->if(list($gallery, $mockItemFactory, , $albumPath) = $this->generateGallery($mockEntityManager))
                ->array($gallery->getAccessiblesItems())
                    ->isEqualTo(
                        array(
                            $mockItemFactory->get('album1/album1.1'),
                            $mockItemFactory->get('album1/album1.2'),
                            $mockItemFactory->get('album1/album1.3'),
                            $mockItemFactory->get('album1/photo1.jpg'),
                            $mockItemFactory->get('album1/photo2.jpg'),
                        )
                    )

            ->if($protection = $this->generateProtection('album1/album1.1', $this->generateMockUser(1)))
            ->and($this->calling($mockRepository)->findByAlbumPath = array($protection))
            ->and($gallery->setUser($this->generateMockUser(2)))
            ->and($gallery->setItems(null))
                ->array($gallery->getAccessiblesItems())
                    ->isEqualTo(
                        array(
                            $mockItemFactory->get('album1/album1.2'),
                            $mockItemFactory->get('album1/album1.3'),
                            $mockItemFactory->get('album1/photo1.jpg'),
                            $mockItemFactory->get('album1/photo2.jpg'),
                        )
                    )
        ;
    }


    public function testGetPath()
    {
        $this
            ->if(list($gallery, , , $albumPath) = $this->generateGallery())
                ->string($gallery->getPath())
                    ->isEqualTo($albumPath)
        ;
    }


    public function testGetParentPath()
    {
        $this
            ->if(list($gallery, , , $albumPath) = $this->generateGallery())
                ->string($gallery->getParentPath())
                    ->isEqualTo(dirname($albumPath))
        ;
    }


    public function testGetBreadCrumb()
    {
        $this
            ->if(list($gallery, , , $albumPath) = $this->generateGallery())
                ->array($gallery->getBreadCrumb())
                    ->isEqualTo(explode('/', $albumPath))
        ;
    }


    public function testIsRoot()
    {
        $this
            ->if(list($gallery, , , $albumPath) = $this->generateGallery(null, ''))
                ->boolean($gallery->isRoot())
                    ->isTrue()

            ->if(list($gallery, , , $albumPath) = $this->generateGallery(null, 'album1'))
                ->boolean($gallery->isRoot())
                    ->isFalse()
        ;
    }



    /////////////
    // HELPERS //
    /////////////
    private function generateGallery(mockEntityManager $mockEntityManager = null, $albumPath = null)
    {
        // ItemFactory
        $mockItemFactory = new mockItemFactory($this->getGalleryRootPath());

        // EntityManager
        if($mockEntityManager === null) {
            list($mockEntityManager) = $this->generateMockEntityManager();
        }

        // Container
        $this->mockGenerator->orphanize('__construct');
        $mockContainer = new mockContainer;
        $this->calling($mockContainer)->get = function($service) use($mockItemFactory, $mockEntityManager)
        {
            switch($service)
            {
                case 'muse.item_factory':
                    return $mockItemFactory;
                break;

                case 'doctrine.entity_managers':
                    return $mockEntityManager;
                break;
            }
        };

        // albumPath
        if($albumPath === null)
        {
            $albumPath = 'album1';
        }

        // User
        $mockUser = new mockUser;

        $gallery = new TestedClass($mockContainer, $albumPath, $mockUser);

        return array(
            $gallery,
            $mockItemFactory,
            $mockEntityManager,
            $albumPath,
            $mockUser
        );
    }

    private function generateMockEntityManager()
    {
        // EntityRepository
        $this->mockGenerator->orphanize('__construct');
        $mockRepository = new mockRepository;
        $this->calling($mockRepository)->findByAlbumPath = array();

        // EntityManager
        $this->mockGenerator->orphanize('__construct');
        $mockEntityManager = new mockEntityManager;
        $this->calling($mockEntityManager)->getRepository = $mockRepository;

        return array(
            $mockEntityManager,
            $mockRepository
        );

    }

    private function generateMockUser($id)
    {
        $mockUser = new mockUser;
        $this->calling($mockUser)->getId = $id;

        return $mockUser;
    }

    private function generateProtection($albumPath, mockUser $mockUser = null)
    {
        $protection = new Protection;
        $protection->setPath($albumPath);

        if($mockUser !== null) {
            $protection->setProtector($mockUser);
        }

        return $protection;
    }
}
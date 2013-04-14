<?php

namespace Muse\Entity\Repository;

use Muse\Entity;
use Doctrine\ORM\EntityRepository;

class Protection extends EntityRepository {
    public function findByAlbumPath($albumPath) {
        $dql = '
            SELECT  p
            FROM    Muse\Entity\Protection p
            WHERE   p.path LIKE ?0
        ';

        $parameters = array($albumPath . '/%');

        foreach(Entity\Protection::getParentPaths($albumPath) as $key => $path) {
            $dql .= ' OR p.path = ?' . ($key + 1);

            $parameters[] = $path;
        }

        $dql .= '
            ORDER BY p.path DESC
        ';

        return $this->_em
            ->createQuery($dql)
            ->setParameters($parameters)
            ->getResult()
        ;
    }
}
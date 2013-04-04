<?php

namespace Muse\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class Protection extends EntityRepository {
    public function findOneByAlbumPath($albumPath) {
        $dql = '
            SELECT  p
            FROM    Muse\Entity\Protection p
            WHERE   p.path = ?0
        ';

        $parameters = array($albumPath);

        $cpt = 0;

        while(dirname($albumPath) != '.') {
            $dql .= ' OR p.path = ?' . ++$cpt;

            $parameters[] = $albumPath = dirname($albumPath);
        }

        $dql .= '
            ORDER BY p.path DESC
        ';

        return $this->_em
            ->createQuery($dql)
            ->setParameters($parameters)
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }
}
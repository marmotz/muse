<?php

namespace Muse\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class Protection extends EntityRepository {
    public function findByAlbumPath($albumPath) {
        $dql = '
            SELECT  p
            FROM    Muse\Entity\Protection p
            WHERE   p.path LIKE ?0
                OR  p.path = ?1
        ';

        $parameters = array(
            $albumPath . '/%',
            $albumPath
        );

        $cpt = 1;

        while(!in_array(dirname($albumPath), array('.', ''))) {
            $dql .= ' OR p.path = ?' . ++$cpt;

            $parameters[] = $albumPath = dirname($albumPath);
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
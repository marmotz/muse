<?php
namespace Muse\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class User extends EntityRepository {
    // public function findOneByLoginOrEmail($loginoremail) {
    //     return $this->_em
    //         ->createQuery(
    //             '
    //                 SELECT  u
    //                 FROM    Muse\Entity\User u
    //                 WHERE   u.login = :login
    //                     OR  u.email = :email
    //             '
    //         )
    //         ->setParameters(
    //             array(
    //                 'login' => $loginoremail,
    //                 'email' => $loginoremail,
    //             )
    //         )
    //         ->getOneOrNullResult()
    //     ;
    // }
}
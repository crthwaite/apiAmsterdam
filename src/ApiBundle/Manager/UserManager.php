<?php

namespace ApiBundle\Manager;


use ApiBundle\Entity\Repository\UserRepository;

class UserManager extends CoreManager
{
    public function findOneByEmail($email)
    {
        return $this->getRepository()->findOneBy(array("email" => $email));
    }

    /** @return UserRepository */
    protected function getRepository()
    {
        return parent::getRepository();
    }
}
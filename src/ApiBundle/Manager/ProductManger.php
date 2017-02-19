<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\Repository\ProductRepository;

class ProductManger extends CoreManager
{
    public function findById($id)
    {
        return $this->getRepository()->find($id);
    }
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @return ProductRepository
     */
    protected function getRepository()
    {
        return parent::getRepository();
    }
}
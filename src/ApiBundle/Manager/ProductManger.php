<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\Repository\ProductRepository;

class ProductManger extends CoreManager
{
    /**
     * @return ProductRepository
     */
    protected function getRepository()
    {
        return parent::getRepository();
    }
}
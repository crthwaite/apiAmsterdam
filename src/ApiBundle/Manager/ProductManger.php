<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\Product;
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

    public function parseListProduct(Product $product)
    {
        $product_info = array(
            'id' => $product->getId(),
            'name' => $product->getName(),
            'available' => $product->getAvailable(),
            'price' => $product->getPrice()
        );

        return $product_info;
    }

    public function parseSingleProduct(Product $product)
    {
        $product_info = array(
            'id' => $product->getId(),
            'name' => $product->getName(),
            'available' => $product->getAvailable(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'created' => $product->getDateCreated()->format('Y-m-d')
        );

        return $product_info;
    }

    /**
     * @return ProductRepository
     */
    protected function getRepository()
    {
        return parent::getRepository();
    }
}
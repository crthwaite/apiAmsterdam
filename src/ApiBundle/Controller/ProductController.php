<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Product;
use ApiBundle\Form\ProductType;
use ApiBundle\Manager\ProductManger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/products")
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="api_product_index")
     * @Template()
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('view');

        $products = $this->getProductManager()->findAll();

        return array("products" => $products);
    }

    /**
     * @Route("/new", name="api_product_new")
     * @Template()
     */
    public function newAction()
    {
        $product = new Product();

        $this->denyAccessUnlessGranted('edit', $product);

        $form = $this->createCreateForm($product);

        return array("form" => $form->createView());
    }

    /**
     * @Route("/create", name="api_product_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $product = new Product();

        $this->denyAccessUnlessGranted('edit', $product);

        $form = $this->createCreateForm($product);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $pm = $this->getProductManager();

            $pm->persist($product);
            $pm->flush();

            return $this->redirectToRoute('api_product_index');
        }

        return array("form" => $form->createView());
    }

    /**
     * @Route("/{id}/edit", name="api_product_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $pm = $this->getProductManager();

        /** @var Product $product */
        $product = $pm->findById($id);

        $this->denyAccessUnlessGranted('edit', $product);

        $form = $this->createUpdateForm($product);

        return array("form" => $form->createView(), "product" => $product);
    }

    /**
     * @Route("/{id}/update", name="api_product_update")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $pm = $this->getProductManager();

        /** @var Product $product */
        $product = $pm->findById($id);

        $this->denyAccessUnlessGranted('edit', $product);

        $form = $this->createUpdateForm($product);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $pm->persist($product);
            $pm->flush();

            return $this->redirectToRoute('api_product_index');
        }

        return array("form" => $form->createView(), "product" => $product);
    }

    /**
     * @Route("/{id}/delete", name="api_product_delete")
     */
    public function deleteAction($id)
    {
        $pm = $this->getProductManager();

        /** @var Product $product */
        $product = $pm->findById($id);

        $this->denyAccessUnlessGranted('edit', $product);

        $pm->remove($product);
        $pm->flush();

        return $this->redirectToRoute('api_product_index');
    }

    /**
     * @param $product
     *
     * @return Form
     */
    private function createCreateForm($product)
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * @param $product
     *
     * @return Form
     */
    private function createUpdateForm($product)
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->add('submit', SubmitType::class, array('label' => 'Save'));

        return $form;
    }

    /**
     * @return ProductManger
     */
    private function getProductManager()
    {
        return $this->get('api.product_manager');
    }
}
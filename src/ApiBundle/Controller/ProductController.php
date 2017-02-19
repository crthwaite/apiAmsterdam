<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Product;
use ApiBundle\Form\ProductType;
use ApiBundle\Manager\ProductManger;
use ApiBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

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
        $this->denyAccessUnlessGranted('view', new Product());

        $products = $this->getProductManager()->findAll();

        return array("products" => $products);
    }

    /**
     * @Route("/list/{email}/{password}", name="api_product_list")
     */
    public function apiGetList($email, $password) {
        $this->checkCredentials($email, $password);

        $pm = $this->getProductManager();

        $products = $pm->findAll();

        $data = array();

        /** @var Product $product */
        foreach($products as $product)
        {
            $data[] = $pm->parseListProduct($product);
        }

        $data = json_encode($data);

        return new JsonResponse($data);
    }

    /**
     * @Route("/{id}/detail/{email}/{password}", name="api_product_detail")
     */
    public function apiGetDetail($id, $email, $password) {
        $this->checkCredentials($email, $password);

        $pm = $this->getProductManager();

        $product = $pm->findById($id);

        $data = $pm->parseSingleProduct($product);

        $data = json_encode($data);

        return new JsonResponse($data);
    }

    /**
     * @Route("/{id}/show", name="api_product_show")
     * @Template()
     */
    public function showAction($id)
    {
        $pm = $this->getProductManager();

        /** @var Product $product */
        $product = $pm->findById($id);

        $this->denyAccessUnlessGranted('view', $product);

        return array("product" => $product);
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
     * @Template("ApiBundle:Product:new.html.twig")
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
     * @Template("ApiBundle:Product:edit.html.twig")
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

    private function checkCredentials($email, $password)
    {
        $um = $this->getUserManager();

        $user = $um->findOneByEmail($email);

        if ($user) {
            $encoder_service = $this->get('security.encoder_factory');
            $encoder = $encoder_service->getEncoder($user);

            if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
                return;
            } else {
                throw new AccessDeniedException();
            }
        } else {
            throw new AccessDeniedException();
        }
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

    /**
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->get('api.user_manager');
    }
}
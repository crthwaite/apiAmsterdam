<?php

namespace AppBundle\Controller;

use ApiBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            return $this->redirectToRoute('api_product_index');
        }

        return $this->redirectToRoute('fos_user_security_login');
    }
}

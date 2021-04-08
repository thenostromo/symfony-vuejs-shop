<?php

namespace App\Controller\Admin;

use Imagine\Gd\Imagine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DashboardController extends AbstractController
{
    private $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    /**
     * @Route("/dashboard", name="admin_dashboard_show")
     */
    public function dashboard(): Response
    {
        return $this->render('admin/utils/dashboard.html.twig');
    }
}

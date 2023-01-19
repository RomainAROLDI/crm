<?php

namespace App\Controller\Api;

use App\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerApiController extends AbstractController
{
    #[Route('/api/customer', name: 'app_api_customer')]
    public function index(CustomerService $service): Response
    {
        return $this->json($service->getAll(), 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
}

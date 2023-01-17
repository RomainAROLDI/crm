<?php

namespace App\Controller;

use App\Model\PaginatedDataModel;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private CustomerRepository $customerRepository;
    private PaginatorInterface $pagination;

    public function __construct(CustomerRepository $customerRepository, PaginatorInterface $pagination)
    {
        $this->customerRepository = $customerRepository;
        $this->pagination = $pagination;
    }

    #[Route('/clients', name: 'app_customer')]
    public function index(Request $request): Response
    {
        $items = $this->pagination->paginate(
            $this->customerRepository->getPaginationQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('customer/index.html.twig', [
            'paginated_data' => (new PaginatedDataModel($items, [
                'Prénom' => 'firstName',
                'Nom' => 'lastName',
                'Email' => 'email',
                'Entreprise' => 'company',
                'Créé par' => 'createdBy'
            ]))->getData()
        ]);
    }

    #[Route('/clients/delete/{id}', name: 'app_customer_delete', methods: ['POST'])]
    public function deleteUser(Request $request, int $id): Response
    {
        if (!empty($id)) {
            $this->customerRepository->remove($this->customerRepository->find($id), true);
        }

        return $this->redirectToRoute('app_customer', [], 301);
    }
}

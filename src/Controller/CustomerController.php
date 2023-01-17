<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CreateCustomerType;
use App\Model\PaginatedDataModel;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('clients/creer', name: 'app_customer_create')]
    public function createUser(Request $request,CustomerRepository $customerRepository): Response
    {
        $customer = new Customer();

        $form = $this->createForm(CreateCustomerType::class, $customer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($customer);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('app_customer');
        }

        return $this->render('customer/creer.html.twig',[
            'form' => $form->createView(),
        ]);
        }

}

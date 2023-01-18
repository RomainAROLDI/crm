<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Form\CreateCustomerType;
use App\Model\PaginatedDataModel;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class CustomerController extends AbstractController
{
    private CustomerRepository $customerRepository;
    private PaginatorInterface $pagination;
    private Security $security;

    public function __construct(CustomerRepository $customerRepository, PaginatorInterface $pagination, Security $security)
    {
        $this->customerRepository = $customerRepository;
        $this->pagination = $pagination;
        $this->security = $security;
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
    public function deleteUser(int $id): Response
    {
        if (!empty($id)) {
            $customer = $this->customerRepository->find($id);
            if (empty($customer)) {
                $this->addFlash('error', "Le client n'existe pas dans la base de données.");
            } else {
                $this->addFlash('success', 'Le client ' . $customer->getFirstName() . ' ' . $customer->getLastName() .
                    ' (#' . $customer->getId() . ') a bien été supprimé.');
                $this->customerRepository->remove($customer, true);
            }
        } else {
            $this->addFlash('error', 'Une erreur est survenue, veuillez réessayer.');
        }

        return $this->redirectToRoute('app_customer', [], 301);
    }

    #[Route('/client/edit/{id}', name: 'app_customer_update', methods: ['POST'])]
    public function editUser(Request $request, Customer $customer, int $id): Response
    {
        if (!empty($id)) {

            $form = $this->createForm(CustomerType::class, $customer);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $this->addFlash('success', 'Le client ' . $customer->getFirstName() . ' ' . $customer->getLastName() .
                    ' (#' . $customer->getId() . ') a bien été modifié.');
                $this->customerRepository->save($customer, true);

                return $this->redirectToRoute('app_customer', [], 301);
            }

            return $this->renderForm('customer/edit.html.twig', [
                'customer' => $customer,
                'form' => $form
            ]);

        } else {
            $this->addFlash('error', 'Une erreur est survenue, veuillez réessayer.');
            return $this->redirectToRoute('app_customer', [], 301);
        }
    }

    #[Route('clients/creer', name: 'app_customer_create')]
    public function createUser(Request $request): Response
    {
        $customer = new Customer();

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customer->setCreatedBy($this->security->getUser());
            $this->customerRepository->save($customer, true);
            $this->addFlash('success', 'Le client ' . $customer->getFirstName() . ' ' . $customer->getLastName() .
                ' a bien été ajouté.');

            return $this->redirectToRoute('app_customer', [], 301);
        }

        return $this->render('customer/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

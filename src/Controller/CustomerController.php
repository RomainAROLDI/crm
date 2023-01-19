<?php

namespace App\Controller;

use App\Cmd\Customer\EditCustomerFormCmd;
use App\Form\EditCustomerFormType;
use App\Model\PaginatedDataModel;
use App\Service\CustomerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    private CustomerService $service;
    private PaginatorInterface $pagination;

    public function __construct(CustomerService $service, PaginatorInterface $pagination)
    {
        $this->service = $service;
        $this->pagination = $pagination;
    }

    #[Route('/clients', name: 'app_customer')]
    public function index(Request $request): Response
    {
        $items = $this->pagination->paginate(
            $this->service->getAllQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('customer/index.html.twig', [
            'paginated_data' => (new PaginatedDataModel($items, [
                'Id' => 'id',
                'Prénom' => 'firstName',
                'Nom' => 'lastName',
                'Email' => 'email',
                'Entreprise' => 'companyName',
                'Poste' => 'jobTitle',
                'Créé par' => 'createdBy'
            ]))->getData()
        ]);
    }

    #[Route('clients/creer', name: 'app_customer_create')]
    public function createUser(Request $request): Response
    {
        $cmd = new EditCustomerFormCmd();
        $form = $this->createForm(EditCustomerFormType::class, $cmd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->service->create($cmd, $this->getUser());

            $this->addFlash('success', 'Le client ' . $cmd->getFirstName() . ' ' . $cmd->getLastName() .
                ' a bien été ajouté.');

            return $this->redirectToRoute('app_customer', [], 301);
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Ajout d\'un client',
            'bouton_libelle' => 'Ajouter un client',
            'route_name' => 'app_customer'
        ]);
    }

    #[Route('/client/modifier/{id}', name: 'app_customer_update', methods: ['POST'])]
    public function updateUser(Request $request, int $id): Response
    {
        try {

            $cmd = $this->service->getEditCustomerFormCmdFromEntityById($id);
            $form = $this->createForm(EditCustomerFormType::class, $cmd);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $this->service->update($cmd, $id);
                $this->addFlash('success', 'Le client ' . $cmd->getFirstName() . ' ' . $cmd->getLastName() .
                    ' (#' . $id . ') a bien été modifié.');

                return $this->redirectToRoute('app_customer', [], 301);
            }

            return $this->render('form.html.twig', [
                'form' => $form->createView(),
                'title' => 'Modification d\'un client',
                'bouton_libelle' => 'Sauvegarder les modifications',
                'route_name' => 'app_customer'
            ]);

        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_customer', [], 301);
        }
    }

    #[Route('/clients/delete/{id}', name: 'app_customer_delete', methods: ['POST'])]
    public function deleteUser(int $id): Response
    {
        try {
            $cmd = $this->service->getEditCustomerFormCmdFromEntityById($id);
            $this->service->delete($id);
            $this->addFlash('success', 'Le client ' . $cmd->getFirstName() . ' ' . $cmd->getLastName() .
                ' (#' . $id . ') a bien été supprimé.');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_customer', [], 301);
    }
}

<?php

namespace App\Controller;

use App\Model\PaginatedDataModel;
use App\Repository\CompanyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    private CompanyRepository $companyRepository;
    private PaginatorInterface $pagination;

    public function __construct(CompanyRepository $companyRepository, PaginatorInterface $pagination)
    {
        $this->companyRepository = $companyRepository;
        $this->pagination = $pagination;
    }

    #[Route('/entreprises', name: 'app_company')]
    public function index(Request $request): Response
    {
        $items = $this->pagination->paginate(
            $this->companyRepository->getPaginationQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('company/index.html.twig', [
            'paginated_data' => (new PaginatedDataModel($items, [
                'Siret' => 'siret',
                'Nom' => 'name',
                'Adresse' => 'street',
                'Ville' => 'city',
                'Code postal' => 'zipCode',
            ]))->getData()
        ]);
    }
}

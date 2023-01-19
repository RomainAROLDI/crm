<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobFormType;
use App\Model\PaginatedDataModel;
use App\Service\JobService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    private PaginatorInterface $pagination;

    public function __construct(PaginatorInterface $pagination)
    {
        $this->pagination = $pagination;
    }

    #[Route('/postes', name: 'app_job')]
    public function index(JobService $service, Request $request): Response
    {

        $items = $this->pagination->paginate(
            $service->getAllQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('job/index.html.twig', [
            'paginated_data' => (new PaginatedDataModel($items, [
                'Id' => 'id',
                'Titre' => 'title'
            ]))->getData()
        ]);
    }

    #[Route('postes/creer', name: 'app_job_create')]
    public function createJob(Request $request): Response
    {
        $job = new Job();

        $form = $this->createForm(JobFormType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'Le poste ' . $job->getTitle() . ' a bien été ajouté.');

            return $this->redirectToRoute('app_job', [], 301);
        }

        return $this->render('job/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

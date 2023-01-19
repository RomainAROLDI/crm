<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\Company\CompanyItemDTO;
use App\Repository\CompanyRepository;
use Doctrine\ORM\Query;

class CompanyService implements AppServiceInterface
{
    private CompanyRepository $repository;

    public function __construct(CompanyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->getAllQuery()->getResult();
    }

    public function getAllQuery(): Query
    {
        $query = 'NEW ' . CompanyItemDTO::class . '(c.id, c.name, c.siret, c.street, c.city, c.zipCode)';
        return $this->repository->createQueryBuilder('c')->select($query)->getQuery();
    }

    public function get(int $id): CompanyItemDTO
    {
        $company = $this->repository->find($id);
        return new CompanyItemDTO(
            $company->getId(),
            $company->getName(),
            $company->getSiret(),
            $company->getStreet(),
            $company->getCity(),
            $company->getZipCode()
        );
    }

    public function create()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
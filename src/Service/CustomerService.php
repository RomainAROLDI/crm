<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\Customer\CustomerItemDTO;
use App\Repository\CustomerRepository;
use Doctrine\ORM\Query;

class CustomerService implements AppServiceInterface
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->getAllQuery()->getResult();
    }

    public function getAllQuery(): Query
    {
        $query = 'NEW ' . CustomerItemDTO::class . '(c.id, c.lastName, c.firstName, c.email, co.name, j.title, u.email)';
        return $this->repository->createQueryBuilder('c')->select($query)->leftJoin('c.job', 'j')
            ->leftJoin('c.company', 'co')->leftJoin('c.createdBy', 'u')->getQuery();
    }

    public function get(int $id): CustomerItemDTO
    {
        $customer = $this->repository->find($id);
        return new CustomerItemDTO(
            $customer->getId(),
            $customer->getLastName(),
            $customer->getFirstName(),
            $customer->getEmail(),
            $customer->getCompany()->getName(),
            $customer->getJob()->getTitle(),
            $customer->getCreatedBy()->getEmail()
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
<?php
declare(strict_types=1);

namespace App\Service;

use App\Cmd\Customer\EditCustomerFormCmd;
use App\DTO\Customer\CustomerItemDTO;
use App\Entity\Customer;
use App\Entity\User;
use App\Repository\CustomerRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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

        return $this->repository
            ->createQueryBuilder('c')
            ->select($query)
            ->leftJoin('c.job', 'j')
            ->leftJoin('c.company', 'co')
            ->leftJoin('c.createdBy', 'u')
            ->getQuery();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function get(int $id): CustomerItemDTO
    {
        $query = 'NEW ' . CustomerItemDTO::class . '(c.id, c.lastName, c.firstName, c.email, co.name, j.title, u.email)';

        return $this->repository
            ->createQueryBuilder('c')
            ->select($query)
            ->leftJoin('c.job', 'j')
            ->leftJoin('c.company', 'co')
            ->leftJoin('c.createdBy', 'u')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function create(EditCustomerFormCmd $cmd, User $currentUser)
    {
        $customer = (new Customer())
            ->setFirstName(ucfirst($cmd->getFirstName()))
            ->setLastName(strtoupper($cmd->getLastName()))
            ->setEmail($cmd->getEmail())
            ->setCreatedBy($currentUser);

        if (!empty($cmd->getCompany())) {
            $customer->setCompany($cmd->getCompany());
        }

        if (!empty($cmd->getJob())) {
            $customer->setJob($cmd->getJob());
        }

        $this->repository->save($customer, true);
    }

    /**
     * Fetch a EditCustomerFormCmd only if the entity exists in the db.
     *
     * @param int $id
     * @return EditCustomerFormCmd
     * @throws \Exception
     */
    public function getEditCustomerFormCmdFromEntityById(int $id): EditCustomerFormCmd
    {
        $customer = $this->repository->find($id);

        if (empty($customer)) {
            throw new \Exception("Impossible de récupérer les informations d'un client qui n'existe pas...");
        }

        return new EditCustomerFormCmd($customer);
    }

    /**
     * @throws \Exception
     */
    public function update(EditCustomerFormCmd $cmd, int $id)
    {
        if (empty($id)) {
            throw new \Exception("Une erreur s'est produite lors de la mise à jour.");
        }

        $customer = $this->repository->find($id);
        if (empty($customer)) {
            throw new \Exception("Impossible de mettre à jour un client qui n'existe pas...");
        }

        $customer->setFirstName(ucfirst($cmd->getFirstName()))
            ->setLastName(strtoupper($cmd->getLastName()))
            ->setEmail($cmd->getEmail())
            ->setCompany($cmd->getCompany())
            ->setJob($cmd->getJob());

        $this->repository->save($customer, true);
    }

    /**
     * @throws \Exception
     */
    public function delete(int $id)
    {
        if (empty($id)) {
            throw new \Exception("Une erreur s'est produite lors de la suppression.");
        }

        $customer = $this->repository->find($id);
        if (empty($customer)) {
            throw new \Exception("Impossible de supprimer un client qui n'existe pas...");
        }

        $this->repository->remove($customer, true);
    }
}
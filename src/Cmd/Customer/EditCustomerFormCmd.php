<?php

namespace App\Cmd\Customer;

use App\Entity\Customer;
use App\Entity\Job;
use App\Entity\Company;
use Symfony\Component\Validator\Constraints as Assert;

class EditCustomerFormCmd
{
    #[Assert\NotBlank()]
    private string $firstName;

    #[Assert\NotBlank()]
    private string $lastName;

    #[Assert\NotBlank()]
    #[Assert\Email()]
    private ?string $email;

    private ?Company $company;

    private ?Job $job;

    public function __construct(?Customer $customer = null)
    {
        if (!empty($customer)) {
            $this->firstName = $customer->getFirstName();
            $this->lastName = $customer->getLastName();
            $this->email = $customer->getEmail();
            $this->company = $customer->getCompany();
            $this->job = $customer->getJob();
        }
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     */
    public function setCompany(?Company $company): void
    {
        $this->company = $company;
    }

    /**
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @param Job|null $job
     */
    public function setJob(?Job $job): void
    {
        $this->job = $job;
    }
}
<?php
declare(strict_types=1);

namespace App\DTO\Customer;

class CustomerItemDTO
{
    private int $id;
    private string $lastName;
    private string $firstName;
    private ?string $email;
    private ?string $companyName;
    private ?string $jobTitle;
    private string $createdBy;

    public function __construct(
        int     $id,
        string  $lastName,
        string  $firstName,
        ?string $email,
        ?string $companyName,
        ?string $jobTitle,
        string  $createdBy
    )
    {
        $this->id = $id;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->email = $email;
        $this->companyName = $companyName;
        $this->jobTitle = $jobTitle;
        $this->createdBy = $createdBy;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @return string|null
     */
    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * @return string
     */
    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }
}
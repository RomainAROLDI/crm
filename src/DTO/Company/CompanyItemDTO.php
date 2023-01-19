<?php
declare(strict_types=1);

namespace App\DTO\Company;

class CompanyItemDTO
{
    private int $id;
    private string $name;
    private ?string $siret;
    private ?string $street;
    private ?string $city;
    private ?string $zipCode;

    public function __construct(
        int     $id,
        string  $name,
        ?string $siret,
        ?string $street,
        ?string $city,
        ?string $zipCode
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->siret = $siret;
        $this->street = $street;
        $this->city = $city;
        $this->zipCode = $zipCode;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }
}
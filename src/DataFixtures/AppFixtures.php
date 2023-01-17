<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @throws \Exception
     */
    function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@crm.fr');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'toto'));
        $manager->persist($user);

        for ($i = 0; $i < 500; $i++) {
            $customer = new Customer();
            $customer->setFirstName('Client ' . $i);
            $customer->setLastName('Doe');
            $customer->setEmail('client' . $i . '@gmail.com');
            $customer->setCreatedBy($user);

            $manager->persist($customer);

            $company = new Company();
            $company->setName('Company ' . $i);
            $company->setCity('Metz');
            $company->setSiret($this->getRandomSiret());
            $company->setStreet($i . ' rue Serpenoise');
            $company->setZipCode('57000');
            $company->addCustomer($customer);

            $manager->persist($company);
        }

        $manager->flush();
    }

    /**
     * @throws \Exception
     */
    public function getRandomSiret(): string
    {
        $siret = '';
        for ($i = 0; $i < 14; $i++) {
            $siret .= random_int(1, 9);
        }
        return $siret;
    }
}

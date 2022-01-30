<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Sodium\add;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function findOrCreate($data)
    {
        $address = $this->findOneBy([
            'customer' => $data['customer'],
            'country' => $data['country']->getId(),
            'city' => $data['city']->getId(),
            'district' => $data['district']->getId(),
        ]);

        if (!$address) {
            $address = new Address();
            $address->setFullAddress($data['full_address']);
            $address->setCustomer($data['customer']);
            $address->setCountry($data['country']);
            $address->setCity($data['city']);
            $address->setDistrict($data['district']);

            $this->_em->persist($address);
            $this->_em->flush();
        }

        return $address;
    }
}

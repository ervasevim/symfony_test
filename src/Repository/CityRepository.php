<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }
    
    public function findOrCreate($data)
    {
        $city = $this->findOneBy([
            'name' => $data['name'], 
            'country' => $data['country']
            ]);

        if (!$city) {
            $city = new City();
            $city->setName($data['name']);
            $city->setCountry($data['country']);
            $this->_em->persist($city);
            $this->_em->flush();
        }

        return $city;
    }
}

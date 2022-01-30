<?php

namespace App\Repository;

use App\Entity\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method District|null find($id, $lockMode = null, $lockVersion = null)
 * @method District|null findOneBy(array $criteria, array $orderBy = null)
 * @method District[]    findAll()
 * @method District[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DistrictRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, District::class);
    }

    public function findOrCreate($data)
    {
        $district = $this->findOneBy([
            'name' => $data['name'],
            'city' => $data['city']
        ]);

        if (!$district) {
            $district = new District();
            $district->setName($data['name']);
            $district->setCity($data['city']);
            $this->_em->persist($district);
            $this->_em->flush();
        }

        return $district;
    }
}

<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function create($data)
    {
        $product    = new Product();
        $fillable   = $product->fillable;
        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)){
                $method = 'set'.$this->setterName($key);
                if (!method_exists($product, $method))
                    throw new \Exception('Something bad');
                $product->$method($value);
            }
        }
        $this->_em->persist($product);
        $this->_em->flush();

        return $product;
    }

    public function update(Product $product, $data)
    {
        $fillable = $product->fillable;

        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)){
                $method = 'set'.$this->setterName($key);
                if (!method_exists($product, $method))
                    throw new \Exception('Something bad');
                $product->$method($value);
            }
        }

        $this->_em->flush();

        return $product;
    }

    public function delete(Product $product)
    {
        $this->_em->remove($product);
        $this->_em->flush();
    }


    function setterName($string)
    {
        return  str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

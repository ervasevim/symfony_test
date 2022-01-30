<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }
    public function create($data)
    {
        $order    = new Order();
        $fillable   = $order->fillable;

        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)){
                $method = 'set'.$this->setterName($key);
                if (!method_exists($order, $method))
                    throw new \Exception('Something bad');
                $order->$method($value);
            }
        }

        $this->_em->persist($order);
        $this->_em->flush();

        return $order;
    }

    public function update(Order $order, $data)
    {
        $fillable = $order->fillable;

        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)){
                $method = 'set'.$this->setterName($key);
                if (!method_exists($order, $method))
                    throw new \Exception('Something bad');
                $order->$method($value);
            }
        }

        $this->_em->flush();

        return $order;
    }

    public function delete(Order $order)
    {
        $this->_em->remove($order);
        $this->_em->flush();
    }

    function setterName($string)
    {
        return  str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

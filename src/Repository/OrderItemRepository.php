<?php

namespace App\Repository;

use App\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderItem[]    findAll()
 * @method OrderItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }
    public function create($data)
    {
        $orderItem    = new OrderItem();
        $fillable   = $orderItem->fillable;
        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)){
                $method = 'set'.$this->setterName($key);
                if (!method_exists($orderItem, $method))
                    throw new \Exception('Something bad');
                $orderItem->$method($value);
            }
        }
        $this->_em->persist($orderItem);
        $this->_em->flush();

        return $orderItem;
    }

    function setterName($string)
    {
        return  str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
}

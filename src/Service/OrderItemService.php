<?php namespace App\Service;


use App\Repository\AddressRepository;
use App\Repository\CountryRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use function Sodium\add;

class OrderItemService
{
    protected $orderItemRepository;
    protected $productRepository;

    /**
     * OrderController constructor.
     * @param $orderRepository
     */
    public function __construct(OrderItemRepository $orderItemRepository, ProductRepository $productRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->productRepository = $productRepository;
    }

    public function createOrderItem($data){
        $orderItems['orders'] = [];
        $orderItems['total_price'] = 0;

        foreach ($data['product'] as $datum) {
            $datum['product'] = $this->findProduct($datum['product_id']);
            if (!$datum['product']){
                throw new \Exception('Product not found');
            }
            $datum['order'] = $data['order'];
            $datum['price'] = $datum['product']->getPrice();
            $orderItems['orders'] = $this->orderItemRepository->create($datum);
            $orderItems['total_price'] += $datum['price']*$datum['quantity'];
        }
        return $orderItems;
    }

    public function findProduct($productId)
    {
        return $this->productRepository->findOneBy(['id' => $productId]);


    }
}

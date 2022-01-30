<?php namespace App\Service;


use App\Entity\Order;
use App\Repository\AddressRepository;
use App\Repository\CountryRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use function Sodium\add;

class OrderService
{
    protected $orderRepository;
    protected $orderItemService;
    protected $addressService;

    /**
     * OrderController constructor.
     * @param $orderRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderItemService $orderItemService,
       AddressService  $addressService
       // Address $addressRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemService = $orderItemService;
        $this->addressService = $addressService;
    }

    public function createOrder($data){
        $data['shipping_date'] = new \DateTime( $data['shipping_date']);
        $data['address']    = $this->addressService->createAddress($data);
        $data['order']      = $this->orderRepository->create($data);
        $data['order_item']  = $this->orderItemService->createOrderItem($data);

        $order = $this->orderRepository->update($data['order'],['total_price' => $data['order_item']['total_price']]);

        return $order;
    }

    public function updateOrder(Order $order, $data){
        if (isset($data['shipping_date'] )){
            $data['shipping_date']  = new \DateTime( $data['shipping_date']);
        }
        if (isset($data['address'])){
            $data['address'] = $this->addressService->createAddress($data);
        }

        $data['order'] = $this->orderRepository->update($order, $data);

        if (isset($data['product'])){
            foreach ($order->getOrderItems() as $orderItem){
                $order->removeOrderItem($orderItem);
            }
            $data['order_item'] = $this->orderItemService->createOrderItem($data);
            $order = $this->orderRepository->update($data['order'],['total_price' => $data['order_item']['total_price']]);
        }
        return $order;
    }
}

<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\OrderService;
use App\Service\ResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderController extends AbstractController
{
    protected $orderRepository;
    protected $orderService;

    /**
     * OrderController constructor.
     * @param $orderRepository
     */
    public function __construct(OrderRepository $orderRepository, OrderService $orderService)
    {
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
    }

    /**
     * @Route("/api/order", methods={"GET"}, name="order.index")
     */
    public function index(): Response
    {
        try {
            $orders = $this->orderRepository->findAll();
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        if (!$orders) {
            return ResponseService::warning()
                ->message('No order found!')
                ->response();
        }

        return ResponseService::success()
            ->response($orders);
    }

    /**
     * @Route("/api/order", methods={"POST"}, name="order.store")
     */
    public function store(Request $request): Response
    {
        $data = $request->toArray();
        $data['customer'] = $this->getUser();

        try {
            $order = $this->orderService->createOrder($data);
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return ResponseService::success()
            ->response($order->jsonSerialize());
    }

    /**
     * @Route("/api/order/{id}", methods={"GET"}, name="order.show")
     */
    public function show(int $id): Response
    {
        try {
            $order = $this->orderRepository->find($id);
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        if (!$order) {
            return ResponseService::warning()
                ->message('No order found for id '.$id)
                ->response();
        }

        return ResponseService::success()
            ->response($order->jsonSerialize());
    }

    /**
     * @Route("/api/order/{id}", methods={"PUT"}, name="order.update")
     */
    public function update(Request $request, int $id): Response
    {
        $data = $request->toArray();
        $data['customer'] = $this->getUser();
        try {
            $order = $this->orderRepository->find($id);
            if (!$order) {
                return ResponseService::warning()
                    ->message('No order found for id '.$id)
                    ->response();
            }
            if ($order->getShippingDate() > New \DateTimeImmutable("now")){
                return ResponseService::warning()
                    ->message('Order update is out of date')
                    ->response();
            }
            $order = $this->orderService->updateOrder($order, $data);
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return ResponseService::success()
            ->response($order);
    }

    /**
     * @Route("/api/order/{id}", methods={"DELETE"}, name="order.delete")
     */
    public function delete(int $id): Response
    {
        try {
            $order = $this->orderRepository->findOneBy(['id' => $id]);
            if (!$order) {
                return ResponseService::warning(404)
                    ->message('No order found for id '.$id)
                    ->response();
            }

            $this->orderRepository->delete($order);

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return ResponseService::success()
            ->message("The order has successfully deleted.")
            ->response();
    }

}

<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Request\ProductRequest;
use App\Request\ProductPutRequest;
use App\Service\ResponseService;
use App\Validator\ProductValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{

    protected $productRepository;
    protected $productValidator;

    /**
     * ProductController constructor.
     * @param $productRepository
     */
    public function __construct(ProductRepository $productRepository, ProductRequest $productValidator)
    {
        $this->productRepository = $productRepository;
        $this->productValidator = $productValidator;
    }

    /**
     * @Route("/api/product", methods={"GET"}, name="product.index")
     */
    public function index(): Response
    {
        try {
            $products = $this->productRepository->findAll();
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        if (!$products) {
            return ResponseService::warning()
                ->message('No product found!')
                ->response();
        }

        return ResponseService::success()
            ->response($products);
    }

    /**
     * @Route("/api/product", methods={"POST"}, name="product.store")
     */
    public function store(ProductRequest $request): Response
    {
        $data = $request->getRequest()->toArray();
        try {
            $product = $this->productRepository->create($data);
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return ResponseService::success()
            ->response($product);
    }

    /**
     * @Route("/api/product/{id}", methods={"GET"}, name="product.show")
     */
    public function show(int $id): Response
    {
        try {
            $product = $this->productRepository->find($id);
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        if (!$product) {
            return ResponseService::warning()
                ->message('No product found for id '.$id)
                ->response();
        }

        return ResponseService::success()
            ->response($product->jsonSerialize());
    }

    /**
     * @Route("/api/product/{id}", methods={"PUT"}, name="product.update")
     */
    public function update(ProductRequest $request, int $id): Response
    {
        $data = $request->getRequest()->toArray();
        try {
            $product = $this->productRepository->find($id);
            if (!$product) {
                return ResponseService::warning()
                    ->message('No product found for id '.$id)
                    ->response();
            }
            $product = $this->productRepository->update($product, $data);
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return ResponseService::success()
            ->response($product);
    }

    /**
     * @Route("/api/product/{id}", methods={"DELETE"}, name="product.delete")
     */
    public function delete(int $id): Response
    {
        try {
            $product = $this->productRepository->findOneBy(['id' => $id]);
            if (!$product) {
                return ResponseService::warning(404)
                    ->message('No product found for id '.$id)
                    ->response();
            }

            $this->productRepository->delete($product);

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return ResponseService::success()
            ->message("The product has successfully deleted.")
            ->response();
    }

}

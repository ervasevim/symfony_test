<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Service\ResponseService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Namshi\JOSE\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AuthController extends AbstractController
{
    protected $customerRepository;
    protected $security;
    protected $JWTTokenManager;

    /**
     * AuthController constructor.
     * @param $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository, Security $security, JWTTokenManagerInterface $JWTTokenManager)
    {
        $this->customerRepository = $customerRepository;
        $this->security = $security;
        $this->JWTTokenManager = $JWTTokenManager;
    }

    /**
     * @Route("/register", name="user.register", methods={"POST"})
     * @return Response
     */
    public function register(Request $request) : Response
    {
        $data = $request->toArray();
        try {
            $customer = $this->customerRepository->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        $response['token'] = $this->JWTTokenManager->create($customer);

        return ResponseService::success()
            ->response($response);
    }
}

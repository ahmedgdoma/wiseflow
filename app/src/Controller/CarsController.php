<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Services\CarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarsController extends AbstractController
{
    private CarRepository $carRepository;
    private CarService $carService;

    /**
     * CarsController constructor.
     * @param CarRepository $carRepository
     * @param CarService $carService
     */
    public function __construct(
        CarRepository $carRepository,
        CarService $carService
    ){
        $this->carRepository = $carRepository;
        $this->carService    = $carService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/cars', name: 'app_cars')]
    public function index(Request $request): Response
    {
        $parameters = $this->carService->filterParameters($request->query->all());
        $cars = $this->carRepository->getCars($parameters);
        $serializedData = $this->carService->serializeCars($cars);

        return new JsonResponse($serializedData);

    }
}

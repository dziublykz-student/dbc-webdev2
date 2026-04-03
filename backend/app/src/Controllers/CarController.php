<?php

namespace App\Controllers;

use App\Services\ICarService;
use App\Services\CarService;
use App\Framework\Controller;

class CarController extends Controller
{
    private ICarService $carService;

    public function __construct()
    {
        $this->carService = new CarService();
    }

    public function getAll()
    {
        try {
            $cars = $this->carService->getAll();
            return $this->sendSuccessResponse($cars);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function get($vars = [])
    {
        try {
            $id = (int)($vars['id'] ?? 0);
            $car = $this->carService->getById($id);

            if (!$car) {
                return $this->sendErrorResponse('Car not found', 404);
            }

            return $this->sendSuccessResponse($car);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }
}
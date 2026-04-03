<?php

namespace App\Controllers;

use App\Services\ICarService;
use App\Services\CarService;
use App\Framework\Controller;
use App\Helpers\JwtHelper;

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

    public function create()
    {
        try {
            $user = $this->requireAuth();

            if (!$user) {
                return $this->sendErrorResponse('Unauthorized', 401);
            }

            $data = json_decode(file_get_contents('php://input'), true);

            if (!$this->isValidCarData($data)) {
                return $this->sendErrorResponse('Invalid car data', 400);
            }

            $car = $this->carService->create($data);
            return $this->sendSuccessResponse($car, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function update($vars = [])
    {
        try {
            $user = $this->requireAuth();

            if (!$user) {
                return $this->sendErrorResponse('Unauthorized', 401);
            }

            $id = (int)($vars['id'] ?? 0);
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$this->isValidCarData($data)) {
                return $this->sendErrorResponse('Invalid car data', 400);
            }

            $car = $this->carService->update($id, $data);

            if (!$car) {
                return $this->sendErrorResponse('Car not found', 404);
            }

            return $this->sendSuccessResponse($car);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    public function delete($vars = [])
    {
        try {
            $user = $this->requireAuth();

            if (!$user) {
                return $this->sendErrorResponse('Unauthorized', 401);
            }
            
            $id = (int)($vars['id'] ?? 0);
            $deleted = $this->carService->delete($id);

            if (!$deleted) {
                return $this->sendErrorResponse('Car not found', 404);
            }

            return $this->sendSuccessResponse(['message' => 'Car deleted successfully']);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    private function requireAuth(): ?array
    {
        $token = JwtHelper::getBearerToken();

        if (!$token) {
            return null;
        }

        $decoded = JwtHelper::validateToken($token);

        if (!$decoded || !isset($decoded['data'])) {
            return null;
        }

        return (array) $decoded['data'];
    }

    private function isValidCarData(?array $data): bool
    {
        if (!$data) {
            return false;
        }

        $requiredFields = [
            'brand',
            'model',
            'year',
            'price',
            'mileage',
            'fuelType',
            'transmission',
            'status',
            'imageUrl',
            'description',
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                return false;
            }
        }

        return true;
    }
}
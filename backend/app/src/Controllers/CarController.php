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
            $filters = [
                'brand' => $_GET['brand'] ?? null,
                'fuelType' => $_GET['fuelType'] ?? null,
                'status' => $_GET['status'] ?? null,
            ];

            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;

            $cars = $this->carService->getAll($filters, $page, $limit);
            return $this->sendSuccessResponse($cars);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage() ?? 'Internal server error', 500);
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
            return $this->sendErrorResponse($e->getMessage() ?? 'Internal server error', 500);
        }
    }

    public function create()
    {
        try {
            $this->requireAdmin();

            $data = json_decode(file_get_contents('php://input'), true);
            $this->carService->validateCarData($data); // throws exception if invalid

            $car = $this->carService->create($data);
            return $this->sendSuccessResponse($car, 201);
        } catch (\InvalidArgumentException $e) {
            return $this->sendErrorResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage() ?? 'Internal server error', 500);
        }
    }

    public function update($vars = [])
    {
        try {
            $this->requireAdmin();

            $id = (int)($vars['id'] ?? 0);
            $data = json_decode(file_get_contents('php://input'), true);
            $this->carService->validateCarData($data); 

            $car = $this->carService->update($id, $data);

            if (!$car) {
                return $this->sendErrorResponse('Car not found', 404);
            }

            return $this->sendSuccessResponse($car);
        } catch (\InvalidArgumentException $e) {
            return $this->sendErrorResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage() ?? 'Internal server error', 500);
        }
    }

    public function delete($vars = [])
    {
        try {
            $this->requireAdmin();

            $id = (int)($vars['id'] ?? 0);
            $deleted = $this->carService->delete($id);

            if (!$deleted) {
                return $this->sendErrorResponse('Car not found', 404);
            }

            return $this->sendSuccessResponse(['message' => 'Car deleted successfully']);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage() ?? 'Internal server error', 500);
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

    private function requireAdmin(): ?array
    {
        $user = $this->requireAuth();

        if (!$user) {
            return null;
        }

        if (($user['role'] ?? '') !== 'admin') {
            return null;
        }

        return $user;
    }
}
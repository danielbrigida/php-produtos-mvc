<?php

namespace App\Api\src\Controller;

use App\Core\src\Controller\Controller;

abstract class ApiController extends Controller
{
    public function api()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET' :
                $this->index();
            break;

            case 'POST' :
                $this->create();
            break;

            case 'PUT' :
                $this->update();
            break;

            case 'DELETE' :
                $this->delete();
            break;
        }   
    }

    public function jsonResponse(array $data, $code = 200) {
        header("Content-Type: application/json", true, $code);
        echo json_encode($data);
        die();
    }

    protected function jsonError($data,int $code = 404) {
        header("Content-Type: application/json", true, $code);
        echo json_encode(['error' => $data]);
        die();
    }

    public function getPost() {
        return json_decode(file_get_contents("php://input"), true);
    }

    protected abstract function index();
    protected abstract function update();
    protected abstract function delete();
    protected abstract function create();
}

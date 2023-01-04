<?php

namespace App\Core\src\Controller;

abstract class Controller
{
    protected function view(string $view, $data = []): array
    {
        return  [
            'layout' =>  __DIR__ .'\\..\\..\\..\\' . $view . '.phtml',
            'data'=> $data,
        ];
    }

    protected function getParams(): array
    {
        return array_merge($_GET, $_POST);
    }

    protected function getForm(): array
    {
        return $_POST;
    }

    protected function redirect($path)
    {
        header("Location:".$path);
        die();
    }

    protected function isItPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    protected function doNotRender()
    {
        die();
    }

    protected function getJsonErrors($message)
    {
        echo json_encode(['error' =>  $message]);
        die();
    }
}

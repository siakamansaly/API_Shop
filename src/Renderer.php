<?php
namespace WshopApi;

/**
 * Class Renderer
 * @package WshopApi
 */
class Renderer
{
    /**
     * Response JSON
     * @param int $code
     * @param array $params
     * @return mixed
     */
    public function render(int $code, array $params = [])
    {
        header('Content-Type: application/json; charset=utf-8', true, $code);
        return json_encode($params);
    }
}
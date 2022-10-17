<?php 
namespace WshopApi\Controller;
use WshopApi\Renderer;
use WshopApi\Repository\ShopRepository;

/**
 * Class Shop
 * @package WshopApi\Controller
 */
class Shop
{
    private $repository;
    private $renderer;

    public function __construct()
    {
        $this->repository = new ShopRepository;
        $this->renderer = new Renderer;
    }

    /**
     * Get all Shops
     * @return JsonResponse
     */
    
    public function ReadAllShop()
    {
        $order = $_GET['order'] ?? 'id_ASC';
        $order = str_replace('_', ' ', $order);
        if(!in_array($order, ['id ASC', 'id DESC', 'name ASC', 'name DESC', 'description ASC', 'description DESC'])){
            $order = 'id ASC';
        }

        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 50;
        $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '%';
        $description = isset($_GET['description']) ? htmlspecialchars($_GET['description']) : '%';

        $result['code'] = 200;
        $result['description'] = "List Shop";
        $result['shop'] = $this->repository->getAll($order, $limit, $name, $description);

        echo $this->renderer->render($result['code'], $result);    
    }

    /**
     * Get Shop by id
     * @param int $id
     * @return JsonResponse
     */
    public function ReadOneShop(int $id)
    {
        $result['code'] = 200;
        $result['description'] = "Shop Detail";
        $result['shop'] = $this->repository->getById($id);
        
        echo $this->renderer->render($result['code'], $result);  
    }

    /**
     * Create Shop
     * @param array $data
     * @return JsonResponse
     */
    public function CreateShop()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result['code'] = 201;
        $result['description'] = "Shop Added";
        $result['shop'] = $this->repository->create($data);
        if($result['shop'] === false) {
            $result['code'] = 400;
            $result['description'] = "Bad Request";
        }

        echo $this->renderer->render($result['code'], $result);  
    }

    /**
     * Update Shop
     * @param int $id
     * @param array $data
     * @return JsonResponse
     */
    public function UpdateShop(int $id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result['code'] = 200;
        $result['description'] = "Shop " . $id . " Updated";
        $result['shop'] = $this->repository->update($id, $data);
        if($result['shop'] === false) {
            $result['code'] = 400;
            $result['description'] = "Bad Request";
        }

        echo $this->renderer->render($result['code'], $result);  
    }

    /**
     * Delete Shop
     * @param int $id
     * @return JsonResponse
     */
    public function DeleteShop(int $id)
    {
        $result['code'] = 204;
        $result['description'] = "Shop " . $id . " Deleted";
        $result['shop'] = $this->repository->delete($id);
        if($result['shop'] === false) {
            $result['code'] = 400;
            $result['description'] = "Bad Request";
            $result['shop'] = "Shop " . $id . " not found";
        }

        echo $this->renderer->render($result['code'], $result);  
    }
}
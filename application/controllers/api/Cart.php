<?php

defined('BASEPATH') or exit('No direct script access allowed');


/**
 * @OA\Info(title="Game Center API", version="0.1")
 * @OA\SecurityScheme(
 *   securityScheme="token",
 *   type="apiKey",
 *   name="Authorization",
 *   in="header"
 * )
 */
class Cart extends BD_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->auth();
        $this->load->helper('string');
        $this->load->model("t_cart", "cart");
        $this->load->model("t_cartDetail", "cartDetail");
        $this->load->model('errormodel', 'errormodel');
    }


    /**
     * @OA\Get(path="/api/cart/new",tags={"cart"},
     *   @OA\Response(response=200,
     *     description="add cart",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/cart")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function new_get()
    {
        if ($this->user_data->type == "cashier") {
            try {
                $cart = $this->cart;
                $cart->cashierCode = $this->user_data->code;

                $result = $cart->add();
                $this->response($result, 200);
            } catch (\Exception $e) {
                $error = new errormodel();
                $error->status = 500;
                $error->message = $e->getMessage();
                $this->response($error, 500);
            }
        } else {
            $this->response("Access Denied", 500);
        }
    }


    /**
     * @OA\Get(path="/api/cart/get",tags={"cart"},
     *   operationId="get cart",
     *   @OA\Parameter(
     *       name="code",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="searchKey",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="skip",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="int")
     *   ),
     *   @OA\Parameter(
     *       name="limit",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="int")
     *   ),
     *   @OA\Response(response=200,
     *     description="get member",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/cart")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function get_get()
    {
        $code = $this->get("code", true);
        $serchKey = $this->get("searchKey", true);
        $limit = $this->get("limit", true);
        $skip = $this->get("skip", true);
        $data = $this->cart->get($code, $serchKey, $limit, $skip);
        $this->response($data, 200); // OK (200) being the HTTP response code
    }

    /**
     * @OA\Post(path="/api/cart/addDetail",tags={"cart"},
     *   operationId="add addDetail", 
     *   @OA\Parameter(
     *       name="cartCode",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/cartDetail")
     *     )
     *   ),
     *   @OA\Response(response=200,
     *     description="add cart detail",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/cartDetail")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function addDetail_post()
    {
        $code = $this->input->get("cartCode", true);
        if ($this->user_data->type == "cashier") {
            try {
                $jsonBody  = json_decode(file_get_contents('php://input'), true);
                $cartDetail = $this->cartDetail->fromJson($jsonBody);
                $cartDetail->cartCode = $code;

                $result = $cartDetail->add();
                $this->response($result, 200);
            } catch (\Exception $e) {
                $error = new errormodel();
                $error->status = 500;
                $error->message = $e->getMessage();
                $this->response($error, 500);
            }
        } else {
            $this->response("Access Denied", 500);
        }
    }
}

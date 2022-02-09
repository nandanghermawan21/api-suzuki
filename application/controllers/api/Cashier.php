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
class Cashier extends BD_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->auth();
        $this->load->helper('string');
        $this->load->model("m_cashier", "cashier");
        $this->load->model('errormodel', 'errormodel');
    }

    /**
     * @OA\Post(path="/api/cashier/add",tags={"cashier"},
     *   operationId="add cashier",
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/cashier")
     *     )
     *   ),
     *   @OA\Response(response=200,
     *     description="add member",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/cashier")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function add_post()
    {
        if ($this->user_data->type == "admin") {
            try {
                $jsonBody  = json_decode(file_get_contents('php://input'), true);
                $cashier = $this->cashier->fromJson($jsonBody);

                if ($cashier->checkUsernameExist() == true) {
                    $this->response("Username Is Exist", 400);
                } else {
                    $result = $this->cashier->fromJson($jsonBody)->add();
                    $this->response($result, 200);
                }
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
     * @OA\Get(path="/api/cashier/get",tags={"cashier"},
     *   operationId="get cashier",
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
     *       @OA\Items(ref="#/components/schemas/cashier")
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
        $data = $this->cashier->get($code, $serchKey, $limit, $skip);
        $this->response($data, 200); // OK (200) being the HTTP response code
    }

    /**
     * @OA\Post(path="/api/cashier/changePassword",tags={"cashier"},
     * @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="userId",
     *                  type="string",
     *                  description="userId"
     *              ),
     *              @OA\Property(
     *                  property="oldPassword",
     *                  type="string",
     *                  description="oldPassword"
     *              ),
     *              @OA\Property(
     *                  property="newPassword",
     *                  type="string",
     *                  description="newPassword"
     *              )
     *          )
     *      )
     *  ),
     *   @OA\Response(response=200,
     *     description="get member",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/cashier")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function changePassword_post()
    {
        $userid = $this->post("userid");
        $oldPassword = $this->post("oldPassword");
        $newPassword = $this->post("newPassword");
        $cashier = new M_cashier();

        if ($this->user_data->type == "member") {
            $userid = $this->user_data->id;
            $cashier = $this->cashier->login($this->user_data->username, $oldPassword);
            if ($cashier == null) {
                $this->response("Old password is wrong", 401);
            } else {
                $cashier->changePassword($newPassword);
                $this->response($cashier, 200);
            }
        } else if ($this->user_data->type == "admin") {
            $oldPassword = "";
            $cashier->id = $userid;
            $cashier->getData();
            $cashier->changePassword($newPassword);
            $this->response($cashier, 200);
        } else {
            $this->response("Access Denied", 401);
        }
    }
}

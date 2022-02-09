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
class Voucher extends BD_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->auth();
        $this->load->helper('string');
        $this->load->model("m_voucher", "voucher");
        $this->load->model("m_member", "member");
        $this->load->model('errormodel', 'errormodel');
    }

    /**
     * @OA\Get(path="/api/voucher/get",tags={"voucher"},
     *   operationId="get voucher",
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
     *       @OA\Items(ref="#/components/schemas/voucher")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function get_get()
    {
        if ($this->user_data->type == "admin") {
            $code = $this->get("code", true);
            $serchKey = $this->get("searchKey", true);
            $limit = $this->get("limit", true);
            $skip = $this->get("skip", true);
            $data = $this->voucher->get($code, $serchKey, $limit, $skip);
            $this->response($data, 200);
        } else {
            $this->response("Access Denied", 401);
        }
    }


    /**
     * @OA\Post(path="/api/voucher/add",tags={"voucher"},
     *   operationId="add voucher",
     *   @OA\Parameter(
     *       name="prefix",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="sufix",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="count",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="int")
     *   ),
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/voucher")
     *     )
     *   ),
     *   @OA\Response(response=200,
     *     description="voucher",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/voucher")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function add_post()
    {
        if ($this->user_data->type == "admin") {
            try {
                $count = $this->input->get("count", true);
                $prefix = $this->input->get("prefix", true);
                $sufix = $this->input->get("sufix", true);
                $jsonBody  = json_decode(file_get_contents('php://input'), true);
                $this->voucher->fromJson($jsonBody);
                $result = $this->voucher->addBatch($prefix ?? "", $sufix ?? "", $count);
                $this->response($result, 200);
            } catch (\Exception $e) {
                $error = new errormodel();
                $error->status = 500;
                $error->message = $e->getMessage();
                $this->response($error, 500);
            }
        } else {
            $this->response("Access Denied", 401);
        }
    }

    /**
     * @OA\Post(path="/api/voucher/use",tags={"voucher"},
     *   operationId="use voucher",
     *   @OA\Parameter(
     *       name="code",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=200,
     *     description="voucher",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/member")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function use_post()
    {
        $code = $this->input->get("code", true);
        try {
            if ($this->user_data->type == "member") {
                $this->voucher->useVoucher($code);
                $this->member->fromId($this->user_data->id);
                $this->member->addPoint($this->voucher->point ?? 0);
                $this->member->addSilverTicket($this->voucher->silverTicket ?? 0);
                $this->member->addGoldTicket($this->voucher->goldTicket ?? 0);
                return $this->response($this->member, 200);
            } else {
                $this->response("Access Denied", 401);
            }
        } catch (\Exception $e) {
            $error = new errormodel();
            $error->status = 500;
            $error->message = $e->getMessage();
            $this->response($error, 500);
        }
    }
}

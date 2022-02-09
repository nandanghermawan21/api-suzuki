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
class Member extends BD_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->auth();
        $this->load->helper('string');
        $this->load->model("m_member", "member");
        $this->load->model('errormodel', 'errormodel');
    }



    /**
     * @OA\Post(path="/api/member/add",tags={"member"},
     *   operationId="add member",
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/member")
     *     )
     *   ),
     *   @OA\Response(response=200,
     *     description="add member",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/member")
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
                $member = $this->member->fromJson($jsonBody);

                if ($member->checkUsernameExist() == true) {
                    $this->response("Username Is Exist", 400);
                } else if ($member->checkEmailExist() == true) {
                    $this->response("Email Is Exist", 400);
                } else if ($member->checkPhoneExist() == true) {
                    $this->response("Phone Is Exist", 400);
                } else {
                    $result = $this->member->fromJson($jsonBody)->add();
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
     * @OA\Get(path="/api/member/get",tags={"member"},
     *   operationId="get member",
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
     *       @OA\Items(ref="#/components/schemas/member")
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
        $data = $this->member->get($code, $serchKey, $limit, $skip);
        $this->response($data, 200); // OK (200) being the HTTP response code
    }

    /**
     * @OA\Get(path="/api/member/addpoint",tags={"member"},
     *   operationId="add point",
     *   @OA\Parameter(
     *       name="id",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="point",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="int")
     *   ),
     *   @OA\Response(response=200,
     *     description="get member",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/member")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function addpoint_get()
    {
        $id = $this->get("id", true);
        $point = $this->get("point", true);
        $data = $this->member->fromId($id)->addPoint($point);
        $this->response($data, 200); // OK (200) being the HTTP response code
    }

    /**
     * @OA\Get(path="/api/member/removepoint",tags={"member"},
     *   operationId="removepoint point",
     *   @OA\Parameter(
     *       name="id",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="point",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="int")
     *   ),
     *   @OA\Response(response=200,
     *     description="get member",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/member")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function removepoint_get()
    {
        $id = $this->get("id", true);
        $point = $this->get("point", true);
        $member = $this->member->fromId($id);

        if (($member->point - $point) < 0) {
            $this->response("Not Enought Point", 400);
        } else {
            $data = $member->fromId($id)->removePoint($point);
            $this->response($data, 200); // OK (200) being the HTTP response code
        }
    }

    /**
     * @OA\Get(path="/api/member/buySilverTicket",tags={"member"},
     *   operationId="buySilverTicket",
     *   @OA\Parameter(
     *       name="id",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=200,
     *     description="get member",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/member")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function buySilverTicket_get()
    {
        $id = $this->get("id", true);

        $member = $this->member->fromId($id);

        if ($member->point < $this->config->item('silver_ticket_price')) {
            $this->response("Not Enought Point", 400);
        } else {
            $member = $member->buySilverTicket();
        }

        $this->response($member, 200); // OK (200) being the HTTP response code
    }

    /**
     * @OA\Get(path="/api/member/useSilverTicket",tags={"member"},
     *   operationId="useSilverTicket",
     *   @OA\Parameter(
     *       name="id",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=200,
     *     description="get member",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/member")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function useSilverTicket_get()
    {
        $id = $this->get("id", true);

        $member = $this->member->fromId($id);

        if ($member->silverTicket - 1 < 0) {
            $this->response("Not Enought Ticket", 400);
        } else {
            $member = $member->useSilverTicket();
            $this->response($member, 200); // OK (200) being the HTTP response code
        }
    }

    /**
     * @OA\Get(path="/api/member/buyGoldTicket",tags={"member"},
     *   operationId="buyGoldTicket",
     *   @OA\Parameter(
     *       name="id",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=200,
     *     description="get member",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/member")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function buyGoldTicket_get()
    {
        $id = $this->get("id", true);

        $member = $this->member->fromId($id);

        if ($member->point < $this->config->item('gold_ticket_price')) {
            $this->response("Not Enought Point", 400);
        } else {
            $member = $member->buyGoldTicket();
        }

        $this->response($member, 200); // OK (200) being the HTTP response code
    }

    /**
     * @OA\Get(path="/api/member/useGoldTicket",tags={"member"},
     *   operationId="useGoldTicket",
     *   @OA\Parameter(
     *       name="id",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=200,
     *     description="get member",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/member")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function useGoldTicket_get()
    {
        $id = $this->get("id", true);

        $member = $this->member->fromId($id);

        if ($member->goldTicket - 1 < 0) {
            $this->response("Not Enought Ticket", 400);
        } else {
            $member = $member->useGoldTicket();
            $this->response($member, 200); // OK (200) being the HTTP response code
        }
    }

    /**
     * @OA\Post(path="/api/member/changePassword",tags={"member"},
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
     *       @OA\Items(ref="#/components/schemas/member")
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
        $member = new M_member();

        if ($this->user_data->type == "member") {
            $userid = $this->user_data->id;
            $member = $this->member->login($this->user_data->username, $oldPassword);
            if ($member == null) {
                $this->response("Old password is wrong", 401);
            } else {
                $member->changePassword($newPassword);
                $this->response($member, 200);
            }
        } else if ($this->user_data->type == "admin") {
            $oldPassword = "";
            $member->id = $userid;
            $member->getData();
            $member->changePassword($newPassword);
            $this->response($member, 200);
        } else {
            $this->response("Access Denied", 401);
        }
    }
}

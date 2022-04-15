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
class Chat extends BD_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth();
        $this->load->model('Chat_model', 'chat');
        $this->load->model('Notification_model', 'notif');
        $this->load->model('Customer_model', 'customer');
    }


    /**
     * @OA\post(path="/api/chat/toCustomer",tags={"chat"},
     *   operationId="Send Message TO Customer",
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/ComposeChat")
     *     ),
     *   ),
     *   @OA\Response(response=200,
     *     description="save location",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/ChatModel")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function toCustomer_post()
    {
        $jsonBody  = json_decode(file_get_contents('php://input'), true);
        $chat = $this->chat->fromJson($jsonBody);

        try {
            //get info customer
            $sender = $this->customer->fromId($this->user_data->id);
            $receiver = $this->customer->fromId($chat->receiver);

            if ($sender->id == null) {
                $this->response("sender not found", 500);
            }

            if ($receiver->id == null) {
                $this->response("receiver not found", 500);
            }

            $chat->messageType = $this->user_data->type . "-To-" . "customer";
            $chat->createDate =  new DateTime("now", new DateTimeZone("UTC"));
            $chat->sender = "customer-" . $this->user_data->id;
            $chat->receiver = "customer-" . $chat->receiver;

            //save message
            $chat = $chat->add();

            //send notification
            $title = array(
                "en" =>  $sender->fullName,
                "id" =>  $sender->fullName
            );
            $message = array(
                "en" => $chat->message,
                "id" =>  $chat->message,
            );
            $deviceId = array(
                $receiver->deviceId,
            );
            $appUrl = "sufismart://customer/chat?receiver=".$receiver->id;

            $notif = $this->notif->send_basic_notification($title,$message,$deviceId,$appUrl);

            //assign result to message
            $result = $chat->toJson();
            $result["notif"] = $notif;

            $this->response($result, 200);
        } catch (\Exception $e) {
            $error = new Error_model();
            $error->status = 500;
            $error->message = $e->getMessage();
            $this->response($error->message, 500);
        }
    }
}

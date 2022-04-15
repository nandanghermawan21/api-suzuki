<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Chat extends BD_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Chat_model', 'chat');
    }


    /**
     * @OA\post(path="/api/chat/toCustomer",tags={"chat"},
     *   operationId="Send Message TO Customer",
     *   @OA\SecurityScheme(
     *     securityScheme="token",
     *     type="apiKey",
     *     name="Authorization",
     *     in="header"
     *   )
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
            $chat->messageType = $this->user_data->type . "-To-" . "customer";
            $chat->sender = "customer-" . $this->user_data->id;
            $chat->receiver = "customer-" . $chat->receiver;

            //save message
            $chat = $chat->add();
            $this->response($chat->toJson(), 200);
        } catch (\Exception $e) {
            $error = new Error_model();
            $error->status = 500;
            $error->message = $e->getMessage();
            $this->response($error->message, 500);
        }
    }
}

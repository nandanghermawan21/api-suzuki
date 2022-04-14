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
     * @OA\post(path="/api/Service/chatToCustomer",tags={"chat"},
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
    public function chatToCustomer_post()
    {
        $jsonBody  = json_decode(file_get_contents('php://input'), true);
        $chat = $this->chat->fromJson($jsonBody);

        if ($this->getData() == null) {
            $this->response("unautorized", 401);
        } else {
            try {
                $chat->messageType = $this->getData()->type . "-To-" . "customer";
                $chat->sender = "customer-" . $this->getData()->id;
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
}

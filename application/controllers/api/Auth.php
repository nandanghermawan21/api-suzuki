<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;

/**
 * @OA\Info(title="Game Center API", version="0.1")
 */
class Auth extends BD_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('Customer_model', 'customer');
        $this->load->model('User_model', 'user');
        $this->load->model('Twilio_model', 'sms');
        $this->load->model('Otp_model', 'otp');
    }

    /**
     * @OA\Post(path="/api/auth/customerLogin",tags={"Auth"},
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/UserModel")
     *     )
     *   ),
     *   @OA\Response(response=200,
     *     description="basic customer info",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/CustomerModel")
     *     ),
     *   ),
     * )
     */
    public function customerLogin_post()
    {
        try {
            $customer = new Customer_model();
            $jsonBody  = json_decode(file_get_contents('php://input'), true);
            $user = $this->user->fromJson($jsonBody);
            $customerPublic = $this->customer->login(
                $user,
                false,
                $customer 
            );
            if ($customer->isVerifiedPhone == false) {
                $this->sms->send_sms($customer->phoneNumber, "Berikut kode OTP untuk registrasi anda " . $customer->otp);

                $result = new Otp_model();
                $result->resendUrl = "api/customer/resendotp/?id=" . $customer->id;
                $result->confirmUrl = "api/customer/confirm/?id=" . $customer->id;
                $result->expired = $customer->otpValidDate->format('Y-m-d') . "T" .  $customer->otpValidDate->format('H:i:s.u');
                $this->response($result, 403);
            }
            $this->response($customerPublic, 200);
        } catch (\Exception $e) {
            $error = new Error_model();
            $error->status = 500;
            $error->message = $e->getMessage();
            $this->response($error->message, 500);
        }
    }
}

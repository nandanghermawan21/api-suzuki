<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;


class Customer extends BD_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('string');
        $this->load->model('Error_model', 'error');
        $this->load->model('Otp_model', 'otp');
        $this->load->model('Customer_model', 'customer');
        $this->load->model('File_model', 'file');
        $this->load->model('User_model', 'user');
        $this->load->model('Twilio_model', 'sms');
        $this->load->model('Location_model', 'location');
    }

    /**
     * @OA\Post(path="/api/customer/register",tags={"customer"},
     *   operationId="register customer",
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/CustomerRegister")
     *     ),
     *   ),
     *   @OA\Response(response=200,
     *     description="register customer",
     *     @OA\JsonContent(
     *       ref="#/components/schemas/OtpModel"
     *     ),
     *   ),
     * )
     */
    public function register_post()
    {
        try {
            $jsonBody  = json_decode(file_get_contents('php://input'), true);
            $customerRegister =  $this->customer->readRegisterJson($jsonBody);
            $customer = $this->customer->fromJson($jsonBody);

            // print("jsonBody \n");
            // print_r($jsonBody);

            // print("customerRegister \n");
            // print_r($customerRegister);

            if ($customer->checkUsernameExist() == true) {
                $this->response("Username Is Exist", 400);
            }
            // else if ($customer->checkPhoneExist() == true) {
            //     $this->response("Phone Is Exist", 400);
            // }
            else if ($customerRegister->avatar == null) {
                $this->response("Please insert image avarar", 400);
            } else {

                //upload image first
                $file = $this->file->save("useravatar", $customerRegister->phoneNumber, $customerRegister->avatar);
                $customer->imageId = $file->id;

                //createOtp
                $otp = rand(100000, 999999);
                $currentDate = new DateTime("now", new DateTimeZone("UTC"));
                $expiredDate = $currentDate->add(new DateInterval('PT' . 5 . 'M'));


                //add
                $customer->otp = $otp;
                $customer->isVerifiedPhone = false;
                $customer->otpValidDate = $expiredDate;

                //send Otp
                // $this->sms->send_sms($customerRegister->phoneNumber, "Berikut kode OTP untuk registrasi anda " . $otp);

                $customer = $customer->add();

                //login
                $result = new Otp_model();
                $result->resendUrl = "customer/resend?id=" . $customer->id;
                $result->confirmUrl = "customer/confirm?id=" . $customer->id;
                $result->expired =   $expiredDate->format(DATE_ATOM);

                $this->response($result, 200);
            }
        } catch (\Exception $e) {
            $this->response($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(path="/api/customer/confirm",tags={"customer"},
     *   operationId="confirm",
     *   @OA\Parameter(
     *       name="id",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="otp",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string")
     *   ),
     *    @OA\Response(response=200,
     *     description="file info",
     *     @OA\JsonContent(
     *       ref="#/components/schemas/CustomerModel"
     *     ),
     *    ),
     * )
     */
    public function confirm_post()
    {
        $id = $this->input->get("id", true);
        $otp = $this->input->get("otp", true);

        if (!empty($id) && !empty($otp)) {
            $customer = $this->customer->fromId($id);
            if ($customer->id != null) {
                if ($customer->otp == $otp) {
                    $customer->isVerifiedPhone = true;
                    $customer = $customer->update();

                    $user = $this->user->fromJson($customer->toJson());
                    $result = $customer->login($user, true);

                    $this->response($result, 200);
                } else {
                    $this->response("otp tidak valid", 400);
                }
            } else {
                $this->response("id tidak ditemukan", 400);
            }
        }
    }



    /**
     * @OA\Post(path="/api/customer/resend",tags={"customer"},
     *   operationId="resend",
     *   @OA\Parameter(
     *       name="id",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string")
     *   ),
     *    @OA\Response(response=200,
     *     description="file info",
     *     @OA\JsonContent(
     *      ref="#/components/schemas/OtpModel"
     *     ),
     *    ),
     * )
     */
    public function resend_post()
    {

        try {
            $id = $this->input->get("id", true);
            if (!empty($id)) {
                $customer = $this->customer->fromId($id);
                if ($customer->id != null) {
                    //send Otp
                    // $this->sms->send_sms($customer->phoneNumber, "Berikut kode OTP untuk registrasi anda " . $customer->otp);

                    $customer = $customer->update();

                    //login
                    $result = new Otp_model();
                    $result->resendUrl = "customer/resend?id=" . $customer->id;
                    $result->confirmUrl = "customer/confirm?id=" . $customer->id;
                    $result->expired = $customer->otpValidDate->format(DateTime::ATOM);
                    $this->response($result, 200);
                } else {
                    $this->response("id tidak ditemukan", 400);
                }
            }
        } catch (\Exception $e) {
            $error = new Error_model();
            $error->status = 500;
            $error->message = $e->getMessage();
            $this->response($error->message, 500);
        }
    }
}

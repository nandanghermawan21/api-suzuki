<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;

/**
 * @OA\Info(title="Game Center API", version="0.1")
 */
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
                $this->sms->send_sms($customerRegister->phoneNumber, "Berikut kode OTP untuk registrasi anda " . $otp);

                $customer = $customer->add();

                //login
                $result = new Otp_model();
                $result->resendUrl = "api/customer/resendotp/?id=" . $customer->id;
                $result->confirmUrl = "api/customer/confirm/?id=" . $customer->id;
                $result->expired = $expiredDate->format('Y-m-d') . "T" .  $expiredDate->format('H:i:s.u');

                $this->response($result, 200);
            }
        } catch (\Exception $e) {
            $this->response($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(path="/api/customer/updateImage",tags={"customer"},
     *   operationId="updateImageId customer",
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               @OA\Property(
     *                   property="media",
     *                   description="media",
     *                   type="file",
     *                   @OA\Items(type="string", format="binary")
     *                ),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200,
     *     description="register customer",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/CustomerModel")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function updateImage_post()
    {
        if ($this->getData()->type == "customer") {
            try {
                $id = $this->getData()->id;
                $path = "customer_photo";
                if (!empty($_FILES["media"])) {
                    $media    = $_FILES["media"];

                    if ($media["error"] !== UPLOAD_ERR_OK) {
                        $this->response("upload gagal", 500);
                        exit;
                    } else {
                        $file = $this->file->upload($path, random_string('alnum', 50), $media);
                        $customer = $this->customer->fromId($id);
                        $customer->imageId = $file->id;
                        $customer = $customer->update();
                        $this->response($customer, 200);
                    }
                }
            } catch (\Exception $e) {
                $error = new Error_model();
                $error->status = 500;
                $error->message = $e->getMessage();
                $this->response($error, 500);
            }
        } else {
            $this->response("forbidden access", 500);
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
     *       @OA\Items(ref="#/components/schemas/CustomerModel")
     *     ),
     *    ),
     *   security={{"token": {}}},
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
     *       @OA\Items(ref="#/components/schemas/OtpModel")
     *     ),
     *    ),
     *   security={{"token": {}}},
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
                    $this->sms->send_sms($customer->phoneNumber, "Berikut kode OTP untuk registrasi anda " . $customer->otp);

                    $customer = $customer->add();

                    //login
                    $result = new Otp_model();
                    $result->resendUrl = "api/customer/resendotp/?id=" . $customer->id;
                    $result->confirmUrl = "api/customer/confirm/?id=" . $customer->id;
                    $result->expired = $customer->otpValidDate->format('Y-m-d') . "T" .  $customer->otpValidDate->format('H:i:s.u');
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

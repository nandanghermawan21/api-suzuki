<?php

/**
 * @OA\Schema(schema="OtpModel")
 */
class Otp_model extends CI_Model
{
	/**
	 * @OA\Property()
	 * @var String
	 */
	public $resendUrl;

	/**
	 * @OA\Property()
	 * @var DateTime
	 */
	public $confirmUrl;

	/**
	 * @OA\Property()
	 * @var String
	 */
	public $jsonData;

	/**
	 * @OA\Property()
	 * @var DateTime
	 */
	public $expired;

}
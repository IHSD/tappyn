<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Voucher Model
 *
 * Fields
 * integer      |   id              |   ID of the voucher
 * string       |   code            |   Discount Code
 * string       |   expiration      |   The type of expiration on this voucher. Can be time_length or usage
 * integer      |   starts_at       |   If expiration is time_length, when the discount starts
 * integer      |   ends_at         |   If expiration is time_length, when the discount ends
 * string       |   discount_type   |   Type of discount. May be percentage or amount
 * string       |   value           |   Value of the discount
 * integer      |   status          |   Wether or not the discount is active
 * integer      |   usage_limit     |   If expiration set to usage, the amount of times discount may be used
 * integer      |   times_used      |   The amount of times voucher has been redeemed
 * integer      |   created_at      |   When the voucher was created
 * integer      |   updated_at      |   Last time the voucher was updated
 *
 *
 */
class Voucher extends MY_Model
{
    protected $table = 'vouchers';
    protected $errors = FALSE;
    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {}

    public function update($id, $data)
    {

    }

    public function errors()
    {
        return $this->errors;
    }
}

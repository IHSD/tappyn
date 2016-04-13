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
    public $table = 'vouchers';
    protected $errors = FALSE;

    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        if($this->db->insert('vouchers', $data))
        {
            return $this->db->insert_id();
        }
        $this->errors = $this->db->error()['message'];
        return FALSE;
    }

    /**
     * Check the validity of a given vouchers
     * @param  integer  $vid Voucher ID
     * @return boolean      Wether or not voucher is valid
     */
    public function is_valid($vid)
    {
        $voucher = $this->where('id', $vid)->limit(1)->fetch();
        // Does voucher exist?
        if(!$voucher || $voucher->num_rows() == 0)
        {
            $this->errors = "I couldn't find a voucher with that discount code";
            return FALSE;
        }
        $voucher = $voucher->row();
        // Is it active?
        if($voucher->status == 0)
        {
            $this->errors = "This voucher has been disabled";
            return FALSE;
        }
        // Based on its expiration, is it currently valid
        if(!is_null($voucher->ends_at) && $voucher->ends_at < time())
        {
            $this->errors = "We're sorry, but that voucher has expired";
            return FALSE;
        }
        // If the expiration is uses, has it reached its capacity yet?
        if($voucher->expiration == 'uses' && $voucher->times_used > $voucher->usage_limit)
        {
            $this->errors = "Were sorry, but that voucher can no longer be used";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Validate the voucher data is agreeable before trying to insert
     * @param  array $data Voucher Data
     * @return boolean       Valid or not
     */
    public function validate($data)
    {

    }

    /**
     * Update a voucher
     * @param  integer  $id  ID of the voucher
     * @param  array $data Data to insert
     * @return boolean
     */
    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('vouchers', $data);
    }

    /**
     * Redeem a voucher_uses
     * @param  integer $vid ID of the voucher
     * @param  integer $cid ID of the contest
     * @return void
     */
    public function redeem($vid, $cid)
    {
        if($this->db->insert('voucher_uses', array(
            'created_at' => time(),
            'voucher_id' => $vid,
            'contest_id' => $cid
        )))
        {
            if($this->db->where('id', $vid)
                     ->set('times_used', 'times_used+1', FALSE)
                     ->update('vouchers')) {
                return TRUE;
            } else {
                $this->errors = $this->db->error()['message'];
                return FALSE;
            }
        }
        $this->errors = $this->db->error()['message'];
        return FALSE;
    }

    public function get($vid)
    {
        $check = $this->db->select('*')->from('vouchers')->where('id', $vid)->get();
        if($check && $check->num_rows() > 0)
        {
            return $check->row();
        }
        return FALSE;
    }

    public function fetchByCode($code)
    {
        $check = $this->db->select('*')->from('vouchers')->where('code', $code)->get();
        if($check && $check->num_rows() > 0)
        {
            return $check->row();
        }
        return FALSE;
    }
    public function errors()
    {
        return $this->errors;
    }
}

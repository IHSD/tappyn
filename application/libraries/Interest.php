<?php defined("BASEPATH") or exit('No direct script access allowed');

class Interest
{
    /**
     * Database Handler
     * @var DB
     */
    protected $db;

    protected $topLevelInterests = array(
        'food_beverage',
        'finance_business',
        'health_wellness',
        'travel',
        'social_network',
        'home_garden',
        'education',
        'art_entertainment',
        'fashion_beauty'
    );


    /**
     * ID of the current user
     * @var integer
     */
    protected $user;

    protected $message = FALSE;

    protected $errors = FALSE;

    public function __construct()
    {

    }

    /**
     * Set our Database Handler
     * @param DB $db Database instance to set
     */
    public function setDatabase(CI_DB $db)
    {
        $this->db = $db;
    }

    /**
     * Set our user ID
     * @param [type] $id [description]
     */
    public function setUser($id)
    {
        $this->user = $id;
    }

    /**
     * Assign an interest as followed by a user
     * @param  integer $uid ID of the user
     * @param  integer $iid ID of the interest
     * @return void
     */
    public function addToUser($iid)
    {
        if(!$this->_exists(array('id' => $iid)))
        {
            $this->errors = "That interest does not exist";
            return FALSE;
        }
        $check = $this->db->select('*')->from('users_interests')->where(array('user_id' => $this->user, 'interest_id' => $iid))->get();
        if(!$check || $check->num_rows() > 0)
        {
            return TRUE;
        }
        if($this->db->insert("users_interests", array("user_id" => $this->user, "interest_id" => $iid, 'created_at' =>time())))
        {
            return TRUE;
        }
        $this->errors = $this->db->error()['message'];
        return FALSE;
    }

    public function removeFromUser($iid)
    {

        return $this->db->where(array('user_id' => $this->user, 'interest_id' => $iid))->delete('users_interests');
    }

    /**
     * Generate an tree / sub-tree base on the hierarchial data in the DB
     * @param integer $id  ID of tree section to start at
     * @return array
     */
    public function tree($id = NULL)
    {
        $results = array();
        // Generate the whole tree
        if(is_null($id))
        {
            $interests = $this->db->select('*')->from('interests')->order_by('lft', 'asc')->get();
            if($interests)
            {
                $results = $interests->result();
            } else {
                die($this->db->error()['message']);
            }
        }
        // Only fetch a subsection
        else {

        }

        $this->followedByUser();
        $results = $this->branch_result($results);
        return $results;

    }

    /**
     * Fetch data on an individual interest
     * @param  integer $id
     * @return object|boolean
     */
    public function fetch($id = NULL)
    {
        if(is_null($id))
        {
            // Fetch all interests
        } else {
            $interest = $this->db->select('*')->from('interests')->where('id', $id)->get();
            if($interest && $interest->num_rows() > 0)
            {
                return $interest->row();
            }
        }
        return FALSE;
    }

    /**
     * Check if an interest has any children interest
     * @param  integer  $lft Left Tree Value
     * @param  integer  $rgt Right Tree Value
     * @return boolean
     */
    public function hasChildren($lft, $rgt)
    {
        // If there is no space bewteen params, obv has no children
        if($rgt - $lft === 1)
        {
            return FALSE;
        }

        $children = $this->db->select('*')->from('interests')->where(array('lft >' => (int)$lft, 'rgt <' => (int)$rgt))->get();

        if(!$children || $children->num_rows() > 0)
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Create an interest
     * @param  string $name         Name of the interest
     * @param  string $display_name User Visible name
     * @param  integer $parent_id    Parent to add the interest to
     * @return boolean
     */
    public function create($name, $display_name, $parent_id)
    {
        // Start the transaction
        $this->_start_transaction();


        /*----------------------
        Begin Business logic
        ----------------------*/

        if($this->_exists(array('name' => $name)))
        {
            $this->errors = "You have to choose a unique name";
            return FALSE;
        }

        if($this->_exists(array('display_name' => $display_name)))
        {
            $this->errors = "You have to choose a unique display name";
            return FALSE;
        }

        if(!$this->_exists(array("id" => $parent_id)))
        {
            $this->errors = "The parent you want to add to doesnt exist!";
            return FALSE;
        }

        $parent = $this->fetch($parent_id);

        // Make room for our new interest
        $this->db->where('rgt >=', (int)$parent->rgt);
        $this->db->set('rgt', 'rgt+2', FALSE);
        $this->db->update('interests');

        $this->db->where('lft >', (int)$parent->lft);
        $this->db->set('lft', 'lft+2', FALSE);
        $this->db->update('interests');

        // Create the interest
        $id = $this->db->insert('interests', array('name' => $name, 'display_name' => $display_name, 'lft' => $parent->lft + 1, 'rgt' => $parent->lft + 2));

        if( ! $id )
        {
            $this->errors = "There was an error creating your interest";
            return FALSE;
        }

        /*----------------------
        End Business Logic
        ----------------------*/

        // Complete the transaction and check status
        $this->_complete_transaction();
        if($this->_transaction_status() === FALSE)
        {
            // An error occured
            $this->errors = "An unknown error occured";
            return FALSE;
        }

        // Everything ran exceptionally well. Interest successfully created
        $this->message = "Interest sucessfully created";
        return TRUE;
    }

    /**
     * Remove an interest and all of its subchildren
     * @param  integer $id ID of the interest we would like to remove
     * @return boolean
     */
    public function delete($id)
    {
        // Start the transaction
        $this->_start_transaction();


        /*----------------------
        Begin Business logic
        ----------------------*/

        if(!$this->_exists(array('id' => $id)))
        {
            $this->errors = "That interest does not exist";
            return FALSE;
        }

        $interest = $this->fetch($id);
        if($this->hasChildren($interest->lft, $interest->rgt))
        {
            $this->errors = "You have to remove all the children for that interest first";
            return FALSE;
        }

        $this->db->where('rgt >', (int)$interest->rgt);
        $this->db->set('rgt', 'rgt-2', FALSE);
        $this->db->update('interests');

        $this->db->where('lft >', (int)$interest->lft);
        $this->db->set('lft', 'lft-2', FALSE);
        $this->db->update('interests');

        $this->db->where('id', $id);
        $this->db->delete('interests');
        /*----------------------
        End Business Logic
        ----------------------*/


        // Complete the transaction and check status
        $this->_complete_transaction();

        if($this->_transaction_status() === FALSE)
        {
            // An error occured;
            $this->errors = "An unknown error occured";
            return FALSE;
        }

        // Everything ran exceptionally well. Interest successfully created
        $this->message = "Interest sucessfully deleted";
        return TRUE;
    }

    /**
     * Check if an interest exists based on a param
     * @param  array $params Parameters to query on
     * @return void
     */
    private function _exists($params)
    {
        $check = $this->db->select('*')->from('interests')->where($params)->get();

        if(!$check || $check->num_rows() > 0)
        {
            return TRUE;
        }
        return FALSE;
    }

    public function topLevelInterest($interest)
    {

    }

    private function branch_result($results)
    {
           $return = $results[0];
           array_shift($results);

           $return->followed_by_user = FALSE;
           if(!empty($this->follows) && in_array($return->id, $this->follows))
           {
               $return->followed_by_user = TRUE;
           }
           if ($return->lft + 1 == $return->rgt)
               $return->leaf = true;
           else {
               foreach ($results as $key => $result) {
                   if ($result->lft > $return->rgt) //not a child
                       break;
                   if (@$rgt > $result->lft) //not a top-level child
                       continue;
                   $return->children[] = $this->branch_result(array_values($results));
                   foreach ($results as $child_key => $child) {

                       if ($child->rgt < $result->rgt)
                           unset($results[$child_key]);
                   }
                   $rgt = $result->rgt;
                   unset($results[$key]);
               }
           }

           //unset($return->lft,$return->rgt);
           return $return;
    }

    /**
     * Fetch an array of interests followed by user
     * @return array
     */
    public function followedByUser()
    {
        $return = array();
        $interests = $this->db->select('*')->from('users_interests')->where('user_id', $this->user)->get();
        if(!$interests || $interests->num_rows() == 0)
        {
            return $return;
        }
        foreach($interests->result() as $interest)
        {
            $return[] = $interest->interest_id;
        }
        $this->follows = $return;
        return;
    }
    /**
     * Begin a transaction
     * @return void
     */
    private function _start_transaction()
    {
        $this->db->trans_start();
    }

    /**
     * Complete transaction. If all queries were successful,
     * the transaction commits. If there was an error, the transaction
     * is rolled back
     * @return void
     */
    private function _complete_transaction()
    {
        $this->db->trans_complete();
    }

    private function _transaction_status()
    {
        return $this->db->trans_status();
    }

    /**
     * Return any encountered errors
     * @return string
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Return any messages
     * @return string
     */
    public function messages()
    {
        return $this->message;
    }

    public function flatten($array)
    {
        $return = array();

        for($x = 0; $x < count($array); $x++) {
    		if(is_array($array[$x])) {
    			$return = array_flatten($array[$x], $return);
    		}
    		else {
    			if(isset($array[$x])) {
    				$return[] = $array[$x];
    			}
    		}
    	}
    	return $return;
    }
}

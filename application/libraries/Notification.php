<?php defined("BASEPATH") or exit('No direct script access allowed');

class Notification
{
    protected $user;
    protected $errors = FALSE;
    protected $messages = FALSE;
    protected $table = 'notifications';
    public function __construct()
    {

    }


    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function count()
    {
        $notifications = $this->db->select('COUNT(*) as count')->from($this->table)->where(array('user_id' => $this->user, 'read' => 0))->get();
        if(!$notifications) return 0;
        return (int)$notifications->row()->count;
    }

    public function fetchUnread()
    {
        $notifications = $this->db->select('type, object_type, object_id')->from($this->table)->where(array('user_id' => $this->user, 'read' => 0))->group_by(array('type', 'object_type', 'object_id'))->get();
        if(!$notifications) return FALSE;
        $nots = array();
        $notifications = $notifications->result();
        foreach($notifications as $notification)
        {
            $not = new StdClass();

        }
        return $notifications->result();
    }

    public function fetchForType($type, $object_type, $objec_id)
    {
        
    }

    public function create($user_id, $type, $object_type, $object_id = NULL)
    {
        $data = array(
            'user_id' => $user_id,
            'type' => $type,
            'created' => time(),
            'read' => 0,
            'read_at' => NULL,
            'object_type' => $object_type,
            'object_id' => $object_id
        );
        $this->db->insert('notifications', $data);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function messages()
    {
        return $this->messages;
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Notification
{
    protected $user;
    protected $db;
    protected $errors = FALSE;
    protected $messages = FALSE;
    protected $table = 'notifications';
    public function __construct()
    {

    }

    public function setDatabase(CI_DB $db)
    {
        $this->db = $db;
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
        $notifications = $this->db->select('*')->from($this->table)->where(array('user_id' => $this->user, 'read' => 0))->get();
        if(!$notifications) return FALSE;
        return $notifications->result();
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

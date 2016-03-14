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
            switch($notification->type) {
                case 'submission_received_vote':
                    $sid = $notification->object_id;
                    // Fetch vote count for x submission
                    $votes = $this->db->select('COUNT(*) as count')->from('votes')->where('submission_id', $sid)->get();
                    if(!$votes || $votes->num_rows() == 0) continue;
                    $votes = $votes->row();
                    $not->type = 'submission_received_vote';
                    $not->message = "Your submission to random contest has received {$votes->count} votes!";
                    $not->destination = "#/contest/:id";
                    $nots[] = $not;
                break;

                case 'winner_chosen':

                break;

                case 'new_contest_launched':
                    $not->type = 'new_contest_launched';
                    $not->message = "Random company just launched a contest you may be interested in!";
                    $not->desination = "#/contest/:id";
                    $nots[] = $not;
                break;

                case 'submission_is_winner':
                    $not->type = 'submission_is_winner';
                    $not->message = "Congratulations! Your submission won!";
                    $not->destination = "#/dashboard?type=winning";
                    $nots[] = $not;
                break;

                case 'submission_confirmed':
                    $not->type = 'submission_confirmed';
                    $not->message = "Your submission to random contest has been accepted";
                    $not->destination = "#/contest/:id";
                    $nots[] = $not;
                break;

            }
        }
        $this->markAsRead();

        return $nots;
    }

    public function fetchForType($type, $object_type, $objec_id)
    {

    }

    public function markAsRead($id = NULL)
    {
        if(is_null($id))
        {
            $this->db->where('user_id', $this->user);
        } else {
            $this->db->where('id', $id);
        }
        return $this->db->update('notifications', array('read' => 1, 'read_at' => time()));
    }

    public function create($user_id, $type, $object_type, $object_id = NULL)
    {
        $data = array(
            'user_id' => (int)$user_id,
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

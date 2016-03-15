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
                    $submission = $this->db->select('id, owner, contest_id')->from('submissions')->where('id', $sid)->get()->row();
                    $contest = $this->db->select('id, owner')->from('contests')->where('id', $submission->contest_id)->get()->row();
                    $company = $this->db->select('name')->from('profiles')->where('id', $contest->owner)->get()->row();
                    $cname = $this->parse($company);
                    // Fetch vote count for x submission
                    $votes = $this->db->select('COUNT(*) as count')->from('votes')->where('submission_id', $sid)->get();
                    if(!$votes || $votes->num_rows() == 0) continue;
                    $votes = $votes->row();
                    $not->type = 'submission_received_vote';
                    $not->message = "Your submission {$cname} has received {$votes->count} votes!";
                    $not->destination = "#/contest/{$contest->id}";
                    $not->object_type = $notification->object_type;
                    $not->object_id = $notification->object_id;
                    $nots[] = $not;
                break;

                case 'winner_chosen':
                    $contest = $this->db->select('id, owner')->from('contests')->where('id', $notification->object_id)->get()->row();
                    $company = $this->db->select('name')->from('profiles')->where('id', $contest->owner)->get()->row();
                    $cname = $this->parse($company);
                    $not->type = 'winner_chosen';
                    $not->message = "A winner has been chosen for {$cname}'s contest!";
                    $not->destination = "#/contest/{$contest->id}";
                    $not->object_type = $notification->object_type;
                    $not->object_id = $notification->object_id;
                    $nots[] = $not;
                break;

                case 'new_contest_launched':
                    $contest = $this->db->select('id, owner')->from('contests')->where('id', $notification->object_id)->get()->row();
                    $company = $this->db->select('name')->from('profiles')->where('id', $contest->owner)->get()->row();
                    $cname = $this->parse($company);
                    $not->type = 'new_contest_launched';
                    $not->message = "{$cname} just launched a contest you may be interested in!";
                    $not->desination = "#/contest/{$contest->id}";
                    $not->object_type = $notification->object_type;
                    $not->object_id = $notification->object_id;
                    $nots[] = $not;
                break;

                case 'submission_is_winner':
                    $sid = $notification->object_id;
                    $submission = $this->db->select('id, owner, contest_id')->from('submissions')->where('id', $sid)->get()->row();
                    $contest = $this->db->select('id, owner')->from('contests')->where('id', $submission->contest_id)->get()->row();
                    $company = $this->db->select('name')->from('profiles')->where('id', $contest->owner)->get()->row();
                    $cname = $this->parse($company);
                    $not->type = 'submission_is_winner';
                    $not->message = "Congratulations! Your submission {$cname} won!";
                    $not->destination = "#/dashboard?type=winning";
                    $not->object_type = $notification->object_type;
                    $not->object_id = $notification->object_id;
                    $nots[] = $not;
                break;

                case 'submission_confirmed':
                    $sid = $notification->object_id;
                    $submission = $this->db->select('id, owner, contest_id')->from('submissions')->where('id', $sid)->get()->row();
                    $contest = $this->db->select('id, owner')->from('contests')->where('id', $submission->contest_id)->get()->row();
                    $company = $this->db->select('name')->from('profiles')->where('id', $contest->owner)->get()->row();
                    $cname = $this->parse($company);
                    $not->type = 'submission_confirmed';
                    $not->message = "Your submission {$cname} has been accepted";
                    $not->destination = "#/contest/{$contest->id}";
                    $not->object_type = $notification->object_type;
                    $not->object_id = $notification->object_id;
                    $nots[] = $not;
                break;

            }
        }
        //$this->markAsRead();

        return $nots;
    }

    function parse($obj)
    {
        if(is_null($obj) || is_null($obj->name))
        {
            return "";
        }
        return "for {$obj->name}";
    }

    public function fetchForType($type, $object_type, $objec_id)
    {

    }

    public function markAsRead($params = array())
    {
        $params['user_id'] = $this->user;
        $params['read'] = 0;

        return $this->db->where($params)->update('notifications', array('read' => 1, 'read_at' => time()));
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

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
        if(!$notifications)
            return 0;
        return (int)$notifications->row()->count;
    }

    public function fetchUnread()
    {
        $notifications = $this->db->select('type, object_type, object_id, created')->from($this->table)->where(array('user_id' => $this->user, 'read' => 0))->group_by(array('type', 'object_type', 'object_id'))->order_by('created', 'desc')->get();
        if(!$notifications) return FALSE;
        $nots = array();
        $notifications = $notifications->result();

        foreach($notifications as $notification)
        {
            $not = new StdClass();
            $not->created = $notification->created;
            switch($notification->type) {
                case 'submission_received_vote':
                    $not->section = 'votes';
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
                    $not->section = 'contest';
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
                    $not->section = 'contest';
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

                case 'submission_chosen':
                    $not->section = 'account';
                    $sid = $notification->object_id;
                    $submission = $this->db->select('id, owner, contest_id')->from('submissions')->where('id', $sid)->get()->row();
                    $contest = $this->db->select('id, owner')->from('contests')->where('id', $submission->contest_id)->get()->row();
                    $company = $this->db->select('name')->from('profiles')->where('id', $contest->owner)->get()->row();
                    $cname = $this->parse($company);
                    $not->type = 'submission_chosen';
                    $not->message = "Congratulations! Your submission {$cname} won!";
                    $not->destination = "#/dashboard?type=winning";
                    $not->object_type = $notification->object_type;
                    $not->object_id = $notification->object_id;
                    $nots[] = $not;
                break;

                case 'submission_confirmed':
                    $not->section = 'contest';
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

                case 'submission_created':
                    $not->section = 'contest';
                    $cid = $notification->object_id;
                    $submissions = $this->db->select('COUNT(*) as count')->from('contests')->where('contest_id', $cid)->get()->row()->count;
                    $not->type = 'submission_created';
                    $not->message = "Your contest has gotten {$submissions} submissions!";
                    $not->destination = "#/contest/{$cid}";
                    $not->object_type = $notification->object_type;
                    $not->object_id = $notification->object_id;
                    $nots[] = $not;
                break;

                case 'new_update':
                    $not->section = 'account';
                    $not->type = 'new_update';
                    $not->message = "Welcome to the new Tappyn!
                                    <br>
                                    In our recent updates:
                                    <ul>
                                    <li>We've fixed the new up-voting system, so spam accounts can't influence your submission.</li>
                                    <li>In order to give the voting system a fresh start, we've had to clear all past submission tables.</li>
                                    <li>We've implemented a notification system.</li>
                                    <li>We've increased website performance.</li>
                                    <li>We've made the user experience more streamlined.</li>
                                    </ul>
                                    Feel free to contact me with any questions or check out our <a href='#/faq'>FAQ page!</a>
                                    <br><br>
                                    Best,
                                    <br><br>
                                    Austin<br>";
                    $not->destination = "#";
                    $not->object_type = NULL;
                    $not->object_id = NULL;
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

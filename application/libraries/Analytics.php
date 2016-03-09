<?php defined("BASEPATH") or exit('No direct script access allowed');

use UAParser\Parser;

class Analytics
{
    protected $request_uri;
    protected $ip_address;
    protected $referrer;
    protected $attribution_time;
    protected $is_mobile;
    protected $family;
    protected $major;
    protected $os;
    protected $os_major;
    protected $domain;
    protected $country;
    protected $state;
    protected $town;
    protected $created_at;
    protected $session_hash = FALSE;

    public function __construct()
    {
        // Ignore any command line requests
        if(is_cli()) return;

        $parser = Parser::create();
        $ua = $parser->parse($this->input->server('HTTP_USER_AGENT'));
        /**
         * Load config and set server variables
         */
        $this->load->config('analytics');
        $this->attribution_time = $this->config->item('attribution_window');

        /**
         * Set user variables
         */
        $this->request_uri = $this->input->server('REQUEST_URI');
        $this->created_at = time();
        $this->ip_address = $this->input->server('REMOTE_ADDR');
        $this->referrer = $this->input->server('HTTP_REFERER');
        $this->family = $ua->ua->family;
        $this->major = $ua->ua->major;
        $this->os = $ua->os->family;
        $this->os_major = $ua->os->major;
        $this->is_mobile = $this->ismobile();
        //$this->geoCheckIP($this->input->server('REMOTE_ADDR'));
        $this->domain = NULL;
        $this->country = NULL;
        $this->state = NULL;
        $this->session_hash();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function track($args)
    {
        if(!$this->session_hash)
        {
            error_log("Attempting to track an event without a session_hash");
            return;
        }
        $args['session_hash'] = $this->session_hash;
        $args['created_at'] = time();
        if(!$this->db->insert('tracked_events', $args))
        {
            error_log($this->db->error()['message']);
        }
        return;
    }

    private function session_hash()
    {
        if(!$this->session->userdata($this->config->item('session_hash_name')) ||
            ($this->config->item('expiration_var_name') && $this->session->userdata($this->config->item('expiration_var_name')) < time()))
        {
            if(!$hash = $this->set_session_hash()) return FALSE;
        }
        else {
            $this->extend_session();
            $this->session_hash = $this->session->userdata($this->config->item('session_hash_name'));
        }
        return TRUE;
    }

    private function set_session_hash()
    {
        $valid = false;
        $limit = 100;
        $count = 0;
        $hash = $this->hash_gen();
        while(!$valid)
        {
            if(!$this->validate_ses_hash($hash))
            {
                $hash = $this->hash_gen();
                $count++;
                if($count >= $limit) return;
            } else {
                $valid = true;
                $this->session_hash = $hash;
            }
        }
        // Generate our session hash
        if($this->db->insert('analytics_sessions', array(
            'session_hash' => $hash,
            'referrer'     => $this->referrer,
            'user_agent'   => $this->input->server('HTTP_USER_AGENT'),
            'is_mobile'    => $this->is_mobile,
            'family'       => $this->family,
            'major'        => $this->major,
            'os'           => $this->os,
            'os_major'     => $this->os_major,
            'ip_address'   => $this->ip_address,
            'domain'       => $this->domain,
            'country'      => $this->country,
            'state'        => $this->state,
            'town'         => $this->town,
            'request_uri'  => $this->request_uri,
            //'user_id'      => $this->ion_auth->logged_in() ? $this->ion_auth->user()->row()->id : NULL,
            'created_at'   => $this->created_at
        )))
        {
            $this->session->set_userdata($this->config->item('session_hash_name'), $hash);
            if($this->config->item('attribution_window'))
            {

                $exp = time() + $this->config->item('attribution_window');
                $this->session->set_userdata($this->config->item('expiration_var_name'), $exp);
            }
        }
        return FALSE;
    }

    private function hash_gen()
    {
        return hash('sha256', microtime().uniqid());
    }

    private function validate_ses_hash($hash)
    {
        return TRUE;
    }

    private function extend_session()
    {
        if($this->config->item('attribution_window'))
        {
            $this->session->set_userdata(
                $this->config->item('expiration_var_name'),
                time() + $this->config->item('attribution_window'));
        }
    }

    private function geoCheckIP($ip)
   {
           //check, if the provided ip is valid
           if(!filter_var($ip, FILTER_VALIDATE_IP))
           {
                   return FALSE;
           }

           //contact ip-server
           $response=@file_get_contents('http://www.netip.de/search?query='.$ip);
           if (empty($response))
           {
                   return FALSE;
           }

           //Array containing all regex-patterns necessary to extract ip-geoinfo from page
           $patterns=array();
           $patterns["domain"] = '#Domain: (.*?)&nbsp;#i';
           $patterns["country"] = '#Country: (.*?)&nbsp;#i';
           $patterns["state"] = '#State/Region: (.*?)<br#i';
           $patterns["town"] = '#City: (.*?)<br#i';

           //check response from ipserver for above patterns
           foreach ($patterns as $key => $pattern)
           {
                   //store the result in array
                   $this->{$key} = preg_match($pattern,$response,$value) && !empty($value[1]) ? $value[1] : NULL;
           }

           return TRUE;
   }

   private function ismobile() {
       $is_mobile = '0';

       if(preg_match('/(android|up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
           $is_mobile=1;
       }

       if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
           $is_mobile=1;
       }

       $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
       $mobile_agents = array('w3c ','acs-','alav','alca','amoi','andr','audi','avan','benq','bird','blac','blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno','ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-','maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-','newt','noki','oper','palm','pana','pant','phil','play','port','prox','qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar','sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-','tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp','wapr','webc','winw','winw','xda','xda-');

       if(in_array($mobile_ua,$mobile_agents)) {
           $is_mobile=1;
       }

       if (isset($_SERVER['ALL_HTTP'])) {
           if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0) {
               $is_mobile=1;
           }
       }

       if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0) {
           $is_mobile=0;
       }

       return $is_mobile;
   }
}

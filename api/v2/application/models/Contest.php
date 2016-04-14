<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contest extends MY_Model
{
    private static $db;
    private static $table = 'contests';

    function __construct($data = NULL)
    {
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    static function all($params)
    {
        return self::$db->select('*')->from(self::$table)->limit(10)->get()->result('Contest');
    }

    static function get($id)
    {
        $contest = self::$db->select('*')->from(self::$table)->where('id', $id)->limit(1)->get()->row(0, 'Contest');
        return $contest;
    }

    public function accepting_submissions()
    {
        if($this->{ContestFields::STOP_TIME} < date('Y-m-d H:i:s') ||
           $this->{ContestFields::START_TIME} > date('Y-m-d H:i:s') ||
           $this->submission_count() >= $this->{ContestFields::SUBMISSION_LIMIT} ||
           $this->{ContestFields::PAID} == 0)
           {
               return FALSE;
           }
          return TRUE;
    }

    public function save()
    {
        // Preprocess all the contest data before trying to save
        $this->process();

        // Set static data points

        $this->data[ContestFields::CREATED_AT] = time();
        // Attempt to save, and return if successful;
        if(!self::$db->insert(self::$table, $this->data))
        {
            return FALSE;
        }
        $this->data['id'] = self::$db->insert_id();
        return TRUE;
    }

    public function update()
    {
        $this->process();

        if(!in_array('id', $this->data))
        {
            $this->errors = "You cant update a contest that doesnt exist";
            return FALSE;
        }

        //$this->data[ContestFields::UPDATED_AT] = time();
        if(!self::$db->where('id', $this->data['id'])->update(self::$table, $this->data))
        {
            return FALSE;
        }
        return TRUE;
    }

    function submission_count()
    {
        return self::$db->select('COUNT(*) as count')->from('submissions')->where('contest_id', $this->{ContestFields::ID})->get()->row()->count;
    }

    function impressions()
    {

    }

    /**
    * Validate existence of anything in the $data property.
    *
    * We also set defaults if some properties do not exist
    * @return boolean
    */
    protected function is_valid()
    {
        if(!in_array(ContestFields::SUBMISSION_LIMIT, $this->data)) $this->data[ContestFields::SUBMISSION_LIMIT] = ContestDefaults::SUBMISSION_LIMIT;
        if(!in_array(ContestFields::AGE, $this->data))              $this->data[ContestFields::AGE] = ContestDefaults::AGE;
        if(!in_array(ContestFields::GENDER, $this->data))           $this->data[ContestFields::GENDER] = ContestDefaults::GENDER;
        switch($this->data[ContestFields::AGE])
        {
            case 1:
            $this->data[ContestFields::MIN_AGE] = 18;
            $this->data[ContestFields::MAX_AGE] = 24;
            break;

            case 2:
            $this->data[ContestFields::MIN_AGE] = 25;
            $this->data[ContestFields::MAX_AGE] = 34;
            break;

            case 3:
            $this->data[ContestFields::MIN_AGE] = 35;
            $this->data[ContestFields::MAX_AGE] = 44;
            break;

            case 4:
            $this->data[ContestFields::MIN_AGE] = 45;
            $this->data[ContestFields::MAX_AGE] = 54;
            break;

            case 5:
            $this->data[ContestFields::MIN_AGE] = 55;
            $this->data[ContestFields::MAX_AGE] = 65;
            default:
            $this->data[ContestFields::MIN_AGE] = ContestDefaults::MIN_AGE;
            $this->data[ContestFields::MAX_AGE] = ContestDefaults::MAX_AGE;
        }
        return TRUE;
    }

//-----------------------------------------------------------------------------
//
// Form Validation Callbacks
//
// ----------------------------------------------------------------------------
    public function validate_industry($str)
    {
        return in_array($str, ContestInterests::$interests);
    }

    public function validate_emotion($str)
    {
        return in_array($str, ContestEmotions::$emotions);
    }

    public function validate_platform($str)
    {
        return in_array($str, ContestPlatforms::$platforms);
    }

    public function validate_objective($str)
    {
        return in_array($str, ContestObjectives::$objectives);
    }

    public function validate_display_type($str)
    {
        return in_array($str, ContestDisplayTypes::$formats);
    }

}

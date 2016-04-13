<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contest extends MY_Model
{
    private static $db;
    private static $table = 'contests';
    private static $platforms = array(
        'google',
        'facebook',
        'twitter',
        'general'
    );

    private static $emotions = array(
        'dove',
        'books',
        'mountain',
        'athelete',
        'eagle',
        'lightbulb',
        'glass',
        'cross',
        'crown'
    );

    private static $industries = array(
        ContestInterests::FOOD_BEVERAGE,
        ContestInterests::FINANCE_BUSINESS,
        ContestInterests::HEALTH_WELLNESS,
        ContestInterests::TRAVEL,
        ContestInterests::SOCIAL_NETWORK,
        ContestInterests::HOME_GARDEN,
        ContestInterests::EDUCATION,
        ContestInterests::ART_ENTERTAINMENT,
        ContestInterests::FASHION_BEAUTY,
        ContestInterests::TECH_SCIENCE,
        ContestInterests::PETS,
        ContestInterests::SPORTS_OUTDOORS,
    );
    private static $objective = array();

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

    public function save()
    {
        // Preprocess all the contest data before trying to save
        $this->process();

        // Attempt to save, and return if successful;
        if(!self::$db->insert(self::$table, $this->data))
        {
            return FALSE;
        }
        $this->data['id'] = self::$db->insert_id();
        return TRUE;
    }

    function impressions()
    {

    }

    public function validate_industry($str)
    {
        return in_array($str, self::$industries);
    }

    public function validate_emotion($str)
    {
        return in_array($str, self::$emotions);
    }

    public function validate_platform($str)
    {
        return in_array($str, self::$platforms);
    }

    public function validate_objective($str)
    {
        return in_array($str, self::$objectives);
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
}

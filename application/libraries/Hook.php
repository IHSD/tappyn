<?php defined("BASEPATH") or exit('No direct script access allowed');

class Hook
{
    public static $hooks;
    public static $manager = FALSE;
    public static $libraries = array();
    public function __construct()
    {
        self::$hooks = new StdClass();
    }

    public static function initialize()
    {
        if(!self::$manager) self::$manager = new Hook();
        return self::$manager;
    }

    public static function register($event, $name, $class, $method, $args = array())
    {
        self::$hooks->{$event}[$name] = new StdClass();
        self::$hooks->{$event}[$name]->class        = $class;
        self::$hooks->{$event}[$name]->method       = $method;
        self::$hooks->{$event}[$name]->arguments    = $args;
    }

    public static function show()
    {
        var_dump(self::$hooks);
    }

    public static function unlink($event, $name = NULL)
    {
        if(is_null($name))
        {
            unset(self::$hooks->{$event});
        }
        else
        {
            unset(self::$hooks->{$event}[$name]);
        }
    }

    public static function register_library($library)
    {
        $className = strtolower(get_class($library));
        self::$libraries[$className] = $library;
    }

    public static function trigger($events, $args = array())
    {
        if (is_array($events) && !empty($events))
		{
			foreach ($events as $event)
			{
				self::trigger($event, $args);
			}
		}
		else
		{
			if (isset(self::$hooks->$events) && !empty(self::$hooks->$events))
			{
				foreach (self::$hooks->$events as $name => $hook)
				{
					self::call_hook($events, $name, $args);
				}
			}
		}
    }

    public static function call_hook($event, $name, $args = array())
	{
		if (isset(self::$hooks->{$event}[$name]) && method_exists(self::$hooks->{$event}[$name]->class, self::$hooks->{$event}[$name]->method))
		{
			$hook = self::$hooks->{$event}[$name];
            $arguments = $hook->arguments;
            if(!empty($args))
            {
                foreach($args as $arg)
                {
                    $arguments[] = $arg;
                }
            }
			return call_user_func_array(array(self::$libraries[$hook->class], $hook->method), $arguments);
		}

		return FALSE;
	}
}

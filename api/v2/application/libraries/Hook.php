<?php defined("BASEPATH") or exit('No direct script access allowed');

class Hook
{
    public static $hooks;
    public static $manager = FALSE;
    public static $models = array();
    public function __construct($hooks)
    {
        self::$hooks = new StdClass();
        foreach($hooks as $hook)
        {
            self::register($hook['event'], $hook['name'], $hook['class'], $hook['method'], $hook['args']);
        }
    }

    /**
     * Initialize our Hook Manager
     * @return self
     */
    public static function initialize($hooks)
    {
        if(!self::$manager) self::$manager = new Hook($hooks);
        return self::$manager;
    }

    /**
     * Register a hook
     * @param  string $event  Event that should trigger this hook
     * @param  string $name   Name of the hook, purley for namespacing
     * @param  object $class  Instance of the object we want to call method from
     * @param  string $method Method name to call
     * @param  array  $args   Associative array or args to pass to function
     * @return void
     */
    public static function register($event, $name, $class, $method, $args = array())
    {
        self::$hooks->{$event}[$name] = new StdClass();
        self::$hooks->{$event}[$name]->class        = $class;
        self::$hooks->{$event}[$name]->method       = $method;
        self::$hooks->{$event}[$name]->arguments    = $args;
    }

    /**
     * Show currently registered hookd
     * @test
     * @return void
     */
    public static function show()
    {
        var_dump(self::$hooks);
    }

    /**
     * Remove a hook from registry. If a name is passed, we remove that individual hook.
     * Otherwise we remove all hooks from an event
     * @param  string $event Name of the event
     * @param  string $name  Name of the hook to remove *optional
     * @return boolean
     */
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

    /**
     * Register a model for the hook manager to use to call functions
     * @param  object $model Instance of the class to use
     * @return void
     */
    public static function register_model($model)
    {
        if(!is_array($model))
        {
            $className = strtolower(get_class($model));
            self::$models[$className] = $model;
        }
        else
        {
            foreach($model as $mod)
            {
                self::register_model($mod);
            }
        }
    }

    /**
     * Broadcast an event occurence
     * @param  array|string $events Events to trigger
     * @param  array  $args
     * @return void
     */
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

    /**
     * Call a hook
     * @param  string $event
     * @param  string $name
     * @param  array  $args
     * @return void
     */
    public static function call_hook($event, $name, $args = array())
	{
		if (isset(self::$hooks->{$event}[$name]) && method_exists(self::$hooks->{$event}[$name]->class, self::$hooks->{$event}[$name]->method))
		{
			$hook = self::$hooks->{$event}[$name];
            $arguments = $hook->arguments;
            if(!empty($args))
            {
                foreach($args as $key => $value)
                {
                    $arguments[$key] = $value;
                }
            }
			return call_user_func_array(array(self::$models[strtolower($hook->class)], $hook->method), array($arguments));
		}

		return FALSE;
	}
}

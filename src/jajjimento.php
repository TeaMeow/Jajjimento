<?php

/**
* Jajjimento Class
*
* @category  Tools
* @package   Jajjimento
* @author    Yami Odymel <yamiodymel@gmail.com>
* @copyright Copyright (c) 2015
* @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* @link      http://github.com/TeaMeow/Jajjimento
* @version   1.0
**/

class jajjimento
{
    /** 
     * Set this as true if Aira does exist, so Aira will be called once the validation not passed.
     * 
     * @var bool
     */
 
    private $hasAira = false;
    
    /**
     * Used to store the rules, and we can load the rules from here next time we use it.
     * 
     * @var array
     */
     
    public $rules = [];
    
    /**
     * The index of the last rule,
     * 
     * default is -1, because array start from 0.
     * 
     * @var int
     */
    
    private $last = -1;
    
    /**
     * Used to check if the validation was passed or not.
     * 
     * @var bool
     */
     
    private $failed = false;
    
    /**
     * @var string      $field        Stores 'this round' rule informations.
     * @var string      $type         Stores 'this round' rule informations.
     * @var int|null    $min          Stores 'this round' rule informations.
     * @var int|null    $max          Stores 'this round' rule informations.
     * @var bool|null   $required     Stores 'this round' rule informations.
     * @var string|null $dataFormat   Stores 'this round' rule informations.
     * @var string|null $inside       Stores 'this round' rule informations.
     * @var string|null $urlNot       Stores 'this round' rule informations.
     * @var bool        $trim         Stores 'this round' rule informations.
     * @var string|null $format       Stores 'this round' rule informations.
     */
     
    private $field, $type, $min, $max, $required, $dateFormat, $inside, $urlNot, $trim, $format;

    
    
    
    /**
     * CALL
     * 
     * Used to handle some PHP keywords or .. ALL.
     * 
     * @return mixed
     */
    
    public function __call($name, $args) 
    {
        $basicFunctions = ['type', 'min', 'max', 'required', 'format', 'trim', 'req', 'length', 'range',
                           'date', 'inside', 'email', 'gender', 'ip', 'ipv4', 'ipv6', 'url', 'urlNot'];
        
        if(in_array($name, $basicFunctions))
            return call_user_func_array(array($this, '_' . $name), $args);
            
        return $this;
    }
    
    
    
    
    /**
     * Source
     * 
     * Set a source here, and we will check it with our rules later.
     * 
     * @param array $source   The array we which we want to check later.
     * 
     * @retrun jajjimento
     */
    
    function source($source)
    {
        /** Source must be an array to continue */
        if(!is_array($source)) return $this;
    }
    
    
    
    
    /**
     * Add
     * 
     * Add a field or a raw variable to the list, and check it later.
     * 
     * @param string $variable   The field or the raw variable.
     * 
     * @return jajjimento
     */
     
    function add($variable)
    {
        $this->rules[] = ['field'      => $variable,
                          'type'       => null,
                          'min'        => null,
                          'max'        => null,
                          'required'   => null,
                          'dateFormat' => null,
                          'inside'     => null,
                          'urlNot'     => null,
                          'trim'       => false,
                          'format'     => null];
       
       /** Change the index */
       $this->last++;
       
       return $this;                     
    }
    



    /**
     * Type
     * 
     * Set the type of the last rule, such as 'length', 'range', 'date'.
     * 
     * @param string $type
     * 
     * @return jajjimento
     */
    
    function _type($type)
    {
        return $this->lastRule('type', $type);
    }
    
    
    
    
    /**
     * Min
     * 
     * Set the minimum limit of the rule.
     * 
     * @param int $min
     * 
     * @return jajjimento
     */
     
    function _min($min)
    {
        return $this->lastRule('min', $min);
    }
    
    
    
    
    /**
     * Max
     * 
     * Set the maximum limit of the rule.
     * 
     * @param int $max
     * 
     * @return jajjimento
     */
     
    function _max($max)
    {
        return $this->lastRule('max', $max);
    }
    
    
    
    
    /**
     * Required
     * 
     * Set a rule as required for it's value.
     * 
     * @return jajjimento
     */
    
    function _required()
    {
        return $this->lastRule('required', true);
    }
    
    
    
    
    /**
     * Date Format
     * 
     * Set the date formats which are allowed.
     * 
     * @param array|string $date
     * 
     * @return jajjimento
     */
    
    function _dateFormat($date)
    {
        return $this->lastRule('dateFormat', $date);
    }
    
    
    
    
    /**
     * Inside
     * 
     * Set a list, and make sure the value is in this list or ggwp.
     * 
     * @param array $list
     * 
     * @return jajjimento
     */
    
    function _inside($list)
    {
        return $this->lastRule('inside', $list);
    }
    
    
    
    
    /**
     * Trim
     * 
     * Set the trim option as true for the last rule.
     * 
     * @return jajjimento
     */
    
    function _trim()
    {
        return $this->lastRule('trim', true);
    }
    
    
    
    
    /**
     * Format
     * 
     * Set the RegEx rule for the last rule.
     * 
     * @param string $regex
     * 
     * @return jajjimento
     */
    
    function _format($regex)
    {
        return $this->lastRule('format', $regex);
    }
    
    


    /***********************************************
    /***********************************************
    /************ S H O R T H A N D S **************
    /***********************************************
    /***********************************************
    
    /**
     * Req
     * 
     * Same as required.
     * 
     * @return jajjimento
     */
     
    function _req()
    {
        return $this->_required();
    }
    
    
    
    
    /**
     * Length
     * 
     * Shorthands for type('length')->min()->max().
     * 
     * @param int $min   The minimun limit.
     * @param int $max   The maximun limit.
     * 
     * @return jajjimento
     */
    
    function _length($min, $max)
    {
        return $this->type('length')
                    ->min($min)
                    ->max($max);
    }




    /**
     * Range
     * 
     * Shorthands for type('range')->min()->max().
     * 
     * @param int $min   The minimun limit.
     * @param int $max   The maximun limit.
     * 
     * @return jajjimento
     */
    
    function _range($min, $max)
    {
        return $this->type('range')
                    ->min($min)
                    ->max($max);
    }




    /**
     * Date
     * 
     * Shorthands for type('date')->dateFormat().
     * 
     * @see https://en.wikipedia.org/wiki/ISO_8601
     * 
     * @param array|string $date   The date format.
     * 
     * @return jajjimento
     */
    
    function _date($date)
    {
        return $this->type('date')
                    ->dateFormat($date);
    }
    
    
    
    
    /**
     * In
     * 
     * Shorthands for type('in')->inside().
     * 
     * @param array $inside
     * 
     * @return jajjimento
     */
    
    function _in($inside)
    {
        return $this->type('in')
                    ->inside($inside);
    }
    
    
    
    
    /**
     * Email
     * 
     * Shorthands for type('email').
     * 
     * @return jajjimento
     */
    
    function _email()
    {
        return $this->type('email');
    }
    
    
    
    
    /**
     * Gender
     * 
     * Shorthands for type('gender').
     * 
     * @return jajjimento
     */
    
    function _gender()
    {
        return $this->type('gender');
    }
    
    
    
    
    /**
     * IP
     * 
     * Shorthands for type('ip').
     * 
     * @return jajjimento
     */
    
    function _ip()
    {
        return $this->type('ip');
    }
    
    
    
    
    /**
     * IPv4
     * 
     * Shorthands for type('ipv4').
     * 
     * @return jajjimento
     */
    
    function _ipv4()
    {
        return $this->type('ipv4');
    }
    
    
    
    
    /**
     * IPv6
     * 
     * Shorthands for type('ipv6').
     * 
     * @return jajjimento
     */
    
    function _ipv6()
    {
        return $this->type('ipv6');
    }
    
    
    
    
    /**
     * URL
     * 
     * Shorthands for type('url').
     * 
     * @return jajjimento
     */
    
    function _url()
    {
        return $this->type('url');
    }
    
    
    
    
    /***********************************************
    /***********************************************
    /************ V A L I D A T I O N **************
    /***********************************************
    /***********************************************

    /**
     * Check 
     * 
     * Here to start the validation.
     */
     
    function check()
    {
        foreach($this->rules as $rule)
        {
            /** Explode the variables first */
            $this->setVariables($rule);
        }
    }



    /***********************************************
    /***********************************************
    /*************** H E L P E R S *****************
    /***********************************************
    /***********************************************
    
    /**
     * Last Rule
     * 
     * Modify when the value is setted or get the information of the last rule.
     * 
     * @param string info    The information name which we want to get.
     * @param string value   The value we want to change to.
     * 
     * @return mixed
     */
    
    function lastRule($info, $value=null)
    {
       /** Get the information if we don't want to modify it */
       if($value == null)
           return $this->rules[$this->last][$info];
       
       /** Or change the value if we want to */
       $this->rules[$this->last][$info] = $value;
       
       return $this;
    }
    
    
    
    
    /**
     * Set Variables
     * 
     * Explode the variables into class variables, so we can use it in any funtions of the class.
     * 
     * @param array $rule   The rule which we want to explode.
     * 
     * @return jajjimento
     */
    
    function setVariables($rule)
    {
        $this->field      = $rule['field'];
        $this->min        = $rule['min'];
        $this->max        = $rule['max'];
        $this->required   = $rule['required'];
        $this->dateFormat = $rule['dateFormat'];
        $this->inside     = $rule['inside'];
        $this->urlNot     = $rule['urlNot'];
        $this->trim       = $rule['trim'];
        $this->format     = $rule['format'];

        /** Set the failed flag back to false like nothing happened before, just like u and ur ex */
        $this->failed     = false;
        
        return $this;
    }
}
    
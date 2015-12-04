<?php

/**
* Jajjimento Class
*
* @category  Tools
* @package   Jajjimento
* @author    Yami Odymel <yamiodymel@gmail.com>
* @copyright Copyright (c) 2015
* @license   https://en.wikipedia.org/wiki/MIT_License MIT License
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
     * The charset of the values, so we can validate them correctly.
     * 
     * @var string
     */
    
    private $charset = 'UTF-8';
     
    /**
     * Used to store the rules, and we can load the rules from here next time we use it.
     * 
     * @var array
     */
     
    public $rules = [];
    
    /**
     * The safer array, used to store the validated datas.
     * 
     * @var array
     */
    
    public $safe = [];
    
    /**
     * Errors here, everyone scared.
     * 
     * @var array
     */
    
    private $errors = [];
    
    /**
     * The index of the last rule,
     * 
     * default is -1, because array start from 0.
     * 
     * @var int
     */
    
    private $last = -1;
    
    /**
     * The source data.
     * 
     * @var bool|array
     */
    
    private $source = false;
    
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
     * @var array|null  $target       Stores 'this round' rule informations.
     * @var bool        $failed       Stores 'this round' rule informations.
     */
     
    private $field, $type, $min, $max, $required, $dateFormat, $inside, $urlNot, $trim, $format, $target, $failed;

    
    
    
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
                           'date', 'inside', 'email', 'gender', 'ip', 'ipv4', 'ipv6', 'url', 'urlNot', 'target'];
        
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
                          'format'     => null,
                          'target'     => null,
                          'failed'     => false];
       
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
    
    
    
    
    /**
     * Target
     * 
     * Set the target, so we can validate the two fields were the same or not.
     * 
     * @param string $target    The field name of the target which we want to compare, or just the raw variable.
     * @param bool   $isField   When this is true, we will compare with the field which the name is same as the $target, otherwise we compare with the target directly.
     * 
     * @return jajjimento
     */
    
    function _target($target, $isField=true)
    {
        return $this->lastRule('target', [$target, $isField]);
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
    
    
    
    
    /**
     * Equals
     * 
     * Shorthands for type('equals')->target().
     * 
     * @return jajjimento
     */
    
    function _equals($target, $isField=true)
    {
        return $this->type('equals')
                    ->target($target, $isField);
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
            
            /** Remove the whitespace at the end of the string if needed */
            if($this->trim)
                $this->validateTrim();
            
            /** Make sure there's something in the field */
            if($this->required)
                if(!$this->validateRequired())
                    continue;
                    
            /** The best part of the jajjimento, most people died here, so, let's go! */
            switch($this->type)
            {
                case 'length': $this->validateLength();   break;
                case 'range' : $this->validateRange();    break;
                case 'date'  : $this->validateDate();     break;
                case 'in'    : $this->validateIn();       break;
                case 'email' : $this->validateEmail();    break;
                case 'gender': $this->validateGender();   break;
                case 'ip'    : $this->validateIp();       break;
                case 'ipv4'  : $this->validateIp('ipv4'); break;
                case 'ipv4'  : $this->validateIp('ipv6'); break;
                case 'url'   : $this->validateUrl();      break;
                case 'equals': $this->validateEquals();   break;
            }
            
            /** No need to continue if this field was not passed the validation, let's move on to the next field */
            if($this->failed)
                continue;
            
            /** The last step, check the format */
            if($this->format)
                if(!$this->validateFormat())
                    $this->error('The format is wrong.');
        }
        
        /** Call the log function if any errors occurred */
        if(!empty($this->errors))
            $this->log();
        
        return empty($this->errors);
    }
    
    
    
    
    /**
     * Validate Trim
     * 
     * Remove the whitespace at the end of the string.
     * 
     * @return jajjimento
     */
    
    function validateTrim()
    {
        preg_match('/^[^\w]{0,}(.*?)[^\w]{0,}$/iu', $this->field, $matches);
   
        if(count($matches) < 2)
            return;
   
        $this->field = $matches[1];
        
        return $this;
    }
    
    
    
    
    /**
     * Validate Required
     * 
     * Check the value and it must not be empty because it's required.
     * 
     * @return bool   Returns true when passed, ofcourse false when no.
     */
     
    function validateRequired()
    {
        if(!$this->field || ctype_space($this->field) || mb_strlen($this->field, $this->charset) == 0)
            return $this->error('Required but nothing here.');

       
        return true;
    }
    
    
    
    
    /**
     * Validate Length
     * 
     * Check the length of the value is long enough like my dick or shorter than mine.
     * 
     * @return bool
     */
    
    function validateLength()
    {
        $length = mb_strlen($this->field, $this->charset);
     
        if($length > $this->max)
            return $this->error('Too long.');
        elseif($length < $this->min)
            return $this->error('Too short.');
        
        return true;
    }
    
    
    
    
    /**
     * Validate Range
     * 
     * Make sure the range is within my limit, otherwise I will be pissed off.
     * 
     * @return bool
     */
    
    function validateRange()
    {
        if(!is_numeric($this->field))
            return $this->error('Not a numeric.');

        elseif($this->field > $this->max)
            return $this->error('Too large.');

        elseif($this->field < $this->min)
            return $this->error('Too small.');
        
        return true;
    }
    
    
    
    
    /**
     * Validate Date
     * 
     * Is this date same as the format which we wanted? Better is.
     * 
     * @return bool
     */
    
    function validateDate()
    {
        $date = DateTime::createFromFormat($this->dateFormat, $this->field);
        
        if(!$date && $date->format($this->dateDormat) == $date)
            return $this->error('Date format is not right or there\'s no this day.');
        
        return true;
    }
    
    
    
    
    /**
     * Validate In
     * 
     * Is the value in the array?
     * 
     * @return bool
     */
    
    function validateIn()
    {
        if(!in_array($this->field, $this->inside))
            return $this->error('Not inside the list.');
            
        return true;
    }
   
    
    
    
    /**
     * Validate Email
     * 
     * Make sure it's an email address.
     *
     * @return bool
     */
    
    function validateEmail()
    {
        if(!filter_var($this->field, FILTER_VALIDATE_EMAIL))
            return $this->error('Not an email.');
            
        return true;
    }
    
    
    
    
    /**
     * Validate Gender
     * 
     * Yes, I mean, there's should be other genders right, not only men and women living on the earth I think.
     * 
     * @return bool
     */
    
    function validateGender()
    {
        $gender = strtolower($this->field);
        
        if($gender != 'o' || $gender != 'm' || $gender != 'f')
            return $this->error('Not a gender.');
        
        return true;
    }
    
    
    
    
    /**
     * Validate IP
     * 
     * Make sure it's an ip address.
     * 
     * @param string|null $type   ipv4 and ipv6, null if both were allowed.
     * 
     * @return bool
     */
    
    function validateIp($type=null)
    {
        if($type == 'ipv4')
            if(!filter_var($this->field, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                return $this->error('Not an ipv4.');
        
        if($type == 'ipv6')
            if(!filter_var($this->field, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
                return $this->error('Not an ipv4.');
        
        if(!filter_var($this->field, FILTER_VALIDATE_IP))
            return $this->error('Not an ip.');
        
        return true;
    }
    
    
    
    
    /**
     * Validate URL
     * 
     * Make sure it's an url address.
     * 
     * @return bool
     */
    
    function validateUrl()
    {
        if(!filter_var($this->field, FILTER_VALIDATE_URL))
            return $this->error('Not an url address.');
            
        return true;
    }
    
    
    
    
    /**
     * Validate Equals
     * 
     * Make sure both were same or ggwp.
     * 
     * @return bool
     */
    
    function validateEquals()
    {
        $isField = $this->target[1];
        $compare = ($isField) ? $this->source[$this->target[0]] : $this->target[0;
        
        
        if($this->target[0])
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
        $this->field      = ($this->source == false) ? $rule['field'] : $this->source[$this->field];
        $this->min        = $rule['min'];
        $this->max        = $rule['max'];
        $this->required   = $rule['required'];
        $this->dateFormat = $rule['dateFormat'];
        $this->inside     = $rule['inside'];
        $this->urlNot     = $rule['urlNot'];
        $this->trim       = $rule['trim'];
        $this->format     = $rule['format'];
        $this->target     = $rule['target'];

        /** Set the failed flag back to false like nothing happened before, just like u and ur ex */
        $this->failed     = false;
        
        return $this;
    }
    
    
    
    
    /***********************************************
    /***********************************************
    /****************  E R R O R S *****************
    /***********************************************
    /***********************************************
    
    /**
     * Error
     * 
     * Log the error data here and why.
     * 
     * @param string $reason   Why the rule not passed.
     * 
     * @return bool
     */
    
    function error($reason)
    {
        $this->failed = true;
        
        return false;
    }
}
?>    
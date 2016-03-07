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

if(session_status() == PHP_SESSION_NONE)
    session_start();

class Jajjimento
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

    public $errors = [];

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
     * The CSRF switch, set false when you don't want the csrf proection.
     *
     * @var bool
     */

    public $csrf = false;

    /**
     * The name of the hashed csrf token which we stored in the COOKIES.
     *
     * @var string
     */

    public $csrfCookieName = 'jajjimento_token';

    /**
     * The name of the csrf token field which you should passed though the form.
     *
     * @var string
     */

    public $csrfFieldName = 'jajjimento_token';

    /**
     * The name of the csrf token which we stored in the SESSIONS.
     *
     * @var string
     */

    public $csrfName  = 'jajjimentoToken';

    /**
     * The name of the csrf token header.
     *
     * @var string
     */

    public $csrfHeaderName = 'X-CSRF-TOKEN';

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

    private $field, $rawField, $type, $min, $max, $required, $dateFormat, $inside, $urlNot, $trim, $format, $target, $failed;




    /**
     * CALL
     *
     * Used to handle some PHP keywords or .. ALL.
     *
     * @return mixed
     */

    public function __call($name, $args)
    {
        $basicFunctions = ['type', 'min', 'max', 'required', 'format', 'trim', 'req', 'length', 'range', 'equals',
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

        $this->source = $source;

        return $this;
    }




    /**
     * Add
     *
     * Add a field or a raw variable to the list, and check it later.
     *
     * @param string $variable   The field or the raw variable.
     *
     * @return Jajjimento
     */

    function add($variable)
    {
        $this->rules[] = ['field'      => $variable,
                          'type'       => null,
                          'min'        => null,
                          'max'        => null,
                          'required'   => false,
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
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
     * @return Jajjimento
     */

    function _equals($target, $isField=true)
    {
        return $this->type('equals')
                    ->target($target, $isField);
    }




    /***********************************************
    /***********************************************
    /****************** F I N A L ******************
    /***********************************************
    /***********************************************

    /**
     * Check
     *
     * Here to start the validation.
     *
     * @return bool
     */

    function check()
    {
        /** Check the csrf token and make sure it's not manual mode */
        if($this->csrf && $this->source)
            $this->Csrf();

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

            /** Turn the empty string to null */
            $this->emptyToNull();

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

            /** Store the safer data to the safe */
            if($this->source)
                $this->safe[$rule['field']] = $this->field;
        }

        /** Call the log function if any errors occurred */
        if(!empty($this->errors))
            $this->log();

        $isEmpty = empty($this->errors);

        /** Remove the safe if the validation failure */
        $this->safe = [];

        $this->clean();

        return $isEmpty;
    }




    /**
     * Load Check
     *
     * Load the rules and start a validation with those rules.
     *
     * @param array $rules   A jajjimento generated rules .
     *
     * @return bool
     */

    function loadCheck($rules)
    {
        $this->rules = $rules;

        return $this->check();
    }




    /**
     * Save
     *
     * Return the rules.
     *
     * @retrun array
     */

    function save()
    {
        $rules = $this->rules;

        $this->clean();

        return $rules;
    }




    /***********************************************
    /***********************************************
    /****************** C S R F ********************
    /***********************************************
    /***********************************************

    /**
     * Process Csrf Protection
     *
     * @return Jajjimento
     */

    function Csrf()
    {
        /** Generate a new csfr token if there's no token generated */
        if(!$this->hasCsrf())
            $this->initializeCsrf();

        //if(!hash_equals($token, crypy($trueToken, $key)))
        //

        if(!$this->csrfHeaderCheck() && !$this->csrfFieldCheck())
            $this->error('Crumb error.');

        return $this;
    }




    /**
     * Csrf Header Check
     *
     * Check the csrf token is in the header and correct or not.
     *
     * @return bool
     */

    function csrfHeaderCheck()
    {
        $token = false;

        /** Get the value of the custom header */
        foreach (getallheaders() as $name => $value)
            if($name == $this->csrfHeaderName)
                $token = $value;

        return $this->csrfValidate($token);
    }




    /**
     * Csrf Field Check
     *
     * Check the csrf token is in the field and correct or not.
     *
     * @return bool
     */

    function csrfFieldCheck()
    {
        $token = $this->source[$this->csrfFieldName];

        return $this->csrfValidate($token);
    }




    /**
     * Csrf Validate
     *
     * Compare two tokens were the same or not.
     *
     * @param string|false $token   The token which we got from the cilent side.
     *
     * @return bool
     */

    function csrfValidate($token=false)
    {
        if(!$token) return false;

        return $token == $_SESSION[$this->csrfName];
    }




    /**
     * Return Hashed Crumb
     *
     * @return string
     */

    function getCrumbValue()
    {
        return $_SESSION[$this->csrfName];
    }




    /**
     * Insert Crumb
     *
     * @param array|null $attrs   Key as the attr name, value as the attr value.
     *
     * @return string
     */

    function insertCrumb($attrs=null)
    {
        $compiledAttrs = '';

        if($attrs != null)
            foreach($attrs as $name => $value)
                $compiledAttrs .= ' ' . $name . '="' . $value . '"';

        return '<input type="hidden" name="' . $this->csrfFieldName . '" value="' . $_SESSION[$this->csrfName] . '"' . $compiledAttrs . '>';
    }




    /**
     * Has Csrf
     *
     * Returns true if we had already initialized the csrf protection thing.
     *
     * @return bool
     */

    function hasCsrf()
    {
        return isset($_SESSION[$this->csrfName]);
    }




    /**
     * Initialize Csrf
     *
     * Initialize the csrf token and the key.
     *
     * @return Jajjimento
     */

    function initializeCsrf()
    {
        /** Generate a random string */
        $token        = md5(uniqid(mt_rand(), true));

        /** Store the token into the session */
        $_SESSION[$this->csrfName]    = $token;
        /** Store the hashed token into the cookie */
        setcookie($this->csrfCookieName, $token, time() + (10 * 365 * 24 * 60 * 60), '', '', false, true);


        return $this;
    }




    /***********************************************
    /***********************************************
    /************ V A L I D A T I O N **************
    /***********************************************
    /***********************************************

    /**
     * Validate Trim
     *
     * Remove the whitespace at the end of the string.
     *
     * @return Jajjimento
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
        if(!$this->required && $this->field == null)
            return true;

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
        $passed = true;

        foreach((array) $this->dateFormat as $dateFormat)
        {
            $date = DateTime::createFromFormat($dateFormat, $this->field);

            $passed = ($date && $date->format($this->dateDormat) == $date);
        }

        if(!$passed)
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
        $target  = $this->target[0];
        $compare = ($isField) ? $this->source[$target] : $target;

        if($this->field != $compare)
            return $this->error('Both weren\'t the same.');
    }




    /**
     * Validate Format
     *
     * Time to RegEx.
     *
     * @return bool
     */

    function validateFormat()
    {
        preg_match($this->format, $this->field, $matches);

        if(count($matches) < 2)
            return $this->error('The format was not correct.');

        return true;
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
     * @return Jajjimento
     */

    function setVariables($rule)
    {
        if($this->source == false)
            $this->field = $rule['field'];
        elseif(isset($this->source[$rule['field']]))
            $this->field = $this->source[$rule['field']];
        else
            $this->field = null;

        $this->rawField   = $rule['field'];
        $this->type       = $rule['type'];
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




    /**
     * Clean
     *
     * @return Jajjimento
     */

    function clean()
    {
        $this->field    = $this->rawField   = $this->type   = $this->min    = $this->max  =
        $this->required = $this->dateFormat = $this->inside = $this->urlNot = $this->trim =
        $this->format   = $this->target     = $this->failed = null;

        $this->rules  = [];
        $this->last   = -1;
        $this->source = false;
    }




    /**
     * Empty To Null
     *
     * Turn the emprt string to null.
     *
     * @return Jajjimento
     */

    function emptyToNull()
    {
        if($this->field == '')
            $this->field = null;

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
        $this->errors[] = ['field'      => $this->field,
                           'rawField'   => $this->rawField,
                           'type'       => $this->type,
                           'min'        => $this->min,
                           'max'        => $this->max,
                           'required'   => $this->required,
                           'dateFormat' => $this->dateFormat,
                           'inside'     => $this->inside,
                           'urlNot'     => $this->urlNot,
                           'trim'       => $this->trim,
                           'format'     => $this->format,
                           'target'     => $this->target,
                           'reason'     => $reason,
                           'time'       => time()];

        $this->failed = true;

        return false;
    }




    /**
     * Log
     *
     */

    function log()
    {
        return ($this->hasAira) ? Aira::Add('INCORRECT_FORM')
                                : $this;
    }


    static function fill($fillWith, $data, $desiredData)
    {
        $keys = array_keys($data);

        foreach($desiredData as $desiredKey)
            if(in_array($desiredKey, $keys))
                continue;
            else
                $output[$desiredKey] = $fillWith;

        return $output;
    }
}
?>
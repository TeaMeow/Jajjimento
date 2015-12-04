<?php

/**
 * Jajjimento Class
 *
 * @category  Tools
 * @package   Aira
 * @author    Yami Odymel <yamiodymel@gmail.com>
 * @copyright Copyright (c) 2015
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      http://github.com/TeaMeow/Jajjimento
 * @version   1.0
 **/

class Jajjimento
{
    private $Source;
    public  $Rules;
    public  $Errors = [];
    
    /** Set true if we're using Aira */
    private $HasAira = false;
    
    
    
    
    /**
     * CONSTRUCT
     * 
     * call PreCheck() if $Source, $MainType, $SubType IS NOT null.
     * 
     * @param array  $Source     The source of the rules.
     * @param string $MainType   The type of the rule, ex: 'User'.
     * @param string $SubType    The sub type like 'Login', 'Create'.
     */
    
    function __construct($Source=NULL, $MainType=NULL, $SubType=NULL)
    {
        if($Source != NULL && $MainType != NULL && $SubType != NULL)
            $this->Source($Source)
                 ->PreCheck($MainType, $SubType);
    }
    
    
    
    
    /**
     * Source
     * 
     * Set the $_POST or $_GET as a source to validate.
     * 
     * @param  array $Source   The source of the rules.
     * @return Jajjimento
     */
     
    public function Source($Source)
    {
        /** Return if the souce is not an array, Source must be an array like: $_POST, $_GET */
        if(!is_array($Source)) return $this;
        
        /** Change the source to the source which we just inputted */
        $this->Source = $Source;
        
        return $this;
    }
    
    
    
    
    /**
     * Manual
     * 
     * Now we will get the value directly, not from the source.
     * 
     * @return Jajjimento
     */
     
    public function Manual()
    {
        /** Change the source to the source which we just inputted */
        $this->Source = 'NO_SOURCE';
        
        return $this;
    }
    
    
    
    
    /**
     * Add
     * 
     * Add a single rule for later to check.
     * 
     * @param  string $Field          The name of the field.
     * @param  string $Type           What's the type we are going to force of? ex: Email, Length, IPV4.
     * @param  bool   $Required       Set true if it's required.
     * @param  int    $Min            Used to be the min value when you validate a length.
     * @param  int    $Max            Same as the $Min but it's for max value.
     * @param  string $StringFormat   The format of the string, like a-z0-9, a-Z0-9.
     * @param  bool   $Trim           Set true to remove the white space after the last word.
     * 
     * @return Jajjimento
     */
    
    public function Add($Field, $Type, $Required, $Min, $Max=null, $StringFormat=null, $Trim=true)
    {
        /** Add this to the rules list */
        $this->Rules[] = ['Field'    => $Field, 
                          'Type'     => $Type, 
                          'Required' => $Required, 
                          'Min'      => $Min,
                          'Max'      => $Max,
                          'Format'   => $StringFormat,
                          'Trim'     => $Trim];
                          
        return $this;
    }
    
    
    
    
    /**
     * Check
     * 
     * Check the rules now!
     * 
     * @return bool   Return true if all passed or false when some of them not passed.
     */
     
    public function Check()
    {
        foreach($this->Rules as $Rule)
        {
            /** Expolde the rule*/
            $this->SetVariables($Rule);
            
            /** Trim the string */
            if($this->Trim)
                $this->Trim();
            
            /** Check required first */
            if($this->Required)
                /** Stop going to check this field if we got a false from ValidateRequired() */
                if(!$this->ValidateRequired()) 
                    continue;
            
            /** Check it with specif type */
            switch($Rule['Type'])
            {
                case 'Length' : $this->ValidateLength(); break;
                case 'Range'  : $this->ValidateRange();  break;         
                case 'Date'   : $this->ValidateDate();   break;
                case 'Switch' : $this->ValidateSwitch(); break;
                case 'Email'  : $this->ValidateEmail();  break;
                case 'Gender' : $this->ValidateGender(); break;
                case 'IP'     : $this->ValidateIP();     break;
                case 'URL'    : $this->ValidateURL();    break;
            }
            
            /** Ignore the string validate if we can't even passed the validation of above */
            if($this->Failed) 
                continue;
            
            /** Check the format */
            if($this->Format != NULL)
                if(!$this->ValidateFormat())
                    $this->Error('The format of the string is not valid.');
        }

        /** Log it down if there's any error occurred */
        if(!empty($this->Errors))
            $this->Log();
        
        return empty($this->Errors);
    }
    
    
    
    
    /**
     * Set Variables
     * 
     * Expolde the variables, so we don't need to pass it via parameter anymore.
     * 
     * @param  array $Rule   The array of the single rule.
     * @return Jajjimento
     */
     
    private function SetVariables($Rule)
    {
        $this->Rule     = $Rule;
        $this->Value    = ($this->Source != 'NO_SOURCE') ? $this->Get($Rule) : $Rule['Field'];
        $this->Field    = $Rule['Field'];
        $this->Type     = $Rule['Type'];
        $this->Min      = $Rule['Min'];
        $this->Max      = $Rule['Max'];
        $this->Required = $Rule['Required'];
        $this->Format   = $Rule['Format'];
        $this->Trim     = $Rule['Trim'];
        
        /** Set $Failed flag as false like nothing happened before, just like u and ur ex */
        $this->Failed   = false;
        
        return $this;
    }
    
    
    
    
    /**
     * Pre Check
     * 
     * Use the rules and check it which we written before.
     * 
     * @param  string $MainType   The type of the rule, ex: 'User'.
     * @param  string $SubType    The sub type like 'Login', 'Create'.
     * @return bool
     */ 
     
    public function PreCheck($MainType, $SubType)
    {
        /** Load the rules */
        require('jajjimento-rules/rules.php');
        
        /** Load the main type */
        $MainRule = $$MainType;

        /** Load each rules */
        foreach($MainRule[$SubType] as $Rule)
        {
            $Rule[5] = isset($Rule[5]) ? $Rule[5] : NULL;
            $Rule[6] = isset($Rule[6]) ? $Rule[6] : NULL;
            
            $this->Add($Rule[0], $Rule[1], $Rule[2], $Rule[3], $Rule[4], $Rule[5], $Rule[6]);
        }
        
        /** Check it and return the boolean */
        return $this->Check();
    }
     
     
     
     
    /**
     * Get
     * 
     * Get the value by the field name.
     * 
     * @param  string $FieldName   The name of the field.
     * @return mixed               The value, return null if there's no such field in the source.
     */
      
    private function Get($FieldName)
    {
        /** Get the field name if we got a rule array not string */
        $FieldName = is_array($FieldName) ? $FieldName['Field'] 
                                          : $FieldName;
         
        return isset($this->Source[$FieldName]) ? $this->Source[$FieldName] 
                                                : false;
    }
    
    
    
    
    /**
     * Clean
     * 
     * Clean the settings which we used before.
     * 
     * @return Jajjimento
     */
     
    private function Clean()
    {
        $this->Source = $this->Rules = $this->Errors = $this->Value    = $this->Field  = 
        $this->Min    = $this->Max   = $this->Type   = $this->Required = $this->Format = 
        $this->Trim   = $this->Rule  = NULL;
        
        return $this;
    }
    
    
     
     
    /***********************************************
    /***********************************************
    /************** V A L I D A T E ****************
    /***********************************************
    /***********************************************
     
    /**
     * Length
     * 
     * @return bool
     */
      
    private function ValidateLength()
    {
        $Length = mb_strlen($this->Value, CODE_CHARSET);
     
        if($Length > $this->Max)
        {
            $this->Error('The value is too long.');
            return false;
        }
        elseif($Length < $this->Min)
        {
            $this->Error('The value is too short.');
            return false;
        }
        
        return true;
    }
     
     
     
     
    /**
     * Range
     */
      
    private function ValidateRange()
    {
        if(!is_numeric($this->Value))
        {
            $this->Error('The value is not a number.');
            $this->Failed = true;
        }
        elseif($this->Value > $this->Max)
        {
            $this->Error('The value is too large.');
            $this->Failed = true;
        }
        elseif($this->Value < $this->Min)
        {
            $this->Error('The value is too small.');
            $this->Failed = true;
        }
    }
     
     
     
     
    /**
     * Date
     */
      
    private function ValidateDate()
    {
        switch($this->Min)
        {
            case 'YYYY-MM-DD':
                if(!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $this->Value))
                {
                    $this->Error('The value is not match with the YYYY-MM-DD format.');
                    $this->Failed = true;
                }
                break;
                
            case 'MM-DD-YYYY':
                if(!preg_match('/^(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-[0-9]{4}$/', $this->Value))
                {
                    $this->Error('The value is not match with the MM-DD-YYYY format.');
                    $this->Failed = true;
                }
                break;
        }
    }
    
    
    
    
    /**
     * Switch
     */
      
    private function ValidateSwitch()
    {
        if(!in_array($this->Value, $this->Min))
            $this->Error('The value is not on the allow list.');
            $this->Failed = true;
    }
     
     
     
     
    /**
     * Email
     */
    
    private function ValidateEmail()
    {
        if(!preg_match('/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/', $this->Value))
        {
            $this->Error('Not a valid email address.');
            $this->Failed = true;
        }
    }
     
     
     
     
    /**
     * Gender
     */
      
    private function ValidateGender()
    {
        $Gender = strtolower($this->Value);
        
        if($Gender != 'o' || $Gender != 'm' || $Gender != 'f')
        {
            $this->Error('Not a valid gender.');
            $this->Failed = true;
        }
    }
     
     
     
     
    /**
     * IP
     */
      
    private function ValidateIP()
    {
        if(!filter_var($this->Value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
        {
            $this->Error('Not a valid IPv4 or IPv6.');
            $this->Failed = true;
        }
    }
     
     
     
     
    /**
     * URL
     */
      
    private function ValidateURL()
    {
        if(!filter_var($this->Value, FILTER_VALIDATE_URL))
        {
            $this->Error('Not a valid URL.');
            $this->Failed = true;
        }
    }
    
    
    
    
    /**
     * Required
     * 
     * @return bool   Return false when required but empty, or true when passed.
     */
     
    private function ValidateRequired()
    {
        /** Call to error function if it's required but only space or zero length */
        if(!$this->Value || ctype_space($this->Value) || mb_strlen($this->Value, CODE_CHARSET) == 0)
        {
            $this->Error('Required but no value or only space or not even in the source.');
            return false;
        } 
        
        return true;
    }
    
    
    
    
    /**
     * Format
     * 
     * Check the format of the string.
     * 
     * @return bool    Return true if passed or false when not passed.
     */
    
    private function ValidateFormat()
    {
        switch($this->Format)
        {
            /** English only */
            case 'a-Z':
                return ctype_alpha($this->Value); 
                break;
            
            /** Uppercase English only */
            case 'A-Z':
                return ctype_upper($this->Value);
                break;
            
            /** Only numbers are allowed */
            case '0-9':
                return is_numeric($this->Value);
                break;
            
            /** Must be English or numbers */
            case 'a-Z0-9':
                return ctype_alnum($this->Value);
                break;
            
            /** English must be uppercase or numbers */
            case 'A-Z0-9':
                return preg_match('/^[A-Z0-9]+$/', $this->Value);
                break;
            
            /** Allows english and numbers and some other symbols */
            case 'a-Z0-9~':
                return preg_match('/^[a-zA-Z0-9\p{P}\p{S}]+$/', $this->Value);
                break;
            
            /** Like above but allows all the unicode language and some other symbols */
            case 'Lang~':
                return preg_match('/^[\p{L}\p{P}\p{S}]+$/', $this->Value);
                break;
            
            /** All languages without symbols */
            case 'Lang':
                return preg_match('/^[\p{L}]+$/', $this->Value);
                break;
            
            /** Others */
            default:
                return preg_match('/^[' . $this->StringFormat . ']+$/', $this->Value);
                break;
        }
    }
    
    
    
    /**
     * Trim
     * 
     * Remove the space in the end of the string.
     * 
     * @return Jajjimento
     */
     
    private function Trim()
    {
        preg_match('/^[^\w]{0,}(.*?)[^\w]{0,}$/iu', $this->Value, $Matches);
   
        if(count($Matches) < 2)
            return;
   
        $this->Value = $Matches[1];

        return $this;
    }
     
     
     
     
    /**
     * Error
     * 
     * Recored this rule to the error list.
     * 
     * @param  string $Reason   The reason why that one not passed.
     * @return Jajjimento
     */
     
    private function Error($Reason)
    {
        $this->Errors[] = ['Field'    => $this->Field,
                           'Value'    => $this->Value,
                           'Min'      => $this->Min,
                           'Max'      => $this->Max,
                           'Required' => $this->Required,
                           'Trim'     => $this->Trim,
                           'Format'   => $this->Format,
                           'Type'     => $this->Type,
                           'Reason'   => $Reason];
                           
        return $this;
    }
    
    
    
    
    /**
     * Log
     * 
     * Log the error down.
     * 
     * @return mixed Return Aira when it's existed or Jajjimento otherwise.
     */
     
    private function Log()
    {
        return ($this->HasAira) ? Aira::Add('INCORRECT_FORM') 
                                : $this;
    }
}
?>

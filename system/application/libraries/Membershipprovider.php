<?php
/*
 *  The Mana World Server
 *  Copyright 2004 The Mana World Development Team
 *
 *  This file is part of The Mana World.
 *
 *  The Mana World  is free software; you can redistribute  it and/or modify it
 *  under the terms of the GNU General  Public License as published by the Free
 *  Software Foundation; either version 2 of the License, or any later version.
 *
 *  The Mana  World is  distributed in  the hope  that it  will be  useful, but
 *  WITHOUT ANY WARRANTY; without even  the implied warranty of MERCHANTABILITY
 *  or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 *  You should  have received a  copy of the  GNU General Public  License along
 *  with The Mana  World; if not, write to the  Free Software Foundation, Inc.,
 *  59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 *  $Id$
 */

 
/**
 * The membershipprovider ist responsible for sessionhandling
 */ 
class Membershipprovider
{

    // Constants used as return value from validatePassword function. /////////
     
    /** 
     * The given password fulfills all requierments.
     */
    const PASSWORD_OK = -1;
    /** 
     * The given password is too short.
     */
    const PASSWORD_TO_SHORT = 1;
    /** 
     * The given password is to long.
     */
    const PASSWORD_TO_LONG = 2;
    /** 
     * The given password may not like your username.
     */
    const PASSWORD_SIMILAR_TO_USERNAME = 3;
    
    // end of constants ///////////////////////////////////////////////////////
    
    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;

    
    /**
     * This static function returns a random hash string in the given length.
     * 
     * @param int Length of the requested hash string
     * @returns string Random hash key
     */
    static function getRandomHashKey($length=24)
    {
        // define possible chars for the hash
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ" . 
                 "abcdefghijklmnopqrstuvwxyz" .
                 "0123456789";
        // split the charstring into array and shuffle it                 
        $chararray = str_split($chars);
        shuffle( $chararray );
        
        // build the key in a loop, even for long string that should not
        // be relevant for performance issues i hope
        $key = "";                 
        for ($i; $i < $length; $i++)
        {
            // add a random char from the array
            $key .= $chararray[mt_rand(0, sizeof($chararray)-1)];
        }
        return $key;
    }
    
    /**
     * This function validates a passwort against the ample discussed password
     * policy of The Mana World.
     *
     * @param String The password to be validated
     * @returns int Returns one of the PASSWORD_* constants.
     */                  
    static function validatePassword($pwd, $username)
    {
        // a password should be at least 7 chars long
        if (strlen($pwd) <= 7)
        {
            return Membershipprovider::PASSWORD_TO_SHORT;
        }
        if (strlen($pwd) > 30)
        {
            return Membershipprovider::PASSWORD_TO_LONG;
        }
        if ($pwd == $username)
        {
            return Membershipprovider::PASSWORD_SIMILAR_TO_USERNAME;
        }
        return Membershipprovider::PASSWORD_OK;
    }
    
    
    /** 
     * Initializes a new instance of MembershipProvider
     */    
    function __construct()
    {
        // get an instance of CI
        // we have to this, because we are not in an controller and therefore
        // we cannot access $this->config
        $this->CI =& get_instance();
    }
    
    
    /**
     * This function updates a user record in the tmw_accounts table and sets
     * a key for a given username. 
     */
    public function setKeyForUser($username, $key)
    {
        $db = $this->CI->db;
        
        // do the update in a single transaction, to not disturb tmwserv
        $db->trans_start();
        $db->where('username', $username);
        $db->update('tmw_accounts', array('authorization' => $key)); 
        $db->trans_complete();
    }
    
    
    /**
     * This function sets a new password for a given username. If the third
     * paramter is true (default), the activation keyy will be set to null.
     *
     * @param String Name of the user to set the password
     * @param String The new password.
     * @param boolean If true, set activation key to NULL, otherwise leave it
     *                untouched.
     */
    public function setPasswordForUser($username, $password, $reset_key=true)
    {
        // hash the password
        $pwd = hash('sha256', $username . $password);
        
        // do the update in a single transaction, to not disturb tmwserv
        $db = $this->CI->db;
        
        $db->trans_start();
            $db->where('username', $username);
            $values = array('password'=>$pwd);
        
            if ($reset_key)
            {
                $values['authorization'] = null;
            }
            $db->update('tmw_accounts', $values); 
            
            log_message('info', sprintf('User [%s] has changed its password.',
                $username ));
        $db->trans_complete();
    }
    
    
    /**
     * Checks, wheter a user with the given key exists or not.
     *
     * @param   String  Username who wants to change its password
     * @param   String  Authorization key sent to the user via mail
     * @returns boolean Returns true, if the given user exists and has set this
     *                  key as his authorization key, otherwise false
     */
    public function validateKeyForUser($username, $key)
    {
        $query = $this->CI->db->get_where( 'tmw_accounts', 
                    array('username'=>$username, 'authorization'=>$key ));
            
        if ($query->num_rows == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
} // class Membershipprovider

?>
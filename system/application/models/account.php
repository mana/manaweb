<?php
/*
 *  The Mana World Account Manager
 *  Copyright 2008 The Mana World Development Team
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
 */

// load dependecies
require_once(APPPATH.'models/character'.EXT);

/**
 * The caharcter model deals with all data according to a character.
 * This class is not used as a model in terms of CodeIgniter. It is
 * used as a simple data object.
 *
 * @ingroup models
 */ 
class Account {

    const ACCOUNT_TBL      = 'tmw_accounts';        /**< Name of the accounts table */
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;
    
    /**
     * holds a reference to the database record.
     */
    private $account;

    ///////////////////////////////////////////////////////////////////////////

    /**
     * Returns an account with the given id.
     *
     * @param id (int) Unique ID of the account.
     * @return (Account) Account object
     */
    public static function getAccount($id)
    {
        $ci =& get_instance();
        $query = $ci->db->get_where(Account::ACCOUNT_TBL,
            array('id' => $id));

        if ($query->num_rows() > 0)
        {
            return new Account($query->row());
        }
        else
        {
            show_error( 'Account::getAccount => a account with this id ' .
                        'doesn`t exist!' );
            return null;
        }
    }

    ///////////////////////////////////////////////////////////////////////////

    
    /**
     * Constructor initializes a new instance of the Account model.
     * The constructor needs a database record as parameter.
     *
     * @param record (Array) Database record to initialite values of the 
     *                       Account.
     */
    public function __construct($record)
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config directly
        $this->CI =& get_instance();
        $this->account = $record;
    }
    
    /**
     * This function returns the unique id of the account.
     * 
     * @return (int) Unique id of the account.
     */
    public function getID()
    {
        return $this->account->id;
    }
        
    /**
     * This function returns the username of the account.
     * 
     * @return (String) Userame of the Account.
     */
    public function getUsername()
    {
        return $this->account->username;
    }
    
    /**
     * This function returns the level of the character.
     * 
     * @return (int) Level of the character
     */
    public function getLevel()
    {
        return $this->account->level;
    }

    /**
     * This functions returns the timestamp until when the account is banned.
     * 
     * @return (int) Unix-timestamp until when the account is banned.
     */
    public function getBanned()
    {
        return $this->account->banned;
    }

    /**
     * This function returns the date and time of the account registration.
     *
     * @return (int) Unix-timestamp of the registration.
     */
    public function getRegistration()
    {
        return intval($this->account->registration);
    }

    /**
     *This function returns the date and time of the last login with the client.
     *
     * @return (int) Unix-timestamp of the last login.
     */
    public function getLastLogin()
    {
        return intval($this->account->lastlogin);
    }

    /**
     * This function checks wheter the user level is high enough to use
     * administrative functions and to see the admin interface link.
     * The permission can be configured in tmw_config.php with parameter
     * tmwweb_admin_level.
     *
     * @return (bool) \c true if the user is allowed to see the admin
     *                interface, otherwise \c false.
     */
    public function isAdmin()
    {
        return $this->getLevel() & AL_ADMIN;
    }

    /**
     * This function tells wheter an account is currently banned or not.
     * If the account is banned, the function will return the unixtimestamp
     * by when the account is banned. If not, it will return false.
     *
     * @return (Mixed) Unixtimestamp or \c false
     */
    public function isBanned()
    {
        if ($this->getBanned() > time() ||
            $this->getLevel() == AL_BANNED)
        {
            return $this->getBanned();
        }
        else
        {
            return false;
        }
    }

    /**
     * This functions is used to check wheter a user has at least one
     * character or not.
     *
     * @todo We shouldn't select all data here, just do a count(*)
     * @return (bool) \c true, if the user has at least one charater,
     *               otherwise \c false
     */
    public function hasCharacters()
    {
        $query = $this->CI->db->get_where(Character::CHARACTER_TBL,
            array('user_id' => $this->getID()), 1);

        if ($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        };
    }

    /**
     * This function returns the number of characters a player has.
     *
     * @todo We shouldn't select all data here, just do a count(*)
     * @return (int) The amount of characters a player has.
     */
    public function getCharacterCount()
    {
        $query = $this->CI->db->get_where(Character::CHARACTER_TBL,
            array('user_id' => $this->getID()) );

        return $query->num_rows();
    }

    /**
     * This functions returns an array with all character models
     * owned by the account.
     *
     * @param   order (String) SQL Order by clause
     * @return  (Array) Returns an array of character models
     */
    public function getCharacters($order="level desc")
    {
        $chars = array();

        $this->CI->db->order_by($order);
        $query = $this->CI->db->get_where(Character::CHARACTER_TBL,
            array('user_id' => $this->getID()));

        foreach ($query->result() as $char)
        {
            $chars[$char->id] = new Character($char);
        }

        return $chars;
    }
}

?>

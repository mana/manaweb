<?php
/*
 *  The Mana World Server
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
 *
 *  $Id: $
 */
require_once('system/application/models/character.php');


/**
 * The user model deals with all data according to a account.
 */ 
class User extends Model {

    
    /**
     * The user model holds an instance to the curently logged in user.
     */
    private $current_user;
    
    /**
     * Boolean variable that indicates wheter the user has authenticated
     * or not.
     */
    private $is_authenticated;
    
        
    
    /**
     * Constructor initializes a new instalnce of the User model.
     * The model tries to get the current logged in user from the membership
     * provider as a shortcut.
     */
    public function __construct()
    {
        parent::Model();
        
        // set defaults
        $this->is_authenticated = false;
        $this->current_user = null;
        
        $this->isAuthenticated();
    }
    
    
    /**
     * This method checks if the user has just authenticated or if a session
     * cookie validates its membership. 
     *
     * @returns Boolean true, if the user is authenticated or logged in, 
     *                  otherwise false.
     */
    public function isAuthenticated()
    {
        if ($this->is_authenticated)
        {
            return true;
        }
        else
        {
            if ($this->session->userdata('logged_in'))
            {
                $user_id = $this->session->userdata('user_id');
                $query = $this->db->get_where( 'tmw_accounts', 
                    array( 'id' => $user_id ));
            
                if ($query->num_rows == 1)
                {
                    // authentication succeeded
                    $this->is_authenticated = true;
                    $this->current_user = $query->row();                                        
                    return true;
                }                
            }
            else
            {
                return false;
            } // !$this->CI->session->userdata('logged_in')
            
        } // !$this->is_authenticated        
    }
    
    
    /**
     * This function tries to authenticate a user by its username and
     * password. 
     * 
     * @param String Username to authenticate
     * @param String Password of the user
     * @returns Object false, if the authentications fails, the user_id of
     *                 the authenticated user if authentication succeeded
     */
    public function authenticate( $username, $password )
    {
        // hash the password
        $pwd = hash('sha256', $username . $password);
        
        // select user from db with given name and password
        $query = $this->db->get_where( 'tmw_accounts', 
            array( 'username' => $username, 'password' => $pwd ));

        if ($query->num_rows == 1)
        {
            // authentication succeeded
            $this->is_authenticated = true;
            $this->current_user = $query->row();
            
            // store cookie
            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('user_id', $this->current_user->id);
            
            return $this->current_user;
        }
        else
        {
            // authentication failed
            $this->is_authenticated = false;
            $this->current_user = null;
            
            return false;
        }
    } // function authenticate
    
    
    
    /**
     * This function is used to delete the current logged in user and logs him
     * out automatically.
     */
    public function deleteCurrentUser()
    {
        $this->deleteUser($this->current_user->id); 
        $this->logout();
    }
    
    
    /**
     * This function is used to delete all data of a user with the given 
     * userid.
     */
    public function deleteUser($userid)
    {
        // begin transaction
        $this->db->trans_start();
        
        // delete data from all tables containing user data
        
        // first delete records from child tables via subselects
        
        // delete guild memberships
        $this->db->query( 'delete from tmw_guild_members ' .
            'where member_name in ( ' .
            '   select name from tmw_characters where user_id = ' . $userid 
            . ' )' );
            
        // delete inventory of characters
        $this->db->query( 'delete from tmw_inventories ' .
            'where owner_id in ( ' .
            '   select id from tmw_characters where user_id = ' . $userid 
            . ' )' );
            
        // delete characters
        $this->db->delete('tmw_characters', array('user_id' => $userid));     
        
        // lastly delete account
        $this->db->delete('tmw_accounts', array('id' => $userid)); 
        
        // commit
        $this->db->trans_complete();
    }
    
    
    
    /**
     * This function destroys the current session of the user and sets all 
     * instance variables back to null or empty.
     */
    public function logout()
    {
        $this->is_authenticated = false;
        $this->current_user = null;
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_id');
        $this->session->sess_destroy();
    }    
    
        
    /**
     * Gets the authenticated user.
     *
     * @return Objct Returns the current authenticated user. If the user is 
     *               not authenticated, the function returns null.
     */
    public function getUser()
    {      
        return $this->current_user;
    }
    
    
    /**
     * This function takes a level as int value and translates it into a human
     * readable string. The translation is defined in the tmw_config file.
     * If parameter $level is null, the function takes the level from the 
     * currently logged in user. If this is also null, maybe there is no one
     * logged id, value 0 is assumed.
     * 
     * @param  int    Level to identify the corresponding name for
     * @return string Human readable translation of the level
     */
    public function getUserLevelString($level=null)
    {
        // get level of the current user if no level given
        if ($level == null)
        {
            // is a user logged in
            if ($this->isAuthenticated())
            {
                $level = $this->current_user->level;
            }
            else
            {
                $level = 0;
            }            
        }
        
        // load configured level strings from config file
        $levels = $this->config->config['tmw_account_levels'];
        $levelstring = "n/a";
        
        // loop through levels
        foreach ($levels as $lvl)
        {
            if ($lvl['min'] <= $level)
            {
               $levelstring = $lvl['name'];
            }
            else
            {
                // we assume the list of levels is orderd. so after the first
                // level that doesn`t match we can stop
                return $levelstring;
            }
        }
        return $levelstring;
    }
    
    
    /**
     * This function gathers all relevant data that should be shown on
     * the user homepage "my Account". Because this view is shown from more
     * than one controller (myaccount, and accountmanager) the functionality
     * for gathering relevant data is located here in the model and not in
     * each of the controllers.
     *
     * @return Array Array with all parameters needed by the view 
     *               tmwweb/user_home.
     */
    public function getHomepageData()
    {
        $params = array('user' => $this->current_user,
            'levelstring' => $this->getUserLevelString(),
            'charachters' => $this->getCharacters());        
        return $params;
    }
    
    
    /**
     * This functions is used to check wheter a user has at least one
     * character or not. 
     *
     * @returns bool true, if the user has at least one charater, 
     *               otherwise false
     */
    public function hasCharacters()
    {
        $query = $this->db->get_where('tmw_characters', 
            array('user_id' => $this->getUser()->id), 1);
            
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
     * This function checks if the current user is owner of the given 
     * character id or not.
     * 
     * @param int ID of the character
     * @return boolean true, if the user is the owner of the character, 
     *                 false otherwise
     */
    public function hasCharacter($id)
    {
        $query = $this->db->get_where('tmw_characters', 
            array(
                'id'      => $id,
                'user_id' => $this->getUser()->id
            ), 1);
            
        if ($query->num_rows() == 1)
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
     * @returns int The number of characters a player has.
     */
    public function getCharacterCount()
    {
        $query = $this->db->get_where('tmw_characters', 
            array('user_id' => $this->getUser()->id) );
            
        return $query->num_rows();
    }
    
    /**
     * This functions returns an array with all character models 
     * owned by the current user.
     * 
     * @param   String SQL Order clause
     * @returns Array  Array of Character models
     */
    public function getCharacters($order="level desc")
    {
        $chars = array();
        
        $this->db->order_by($order);
        $query = $this->db->get_where('tmw_characters', 
            array('user_id' => $this->getUser()->id));
            
        foreach ($query->result() as $char)
        {
            $chars[$char->id] = new Character($char);
        }           
            
        return $chars;
    }
    
    
    /**
     * This function returns a character object with the given id.
     *
     * @param int Id of the character
     * @return Objct Character object
     */
    public function getCharacter($id)
    {
        $query = $this->db->get_where('tmw_characters', 
            array('id' => $id));
            
        if ($query->num_rows() > 0)
        {            
            return new Character($query->row());
        }
        else
        {
            show_error( 'User::getCharacter => a character with this id ' .
                        'doesn`t exist!' );
        }
    }
    
    
    
} // class User
?>

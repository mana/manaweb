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
 *  $Id: $
 */


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
     * @returns Objct Returns the current authenticated user. If the user is 
     *                not authenticated, the function returns null.
     */
    public function getUser()
    {      
        return $this->current_user;
    }
    
    
    
    
    /**
     * This function gathers all relevant data that should be shown on
     * the user homepage "my Account". Because this view is shown from more
     * than one controller (myaccount, and accountmanager) the functionality
     * for gathering relevant data is located here in the model and not in
     * each of the controllers.
     *
     * @returns Array Array with all parameters needed by the view 
     *                tmwweb/user_home.
     */
    public function getHomepageData()
    {
        $params = array('user' => $this->current_user);        
        return $params;
    }
    
    
    
} // class User
?>
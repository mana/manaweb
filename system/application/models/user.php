<?php
/*
 *  The Mana Server Account Manager
 *  Copyright 2009 The Mana Project Development Team
 *
 *  This file is part of The Mana Server.
 *
 *  The Mana Server  is free software; you can redistribute  it and/or modify it
 *  under the terms of the GNU General  Public License as published by the Free
 *  Software Foundation; either version 2 of the License, or any later version.
 *
 *  The Mana Server is  distributed in  the hope  that it  will be  useful, but
 *  WITHOUT ANY WARRANTY; without even  the implied warranty of MERCHANTABILITY
 *  or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 *  You should  have received a  copy of the  GNU General Public  License along
 *  with The Mana Server; if not, write to the  Free Software Foundation, Inc.,
 *  59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 */

// load dependecies
require_once(APPPATH.'models/account'.EXT);


/**
 * The user model deals with all data according to a account.
 *
 * @ingroup models
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
     * Boolean variable that indicates wheter the user has administrative
     * rights according to his level or not.
     */
    private $is_admin;


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
        $this->is_admin = false;
        $this->current_user = null;

        $this->isAuthenticated();
    }


    /**
     * This method checks if the user has just authenticated or if a session
     * cookie validates its membership.
     *
     * @return  (bool) \c true, if the user is authenticated or logged in,
     *                  otherwise \c false.
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
                $query = $this->db->get_where( Account::ACCOUNT_TBL,
                    array('id' => $user_id));

                if ($query->num_rows == 1)
                {
                    // authentication succeeded
                    $this->is_authenticated = true;
                    $this->current_user = new Account($query->row());
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
     * This function checks wheter the user level is high enough to use
     * administrative functions and to see the admin interface link.
     * The permission can be configured in mana_config.php with parameter
     * manaweb_admin_level.
     *
     * @return (bool) \c true if the user is allowed to see the admin
     *                interface, otherwise \c false.
     */
    public function isAdmin()
    {
        if ($this->is_admin)
        {
            return true;
        }
        else
        {
            // check if the userlevel has a sufficient value
            if ($this->current_user->isAdmin())
            {
                $this->is_admin = true;
                return true;
            }
            else
            {
                return false;
            }
        }
    }


    /**
     * This function tells wheter a user is currently banned or not.
     * If the account is banned, the function will return the unixtimestamp
     * by when the account is banned. If not, it will return false.
     *
     * @return (Mixed) Unixtimestamp or \c false
     */
    public function isBanned()
    {
        $this->current_user->isBanned();
    }


    /**
     * This function checks wheter a user is logged in, is admin and has a
     * sufficient level to do a special action. The action is given as
     * parameter right. See mana_config.php for details of the available rights.
     *
     * @param  right (String) Right to check against users permission
     * @return (bool) \c true if the user has the right, otherwise \c false.
     */
    public function hasRight($right)
    {
        // the user has to be authenticated
        if (!$this->isAuthenticated())
        {
            return false;
        }
        // the user needs administrative rights
        if (!$this->isAdmin())
        {
            return false;
        }
        // load the configured rights
        $rights = $this->config->item('manaweb_admin_permissions');


        if (!isset($rights[$right]))
        {
            show_error("The requested right '".$right."' doesn't exist.");
            return;
        }

        // compare levels with explicit type conversion!
        if ($this->current_user->getLevel() & intval($rights[$right]['group']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * This function tries to authenticate a user by its username and
     * password.
     *
     * @param username   (String) Username to authenticate
     * @param password   (String) Password of the user
     * @param setsession (bool)   if \c true, the session is modified after
     *                   successful login to store user credentials.
                         If \c false, only username and password are validated
     * @return (Object) \c false, if the authentications fails, the user_object
     *                  of the authenticated user if authentication succeeded
     *                  If parameter setsession has been set to \c false, the
     *                  function only returns \c true or \c false.
     */
    public function authenticate($username, $password, $setsession=true)
    {
        // Hash the password. Normally the client performs the first hash and
        // the server the second.
        $pwd = hash('sha256', hash('sha256', $username . $password));

        // select user from db with given name and password
        $query = $this->db->get_where( Account::ACCOUNT_TBL,
            array( 'username' => $username, 'password' => $pwd ));

        if ($query->num_rows == 1)
        {
            // authentication succeeded
            if ($setsession)
            {
                // modify session
                $this->is_authenticated = true;
                $this->current_user = new Account($query->row());

                // store cookie
                $this->session->set_userdata('logged_in', true);
                $this->session->set_userdata('user_id',
                    $this->current_user->getID());

                return $this->current_user;
            }
            else
            {
                return true;
            }
        }
        else
        {
            if ($setsession)
            {
                // authentication failed
                $this->is_authenticated = false;
                $this->is_admin = false;
                $this->current_user = null;
            }

            return false;
        }
    } // function authenticate



    /**
     * This function is used to delete the current logged in user and logs him
     * out automatically.
     */
    public function deleteCurrentUser()
    {
        $this->deleteUser($this->current_user->getID());
        $this->logout();
    }


    /**
     * This function is used to delete all data of a user with the given
     * userid.
     *
     * @bug: delete bids on deleted auctions.
     * @param userid (int) Id of the user to delete
     */
    public function deleteUser($userid)
    {
        // begin transaction
        $this->db->trans_start();

        // delete data from all tables containing user data

        // first delete records from child tables via subselects

        // TODO: remove hardcoded table names and use constants instead...

        // delete quest states of characters
        $this->db->query( 'delete from mana_quests ' .
            'where owner_id in ( ' .
            '   select id from ' . Character::CHARACTER_TBL . ' where user_id = ' . $userid
            . ' )' );

        // delete guild memberships
        $this->db->query( 'delete from mana_guild_members ' .
            'where member_name in ( ' .
            '   select name from ' . Character::CHARACTER_TBL . ' where user_id = ' . $userid
            . ' )' );

        // delete inventory of characters
        $this->db->query( 'delete from mana_inventories ' .
            'where owner_id in ( ' .
            '   select id from ' . Character::CHARACTER_TBL . ' where user_id = ' . $userid
            . ' )' );

        // delete inventory of characters
        $this->db->query( 'delete from mana_char_skills ' .
            'where char_id in ( ' .
            '   select id from ' . Character::CHARACTER_TBL . ' where user_id = ' . $userid
            . ' )' );

        // delete auctions started by the player
        $this->db->query( 'delete from mana_auctions ' .
            'where char_id in ( ' .
            '   select id from ' . Character::CHARACTER_TBL . ' where user_id = ' . $userid
            . ' )' );
        // bug: delete bids on deleted auctions

        // delete characters
        $this->db->delete(Character::CHARACTER_TBL,  array('user_id' => $userid));

        // lastly delete account
        $this->db->delete(Account::ACCOUNT_TBL, array('id' => $userid));

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
        $this->is_admin = false;
        $this->current_user = null;
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_id');
        $this->session->sess_destroy();
    }


    /**
     * Gets the authenticated user.
     *
     * @return (Account) Returns the current authenticated user. If the user is
     *         not authenticated, the function returns \c null.
     */
    public function getUser()
    {
        return $this->current_user;
    }


    /**
     * This function takes a level as int value and translates it into a human
     * readable string. The translation is defined in the mana_config file.
     * If parameter $level is \c null, the function takes the level from the
     * currently logged in user. If this is also \c null, maybe there is no one
     * logged id, value 0 is assumed.
     *
     * @param  level (int) Level to identify the corresponding name for
     * @return (array) List of all group the level is a member of
     */
    public function getUserLevelString($level=null)
    {
        $groups = array();
        // get level of the current user if no level given
        if ($level == null)
        {
            // is a user logged in
            if ($this->isAuthenticated())
            {
                $level = $this->current_user->getLevel();
            }
            else
            {
                $level = AL_BANNED;
            }
        }

        // load configured level strings from config file
        $levels = $this->config->item('mana_account_levels');
        $levelstring = "n/a";

        // loop through levels
        foreach ($levels as $lvl)
        {
            if ($lvl['byte'] & $level)
            {
               $groups[] = $lvl['name'];
            }
        }
        return $groups;
    }


    /**
     * This function gathers all relevant data that should be shown on
     * the user homepage "my Account". Because this view is shown from more
     * than one controller (myaccount, and accountmanager) the functionality
     * for gathering relevant data is located here in the model and not in
     * each of the controllers.
     *
     * @return (Array) Returns an array with all parameters needed by the view
     *               <tt>manaweb/user_home</tt>.
     */
    public function getHomepageData()
    {
        $params = array('user' => $this->current_user,
            'groups'      => $this->getUserLevelString(),
            'charachters' => $this->getCharacters());
        return $params;
    }


    /**
     * This functions is used to check wheter a user has at least one
     * character or not.
     *
     * @return (bool) \c true, if the user has at least one charater,
     *               otherwise \c false
     */
    public function hasCharacters()
    {
        return $this->current_user->hasCharacters();
    }


    /**
     * This function checks if the current user is owner of the given
     * character id or not.
     *
     * @param id (int) ID of the character
     * @return (bool) \c true, if the user is the owner of the character,
     *                 otherwise \c false
     */
    public function hasCharacter($id)
    {
        $query = $this->db->get_where(Character::CHARACTER_TBL,
            array(
                'id'      => $id,
                'user_id' => $this->getUser()->getID()
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
     * @return (int) The amount of characters a player has.
     */
    public function getCharacterCount()
    {
        return $this->current_user->getCharacterCount();
    }

    /**
     * This functions returns an array with all character models
     * owned by the current user.
     *
     * @param   order (String) SQL Order by clause
     * @return  (Array) Returns an array of character models
     */
    public function getCharacters($order="level desc")
    {
        return $this->current_user->getCharacters($order);
    }

    /**
     * This function returns a character object with the given id.
     *
     * @param id (int) Id of the character
     * @return (Object) Character object
     */
    public function getCharacter($id)
    {
        $query = $this->db->get_where(Character::CHARACTER_TBL,
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

    /**
     * This function returns the registration date of the account as
     * unixtimestamp.
     *
     * @return (int) Unixtimestamp of the account registration
     */
    public function getRegistrationDate()
    {
        return $this->getUser()->getRegistration();
    }


    /**
     * This function returns the date of the last login into this account using
     * the Mana client.
     *
     * @return (int) Unixtimestamp of the last login.
     */
    public function getLastLogin()
    {
        return $this->getUser()->getLastLogin();
    }

    /**
     * This function returns true, if the user has at least one character that
     * is a member of the given guild.
     *
     * @param id (int) Id of the guild.
     * @return (bool) True, if the player is allowd to see the guild.
     */
    public function isMemberOfGuild($id)
    {
        $this->db->select('g.guild_id');
        $this->db->from(Character::CHARACTER_TBL);
        $this->db->join(Guild::GUILD_MEMBER_TBL.' g', 'id = g.member_id');
        $this->db->where(
            array('user_id' => $this->getUser()->getID(),
                'g.guild_id' => $id
            ));
        $result = $this->db->get();
        return ($result->num_rows() > 0);
    }


} // class User
?>

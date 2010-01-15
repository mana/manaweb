<?php
/*
 *  The Mana Server Account Manager
 *  Copyright 2008 The Mana Server Development Team
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
 */

 
/**
 * The menuprovider is responsible for generating the structure of the 
 * navigation menu. This includes static links, that are always visible and
 * links based on the authentication state of the current user.
 *
 * The menuprovider uses a custom configuration script located under 
 * <tt>application/config</tt>. See this file for further options.
 *
 * @see Menuprovider::CONFIG_FILE_NAME
 *
 * @ingroup libraries
 */ 
class Menuprovider
{
    /**
     * Defines the filename of the configuration file where the structure of 
     * the custom menu is defined.
     */
    const CONFIG_FILE_NAME = 'menu';
    
    
    /**
     * Array of static links to show in the outer navigation bar
     */
    private $static_links;
    
    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;
    
    /**
     * Reference to the currently observed character if we should display
     * a character menu.
     */
    private $char;
    
    
    /**
     * Initialize a new instance of the Menuprovider.
     */
    function __construct()
    {
        // get an instance of CI
        // we have to this, because we are not in an controller and therefore
        // we cannot access $this->config
        $this->CI =& get_instance();
        
        // load custom configuration script with static links for the 
        // navigation menu
        $this->CI->config->load( Menuprovider::CONFIG_FILE_NAME );    
        $this->static_links = $this->CI->config->config['mana_static_links'];
        
        $this->char = null;
    }
    
    /**
     * Returns an array with all configured static links
     * @return (Array) Returns an array with static links.
     */
    function getStaticMenu()
    {
        return $this->static_links;
    }
    
    
    /**
     * This function returns the structure of the user menu if the user has
     * authenticated.
     *
     * @return (Array) Returns an array with the menu structure, if the user
     *                 is authenticated, otherwise null;
     */
    function getUserMenu()
    {
        if ($this->CI->user->isAuthenticated())
        {
            return array( 
                array('url' => site_url('accountmanager/settings'), 
                    'name' => 'Settings'),
                array('url' => site_url('myaccount/logout'), 'name' => 'Logout')
            );
        }
        else
        {
            return null;
        }
    }
    
    
    /** 
     * This function is called by the \a Mana_Output library to find out if 
     * we should display the menu for characers or not.
     *
     * @return (Bool) \c true, if we look at character details, otherwise
     *                \c false.
     */
    public function getCharMenu()
    {
        if (isset($this->char))
        {
            return $this->char;
        }
        else
        {
            return false;
        }
    }
    
    
    /**
     * Sets the currently observed character and leads to displaying the 
     * character menu at the right side.
     *
     * @param char (Object) Reference to the character object.
     */
    public function setChar(& $char)
    {
        $this->char = $char;
    }
    
} // class Menuprovider

?>

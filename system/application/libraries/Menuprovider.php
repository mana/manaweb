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
 *  $Id$
 */

 
/**
 * The menuprovider is responsible for generating the structure of the 
 * navigation menu. This includes static links, that are always visible and
 * links based on the authentication state of the current user.
 *
 * The menuprovider uses a custom configuration script - see constant
 * Menuprovider::CONFIG_FILE_NAME for its current name - located under 
 * application/config. See this file for further options.
 */ 
class Menuprovider
{
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
        $this->static_links = $this->CI->config->config['tmw_static_links'];    
    }
    
    /**
     * Returns an array with all configured static links
     * @returns Array Array with static links.
     */
    function getStaticMenu()
    {
        return $this->static_links;
    }
    
    
    /**
     * This function returns the structure of the user menu if the user has
     * authenticated.
     *
     * @returns Array Returns an array with the menu structure, if the user
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
    
}


?>

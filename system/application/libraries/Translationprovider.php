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
 *
 *  $Id$
 */

 
/**
 * The translationprovider is responsible for managing languages and 
 * translations.
 * 
 * @author Andreas Habel <mail@exceptionfault.de>
 * @ingroup libraries
 */ 
class Translationprovider
{
    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;
    
    /**
     * Current language of the user
     */
    private $language;
    
    
    /**
     * Initialize a new instance of the Menuprovider.
     */
    function __construct()
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config directly
        $this->CI =& get_instance();
        $this->language = null;
    }
    
    
    /**
     * This functions returns a list with all available languages.
     * 
     * @return (Array) List of all available languages.
     */
    public function getLanguages()
    {
        return $this->CI->config->config['tmw_languages'];
    }
    
    
    
    /** 
     * This function returns the current language of the user. If we couldn't
     * find a cookie with that information, we are using the default language
     * defined in system/application/config/config.php
     *
     * @return (String) Directory name of the current language
     */
    public function getCurrentLanguage()
    {
        if ( isset($this->language) )
        {
            return $this->language;
        }
        
        if ($this->CI->session->userdata('language') === false)
        {
            $this->language = $this->CI->config->config['language'];
            return $this->CI->config->config['language'];
        }
        else
        {
            $this->language = $this->CI->session->userdata('language');
            return $this->CI->session->userdata('language');
        }
    }
    
    
    /** 
     * This function wraps the $this->lang->load functions and automatically
     * adds the needed language as second parameter based on the current
     * user settings.
     * 
     * @param filename (String) Filename of the language file.
     */
    public function loadLanguage($filename)
    {
        $this->CI->lang->load($filename, $this->getCurrentLanguage());
    }
    
    
    /** 
     * This function sets the language for the current user.
     * 
     * @param lang (String) Name of the language directory.
     */
    public function setLanguage($lang)
    {
        $this->CI->session->set_userdata('language', $lang);
    }
    
} // class Translationprovider
?>
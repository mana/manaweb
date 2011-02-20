<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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


/**
 * The MANA_Config library extends the config library shipped with codeigniter
 * to support user defined config files. The library now loads the requested
 * config file and looks if there is a custom config file to override the
 * default settings.
 * Custom files have to be in the same directory as the default and have to be
 * named like <tt>"configfile".user.php</tt>
 *
 * @ingroup libraries
 */
class MANA_Config extends CI_Config
{

    /**
     * Defines the extension to identify custom config files that override
     * default settings
     */
    const CUSTOM_FILE_EXT = ".user";

    /**
     * Load Config File
     *
     * @param   file (string)  the config file name
     * @param   use_sections (bool)
     * @param   fail_gracefully (bool)
     * @return  boolean if the file was loaded correctly
     */
    public function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        parent::load($file, $use_sections, $fail_gracefully);

        if (file_exists(APPPATH.'config/'. $file .
            MANA_Config::CUSTOM_FILE_EXT . EXT))
        {
            parent::load($file . MANA_Config::CUSTOM_FILE_EXT);
        }
    }


} // class MY_Config
?>
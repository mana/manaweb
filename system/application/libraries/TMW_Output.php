<?php
/**
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
 *
 *  @author Andreas Habel <mail@exceptionfault.de>
 *  @copyright Copyright 2008 The Mana World Development Team
 *
 *  @package tmwweb
 *  @subpackage libraries
 */

 
/**
 * The TMW_Config library extends the config library shipped with codeigniter
 * to support user defined config files. The library now loads the requested
 * config file and looks if there is a custom config file to override the
 * default settings. 
 * Custom files have to be in the same directory as the default and have to be
 * named like "<configfile>.user.php"
 */ 
class TMW_Output extends CI_Output
{
    
    /** 
     * Array used to store fixed values that needed by the layout views.
     * 
     * @var array
     */
    private $header_data = array();
    
    
    /**
     * Use this function to show a view with the given parameters.
     *
     * @param string Title to sho in the view
     * @param string Filename of the view to show
     * @param array  Array with values ans params to show
     */
    public function showPage($title, $filename, $params=array())
    {
        $CI =& get_instance();
        
        
        $this->header_data['static_menu'] = $CI->menuprovider->getStaticMenu();
        $this->header_data['user_menu']   = $CI->menuprovider->getUserMenu();
        $this->header_data['page_title']  = $title;
        
        
        $CI->load->view('layout/header', $this->header_data);
        $CI->load->view($filename, $params);
        $CI->load->view('layout/footer');
    }
    
} // class MY_Config
?>
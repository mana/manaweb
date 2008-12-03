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

 
/**
 * The TMW_Output extends the output library shipped with codeigniter.
 * 
 * @ingroup libraries
 */ 
class TMW_Output extends CI_Output
{
    
    /** 
     * Array used to store fixed values that needed by the layout views.
     */
    private $header_data = array();
    
    
    /**
     * Use this function to show a view with the given parameters.
     *
     * @param title    (String) Title to show in the view header
     * @param filename (String) Filename of the view to show
     * @param params   (Array)  Array with values ans params to show
     */
    public function showPage($title, $filename, $params=array())
    {
        $CI =& get_instance();
        
        $this->header_data['static_menu'] = $CI->menuprovider->getStaticMenu();
        $this->header_data['user_menu']   = $CI->menuprovider->getUserMenu();
        $this->header_data['page_title']  = $title;
        
        
        // shall we display a character menu in the navigation 
        $char = $CI->menuprovider->getCharMenu();
        if ($char)
        {
            $this->header_data['character_menu']   = $char;
        }
        
        $CI->load->view('layout/header', $this->header_data);
        $CI->load->view($filename, $params);
        $CI->load->view('layout/footer');
    }
    
} // class TMW_Output
?>
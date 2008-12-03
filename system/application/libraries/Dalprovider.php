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
require_once(APPPATH.'models/inventory'.EXT);
 

/**
 * The DalProvider library is used to store tmwweb specific data in its own 
 * database file, independent from tmwserv.
 *
 * @todo Make storage independent from sqlite and also support mysql  
 * @ingroup libraries
 */ 
class Dalprovider
{
    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;
    
    /** 
     * Configured path and name of the items.xml file.
     */
    private $items_file;
    
    /**
     * Configured path to the item images for import into tmwweb.
     */
    private $images_dir;
    
    
    /**
     * Initialize a new instance of the DalProvider.
     */
    public function __construct()
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config
        $this->CI =& get_instance();
        
        $this->items_file = $this->CI->config->item('tmwserv_items.xml');
        $this->images_dir = $this->CI->config->item('tmwserv_items_images');
    }
    

    
    /** 
     * This function loads all known items \c form the tmw_items table
     * and refreshs the locally stores item images
     */
    public function refreshStorage()
    {
        if (!file_exists($this->items_file))
        {
            show_error('The file ' . $this->items_file . ' was not found.');
        }

        // this is used as return message
        $retval = array();

        // this should no longer be necessary as tmwserv refreshs the
        // item storage on startup!

        $db =& $this->CI->db;
        $query = $db->get("tmw_items");
        
        foreach ($query->result() as $item)
        {
            
            // check if the corresponding image is available to tmwweb
            if (!file_exists("./images/items/" . $item->image))
            {
                // try to copy the image
                if (file_exists($this->images_dir . $item->image))
                {
                    $res = copy($this->images_dir . $item->image,
                        "images/items/" . $item->image);
                        
                    // copy failed
                    if (!$res)
                    {
                        $retval[] = $item->image;
                    }
                }
                else
                {
                    if (!in_array($item->image, $retval))
                    {
                        $retval[] = $item->image;
                    }
                }
            }
        }
        
        return $retval;
        
    } // public function refreshStorage()
    
} // class DalProvider

?>

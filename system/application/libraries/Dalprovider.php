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
 
// load dependecies 
require_once(APPPATH.'models/inventory'.EXT);
 

/**
 * The DalProvider library is used to store tmwweb specific data in its own 
 * database file, independent from tmwserv.
 *
 * @todo Make storage independent from sqlite and also support mysql  
 * @author Andreas Habel <mail@exceptionfault.de>
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
     * This function loads the \c items.xml file, parses it, and refreshs the
     * stored items in the tmw_items database table for faster access.
     */
    public function refreshStorage()
    {
        if (!file_exists($this->items_file))
        {
            show_error('The file ' . $this->items_file . ' was not found.');
        }
        
        $db =& $this->CI->db;
        $db->query("BEGIN TRANSACTION");
        $db->query("DELETE FROM ".Inventory::ITEMS_TBL);
        
        // this is used as return message
        $retval = array();
        
        $items = simplexml_load_file($this->items_file);
        foreach ($items->item as $item) {
            
            // filter item ids < 0
            if (!isset($item->attributes()->id) || $item->attributes()->id < 0)
            {
                continue;
            }
            
            // filter items that have no name and no image
            if (!isset($item->attributes()->name) || 
                !isset($item->attributes()->image))
            {
                continue;
            }
            
            // try to update existing 
            $data = array(
                'id'          => intval($item->attributes()->id),
                'name'        => strval($item->attributes()->name),
                'description' => strval($item->attributes()->description),
                'image'       => $this->parseImage($item->attributes()->image),
                'weight'      => intval($item->attributes()->weight),
                'itemtype'    => strval($item->attributes()->type),
                'effect'      => strval($item->attributes()->effect)
            );
            
            $db->insert(Inventory::ITEMS_TBL, $data);
            
            // check if the corresponding image is available to tmwweb
            if (!file_exists("./images/items/" . $data['image']))
            {
                // try to copy the image
                if (file_exists($this->images_dir . $data['image']))
                {
                    $res = copy($this->images_dir . $data['image'], 
                        "images/items/" . $data['image']);
                        
                    // copy failed
                    if (!$res)
                    {
                        $retval[] = $data['image'];
                    }
                }
                else
                {
                    if (!in_array($data['image'], $retval))
                    {
                        $retval[] = $data['image'];
                    }
                }
            }
        }
        
        // commit if successful
        $db->query("COMMIT");
        
        
        return $retval;
        
    } // public function refreshStorage()
    
    
    /** 
     * This function scans the string given in the image attribute of the 
     * items.xml file and tries to extract the image name.
     *
     * @param img (String) Value of image attribute from items.xml file
     * @return (String) Name of the image
     */
    private function parseImage($img)
    {
        // example string from items.xml
        // "armor-chest-tnecksweater.png|W:#665522,ccbb33,ffffaa"
        // we have to look after the pipe...
        
        // get position of | in the string
        $pos = strpos($img, "|");
        if ($pos === false)
        {
            return strval($img);
        }
        else
        {
            return strval(substr($img, 0, $pos));
        }
        
    }
    
} // class DalProvider

?>

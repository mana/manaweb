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
 * The mapprovider is responsible for all actions according to maps in the 
 * tmwserv module. It reads the maps.xml file, and delivers informations about
 * maps to the user.
 * 
 * @author Andreas Habel <mail@exceptionfault.de>
 * @copyright Copyright 2008 The Mana World Development Team
 *
 * @package tmwweb
 * @subpackage libraries
 */ 
class Mapprovider
{
    /**
     *  
     */
    const MAP_STORAGE = './data/maps.php.db';
    
    /**
     * Reference to the CodeIgniter framework
     * @var object
     */
    private $CI;
    
    /** 
     * Configured path and name of the maps.xml file.
     * @var string 
     */
    private $maps_file;
    
    /**
     * List of all available maps loaded from the maps.xml or the serialized
     * data object.
     * @var array array with map objects
     */
    private $maps;
    
    
    /**
     * Initialize a new instance of the Menuprovider.
     */
    function __construct()
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config
        $this->CI =& get_instance();
        
        // initialize variables
        $this->maps_file = "";
        $this->maps = array();
        
        
        // check if the serialized data object is present
        if (file_exists(Mapprovider::MAP_STORAGE))
        {
            $this->maps = unserialize(
                file_get_contents(Mapprovider::MAP_STORAGE)
            );
        }
        else
        {
            // try to load maps.xml file
            $this->load_maps_file();
        }
    } // __construct
    
    
    /**
     * This function returns all informations stored to the map with the given
     * id.
     * @param int ID of the map
     * @return object Map object.
     */
    public function getMap($id)
    {
        if (isset($this->maps[$id]))
        {
            return $this->maps[$id];
        }
        else
        {
            show_error('A map with the id ' . $id . ' is unknown. Maybe you '.
                'have to reload the maps.xml file');
        }
    }
    
    
    /**
     * This function tries to load and serialize the maps.xml file.
     */
    public function load_maps_file()
    {
        // load the configured path and filename from config file
        $this->maps_file = $this->CI->config->item('tmwserv_maps.xml');
        
        // check if the file really exists and is readable
        if (!file_exists($this->maps_file))
        {
            show_error('The maps.xml file ' . $this->maps_file . ' configured'.
                ' in tmw_config.php cannot be found');
            return;
        }        
        else
        {
            // load and parse the xml file
            $maps = simplexml_load_file($this->maps_file);
            foreach ($maps as $map)
            {
                // loop through defined maps and build internal array
                $m = array( 
                    'id'   => intval($map->attributes()->id),
                    'name' => strval($map->attributes()->name)
                );
                $this->maps[strval($map->attributes()->id)] = $m;
            }
            
            $this->flush_maps();
        }
    } // function load_maps_file
    
    
    /**
     * This function writes the memory structure stored in the private maps
     * variable to disk for faster access than reading maps.xml on each 
     * request.
     */
    private function flush_maps()
    {
        $fp = fopen(Mapprovider::MAP_STORAGE, "w");
        fwrite($fp, serialize($this->maps));
        fclose($fp);
    }
    
    
} // class Mapprovider


?>

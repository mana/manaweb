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
 *  @subpackage models
 */

/**
 * The Model repesents a map in The Mana World.
 */ 
class Map 
{
    /**
     * Unique Id of the map
     * @var int
     */
    private $id;
    
    /**
     * Filename of the map
     * @var string
     */
    private $name;
    
    /**
     * Human readable description of the map
     * @var string
     */
    private $description;
    
    
    /**
     * Creates a new map object. Id and name are required.
     * 
     * @param int    Unique Id of the map
     * @param string Filename of the map
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = null;
    }
    
    
    /**
     * Returns the unique Id of the map.
     * @return in Unique Id of the map.
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Returns the filename of the map.
     * @return string Filename of the map.
     */
    public function getName()
    {
        return $this->name;
    }
        
    
    /**
     * Returns the description of the map. As the description field is optional
     * this function returns the name, if no description is available.
     * @return string Description of the map.
     */
    public function getDescription()
    {
        if (isset($this->description))
        {
            return $this->description;
        }
        else
        {
            return $this->getName();
        }
    }    
    
    
    /**
     * Sets the description of the map.
     * @param string Description of the map.
     */
    public function setDescription($desc)
    {
        $this->description = $desc;
    }
    
    
} // class Map
?>

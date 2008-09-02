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
 * The inventory model deals with all items owned by a character.
 * This class is not used as a model in terms of CodeIgniter. It is
 * used as a simple data object.
 *
 * @author Andreas Habel <mail@exceptionfault.de>
 * @ingroup models
 */ 
class Inventory {

    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;

    
    
    /**
     * Constructor initializes a new instance of the Inventory model.
     * The constructor needs a database record as parameter.
     */
    public function __construct()
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config directly
        $this->CI =& get_instance();
    }
    
    
} // class Inventory
?>

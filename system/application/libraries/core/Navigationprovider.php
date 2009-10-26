<?php
/**
 *  The Mana Server Server
 *  Copyright 2008 The Mana Server Development Team
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

require_once(APPPATH.'models/core/Navigation'.EXT);

class Navigationprovider
{
    private $navboxes;

    public function Navigationprovider()
    {
        $this->navboxes = array();
    }

    public function addNavbox(NavigationBox $navbox, $orderId = PHP_INT_MAX)
    {
        $navbox->setOrderId( $orderId );
        $this->navboxes[$navbox->getId()] = $navbox;
    }
    
    public function hasNavbox( $id )
    {
        return array_key_exists($id, $this->navboxes);
    }

    public function getNavbox( $id )
    {
        if (!$this->hasNavbox($id))
        {
            throw new Exception(sprintf(_("ERROR: Navbox %s not found!"), $id));
        }
        return $this->navboxes[$id];
    }

    public function getNavboxes()
    {
        usort($this->navboxes, array('NavigationBox', 'order'));
        return $this->navboxes;
    }
}

?>
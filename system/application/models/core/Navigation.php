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
 */

class NavigationItem
{
    // unique id of the navigation item
    private $id;
    // text shown as navigation item
    private $text;
    // url used as linktarget
    private $url;
    // id used to sort the navigation items inside the boxes
    private $orderId;

    public function NavigationItem( $id, $text, $url )
    {
        $this->id = $id;
        $this->text = $text;
        $this->url = $url;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Used to order a list of NavigationItems
     */
    public static function order( NavigationItem $a, NavigationItem $b )
    {
        if ( $a->orderId == $b->orderId )
            return 0;

        return ( $a->orderId > $b->orderId ) ? 1 : -1;
    }
}


class NavigationBox
{
    // unique id of the menu
    private $id;
    // header text of the menu
    private $header;
    // id used to sort the navigation boxes
    private $orderId;
    // list of items inside the navigation box
    private $items;

    /** Creates a new navigation box
     *
     * @param (string) $id      Unique Id string of the navigation box
     * @param (string) $header  Hedertext shown in the navigation box
     */
    public function NavigationBox( $id, $header )
    {
        $this->id = $id;
        $this->header = $header;
        $this->orderId = PHP_INT_MAX;
        $this->items = array();
    }

    public function addNavigationItem(NavigationItem $item, $orderId = null)
    {
        if ($orderId == null)
            $item->setOrderId( count($this->items) );

        $this->items[$item->getId()] = $item;
    }

    public function setOrderId( $id )
    {
        $this->orderId = $id;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getItems()
    {
        usort( $this->items, array('NavigationItem', 'order'));
        return $this->items;
    }

    /**
     * Used to order a list of NavigationItems
     */
    public static function order( NavigationBox $a, NavigationBox $b )
    {
        if ( $a->orderId == $b->orderId )
            return 0;

        return ( $a->orderId > $b->orderId ) ? 1 : -1;
    }
}

?>
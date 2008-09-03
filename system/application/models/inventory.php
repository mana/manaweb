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
     * Name of the inventories table
     */
    const INVENTORY_TBL = 'tmw_inventories';
    
    /**
     * Name of the items table
     */
    const ITEMS_TBL = 'tmw_items';
    
    
    /**
     * Reference to the CodeIgniter framework.
     */
    private $CI;
    
    /**
     * If of the character that ownes the items.
     */
    private $char_id;
    
    /**
     * Array with all items the character has in its inventory
     */
    private $items;
    
    /**
     * Array with all items the character has equipped.
     */
    private $equipment;
    

    ///////////////////////////////////////////////////////////////////////////
    // Constants defining inventory slots
    
    /** Item equipped at the breast */
    const EQUIP_TORSO_SLOT = 0;
    /** Item equipped at the arms */
    const EQUIP_ARMS_SLOT = 1;
    /** Item equipped on the head */
    const EQUIP_HEAD_SLOT = 2;
    /** Item equipped at the legs */
    const EQUIP_LEGS_SLOT = 3;
    /** Item equipped on the feets */
    const EQUIP_FEET_SLOT = 4;
    /** Item equipped at the left hand */
    const EQUIP_RING1_SLOT = 5;
    /** Item equipped at the right hand */
    const EQUIP_RING2_SLOT = 6;
    /** Item equipped at the neck */
    const EQUIP_NECKLACE_SLOT = 7;
    /** Item equipped in the left hand */
    const EQUIP_FIGHT1_SLOT = 8;
    /** Item equipped in the right hand */
    const EQUIP_FIGHT2_SLOT = 9;
    /** Item equipped as projectile for bows e.g. arrows or bolts */
    const EQUIP_PROJECTILE_SLOT = 10;
    /** 
     * Defines the number of the last slot for equipped icons. Larger slots 
     * mean, that the item is in the inventory of the character.
     */        
    const EQUIP_LAST_SLOT = Inventory::EQUIP_PROJECTILE_SLOT;

    ///////////////////////////////////////////////////////////////////////////
     
    
    /**
     * Constructor initializes a new instance of the Inventory model.
     * 
     * @param char_id (int) Id of the character the inventory should be loaded.
     */
    public function __construct($char_id)
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config directly
        $this->CI =& get_instance();
        
        $this->char_id = $char_id;
        
        // set the internal vars to null, only load them if requested...
        $this->items = null;
        $this->equipment = null;
    }
    
    
    /**
     * This function returns a list of all items the character has in its 
     * inventory.
     *
     * @return (Array) Returns an array with all items in the user inventory.
     */
    public function getInventory()
    {
        if (!isset($this->items))
        {
            $this->loadInventory();
        }
        return $this->items;
    }
    

    /**
     * This function returns a list of all items the character currently has 
     * equipped.
     *
     * @return (Array) Returns an array with all equipped items of a character.
     */
    public function getEquipment()
    {
        if (!isset($this->equipment))
        {
            $this->loadInventory();
        }
        return $this->equipment;
    }
    
    
    /**
     * This function returns the weight of your equipped items, or items in 
     * your inventory, or both. 
     *
     * @param place (String) Define 'all, 'inventory' or 'equipment' to define
     *              what weight should be calculated.
     * @return (int) Weight of the items.
     */
    public function getWeight($place='all')
    {
        $weight = 0;
        
        if ($place == 'all' || $place == 'equipment')
        {
            foreach ($this->getEquipment() as $item)
            {
                $weight += intval($item->weight) * intval($item->amount);
            }
        }
        
        if ($place == 'all' || $place == 'inventory')
        {
            foreach ($this->getInventory() as $item)
            {
                $weight += intval($item->weight) * intval($item->amount);
            }
        }

        return $weight;        
    }
    
    
    /** 
     * This function loads the inventory of the user from the database and
     * initializes the local variables \a items and \a equipment.
     */
    private function loadInventory()
    {
        $db =& $this->CI->db;
        $this->items = array();
        $this->equipment = array();

        
        $sql = "SELECT items.*, "
             . "       inventory.amount as amount, "
             . "       inventory.slot as slot "
             . "  FROM ".Inventory::INVENTORY_TBL." inventory "
             . "  JOIN ".Inventory::ITEMS_TBL." items "
             . "    ON inventory.class_id = items.id "
             . " WHERE owner_id = " . $this->char_id 
             . " ORDER BY itemtype, name ";
             
        
        $query = $db->query($sql);
        foreach ($query->result() as $item)
        {
            // differentiate between items equipped and items in the inventory
            if ($item->id <= Inventory::EQUIP_LAST_SLOT)
            {
                $this->equipment[] = $item;
            }
            else
            {
                   $this->items[] = $item;
            }
        }
        
    } // private function loadInventory()
    
    
} // class Inventory
?>
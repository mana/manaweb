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
 * Controller for displaying all available items.
 * 
 * @ingroup controllers
 */ 
class Itemslist extends Controller 
{
	/** 
	 * Array used to store category statistics.
	 */
	private $cat_stats;
		
	
    /**
     * Initializes the Home controller.
     */
    function __construct()
    {
        parent::Controller();
        $this->output->enable_profiler(
            $this->config->item('tmw_enable_profiler')
        );
        
        $this->load->library("Imageprovider");
        
        $this->_initCategoryStats();
    }
    
    
    /** 
     * Default controller function. 
     */
    public function index()
    {
        $this->output->showPage( 'Item dictionary', 'tmwweb/item_list',
            array("ctrl" => $this));
    }
    
    /**
     * This function is called by the item_list view to load a list of all
     * items in the given category.
     * 
     * @param itemcategory Category of the item 
     */
    public function show($itemcategory)
    {
	    $this->db->order_by('name');
	    $query = $this->db->get_where('tmw_items',
	    	array('itemtype' => $itemcategory));
	    	
	    $items = array();
	    foreach ($query->result() as $row)
	    {
		    $items[] = $row;
	    }
	    	
        $this->output->showPage( 'Item dictionary', 'tmwweb/item_list',
            array(
            	'ctrl' => $this, 
            	'itemslist' => $items, 
            	'imageprovider' => $this->imageprovider
            ));
    }
    
    
    /** 
     * Gets the amount of items defined for a given category name.
     * 
     * The category names are defined as constants in the \a Inventory model
     * named like ITEM_TYPE_*
     *
     * @param category Category to get number of defined items.
     * @return Returns the amount of items defined for the given category.
     */
    public function getItemsPerCat($category)
    {
		if (!isset($this->cat_stats[$category]))
		{
			return intval(0);
		}
		else
		{
			return intval($this->cat_stats[$category]);
		}
    }
    
    
    /**
     * Initializes the statistics about items per category.
     */
    private function _initCategoryStats()
    {
	    $this->cat_stats = array();
	    
	    $sql = "SELECT itemtype, COUNT(*) AS amount \n".
	           "FROM   tmw_items \n".
	           "GROUP  BY itemtype";
	    
	    $query 	= $this->db->query($sql);
	    foreach ( $query->result() as $row )
	    {
		    $this->cat_stats[ $row->itemtype ] = $row->amount;
	    }
    }
    
} // class Itemlist
?>
<?php
/*
 *  The Mana Server Account Manager
 *  Copyright 2009 The Mana Project Development Team
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
            $this->config->item('mana_enable_profiler')
        );

        $this->load->library("Imageprovider");
        $this->load->helper('form');
        $this->load->library('validation');

        $this->_initCategoryStats();
    }


    /**
     * Default controller function.
     */
    public function index()
    {
        $this->output->showPage( 'Item dictionary', 'manaweb/item_list',
            array("ctrl" => $this));
    }


    /**
     * This function is called by the view item_list if a user wants to search
     * an item by its name.
     */
    public function search_item()
    {
        // the searchfield has to contain at least one character
        $rules['ManaSearchItem']  = "required|min_length[1]";
        $this->validation->set_rules($rules);
        if ($this->validation->run() == false)
        {
        $this->output->showPage( 'Item dictionary', 'manaweb/item_list',
            array("ctrl" => $this));
            return;
        }

        // even if active record has a method ->like we write the code on our
        // own due to a bug which does wrong quoting in the searchstring... :(
        $search = '%' . $this->input->post('ManaSearchItem') . '%';

        $this->db->where('name LIKE \'' . $search . '\'');
        $this->db->order_by('name');
        $res = $this->db->get('mana_items');

        if ($res->num_rows() > 0)
        {
            $param = array(
                'result_items'   => true,
                'itemslist'      => $res->result(),
                'searchstring'   => $this->input->post('ManaSearchItem'),
                'imageprovider'  => $this->imageprovider
            );
        }
        else
        {
            $param = array('result_items' => false);
        }

        $params = array_merge( array("ctrl" => $this), $param );
        $this->output->showPage( 'Item dictionary', 'manaweb/item_list', $params);
    }

    /**
     * This function is called by the view item_list if a user wants to search
     * an item by its name.
     */
    public function search_item_ajax()
    {
        // even if active record has a method ->like we write the code on our
        // own due to a bug which does wrong quoting in the searchstring... :(
        $search = '%' . $this->input->post('ManaSearchItem') . '%';

        $this->db->where('name LIKE \'' . $search . '\'');
        $this->db->order_by('name');
        $res = $this->db->get('mana_items');

        echo "<ul>";
        foreach ($res->result() as $item)
        {
            echo "<li>" . $item->name . "</li>";
        }
        echo "</ul>";
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
	    $query = $this->db->get_where('mana_items',
	    	array('itemtype' => $itemcategory));

	    $items = array();
	    foreach ($query->result() as $row)
	    {
		    $items[] = $row;
	    }

        $this->output->showPage( 'Item dictionary', 'manaweb/item_list',
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

        // TODO: replace hard coded table name with constant
	    $sql = "SELECT itemtype, COUNT(*) AS amount \n".
	           "FROM   mana_items \n".
	           "GROUP  BY itemtype";

	    $query 	= $this->db->query($sql);
	    foreach ( $query->result() as $row )
	    {
		    $this->cat_stats[ $row->itemtype ] = $row->amount;
	    }
    }

} // class Itemlist
?>
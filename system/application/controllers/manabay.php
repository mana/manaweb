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

require_once(APPPATH.'models/auction'.EXT);

/**
 * The accountmanager controller is responsible for all actions a user can do
 * with its account, beside the functions located in the myaccount controller.
 * Each functions in the accountmanager need an authorized user, so the
 * authentication is just done in the constructor, rather in every single
 * function.
 *
 * @ingroup controllers
 */
class Manabay extends Controller {

    /**
     * Initializes the Home controller.
     */
    function __construct()
    {
        parent::Controller();
        $this->output->enable_profiler(
            $this->config->item('mana_enable_profiler')
        );

        $this->load->library('Imageprovider');

        /*
        // check if the user is currently logged in
        if (!$this->user->isAuthenticated())
        {
            $param = array('has_errors' => false);
            $this->output->showPage(T_('account_login'),
                'manaweb/login_form', $param);
        }
        */
    }


    /**
     * Default controller function. Shows either the login screen or the
     * homepage of the user account, depending of the login status of the
     * current user.
     */
    public function index()
    {
	    $params = array(
	    	'next_auctions' => Auction::getFinishingAuctions(),
	    	'imageprovider' => $this->imageprovider
	    );

        $this->output->showPage('Welcome to the Manabay!',
        	'manabay/auction_list',
        	$params);
    }


    /**
     * This function is called by the view manabay/auction_list if the user
     * requests a list of open auctions for a specific item category.
     *
     * @param category Item category to show.
     */
    public function show_by_category($category)
    {
	    //$this->output->showPage('Welcome to the Manabay!', 'manabay/auction_list');
	    $this->index();
    }


    /**
     * This function is called by a view to show an auction with a specific id.
     * @param auction_id Unique id of an auction.
     */
    public function show_by_id($auction_id)
    {
	    //$this->output->showPage('Welcome to the Manabay!', 'manabay/auction_list');
	    $this->index();
    }


} // class Manabay
?>

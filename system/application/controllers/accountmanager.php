<?php
/*
 *  The Mana World Server
 *  Copyright 2004 The Mana World Development Team
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
 *  $Id: $
 */


/**
 * The accountmanager controller is responsible for all actions a user can do 
 * with its account, beside the functions located in the myaccount controller.
 * Each functions in the accountmanager need an authorized user, so the
 * authentication is just done in the constructor, rather in every single 
 * function.
 */ 
class Accountmanager extends Controller {

    /**
     * Array to store data needed by the header view.
     */
    private $header_data = Array();
    
    
    /**
     * Initializes the Home controller.
     */
    function __construct()
    {
        parent::Controller();
        $this->load->library('validation');
        $this->load->helper('form');
        $this->header_data['static_menu'] = 
            $this->menuprovider->getStaticMenu();        
            
        // check if the user is currently logged in
        if (!$this->membershipprovider->isAuthenticated())
        {
            $params = array( 'has_errors' => false ); 
            $this->showPage( 'Account Manager', 'tmwweb/login_form', $params);
        }
    }
    
    
    /** 
     * Default controller function. Shows either the login screen or the
     * homepage of the user account, depending of the login status of the 
     * current user.
     */
    function index()
    {
        $this->_show_user_account();
    }
    
    
    /**
     * This private function is used to show the account page of the user.
     */    
    private function _show_user_account()
    {
        $this->showPage( 'My Account', 'tmwweb/user_home', 
            $this->user->getHomepageData() );
    }
    

        
    /**
     * Use this function to show a view with the given parameters
     */
    private function showPage( $title, $filename, $params=array() )
    {
        $this->header_data['page_title'] = $title;
        $this->header_data['user_menu'] = $this->menuprovider->getUserMenu();
        $this->load->view('layout/header', $this->header_data);
        $this->load->view($filename, $params);
        $this->load->view('layout/footer');
    }
    
} // class Myaccount
?>
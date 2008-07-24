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
 * Main controller of the homepage.
 */ 
class Home extends Controller {

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
        $this->header_data['static_menu'] = $this->menuprovider->getStaticMenu();
        $this->header_data['user_menu'] = $this->menuprovider->getUserMenu();
    }
    
    /** 
     * Default controller function. Shows the news of the homepage.
     */
    function index()
    {
        $this->_show_static( 'News', 'static/news' );
    }
    
   
    /** 
     * Shows the static "About The Mana World" page.
     */
    function about()
    {
        $this->_show_static( 'About The Mana World', 'static/about' );
    }
    
    
    /** 
     * Shows the static download page.
     */
    function downloads()
    {
        $this->_show_static( 'Downloads', 'static/downloads' );
    }
    
    
    /** 
     * Shows the static links page.
     */
    function links()
    {
        $this->_show_static( 'Links', 'static/links' );
    }
    
    
        
    /** 
     * Shows a static page.
     *
     * A static view can`t contain any dynamic content.
     * 
     * @param String Title of the page used as header.
     * @param String Filename of the view.
     */
    private function _show_static( $title, $filename )
    {
        $this->header_data['page_title'] = $title;
        $this->header_data['user_menu']  = $this->menuprovider->getUserMenu();
        $this->load->view('layout/header', $this->header_data);
        $this->load->view($filename);
        $this->load->view('layout/footer');
    }
    
    
}
?>
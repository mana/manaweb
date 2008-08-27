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
 *  @subpackage controllers
 */


/**
 * The admin controller is responsible for all actions a admin or gm can do 
 * to administrate tmwweb.
 * Each functions in the admin controller need an authorized user with 
 * administrative rights, so the authentication is just done in the 
 * constructor, rather in every single function.
 * 
 * @author Andreas Habel <mail@exceptionfault.de>
 * @copyright Copyright 2008 The Mana World Development Team
 *
 * @package tmwweb
 * @subpackage controllers
 */ 
class Admin extends Controller {

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
        $this->output->enable_profiler(
            $this->config->item('tmw_enable_profiler')
        );
        
        $this->load->helper('form');
        $this->load->library('validation');
        $this->translationprovider->loadLanguage('admin');            

        $this->header_data['static_menu'] = 
            $this->menuprovider->getStaticMenu();
        
        // check if the user is currently logged in
        if (!$this->user->isAuthenticated() || !$this->user->isAdmin())
        {
            $param = array('has_errors' => false); 
            $this->load->library('validation');
            $this->translationprovider->loadLanguage('account');
            $this->showPage(lang('account_login'), 'tmwweb/login_form', $param);
        }
    }
    
    
    /** 
     * Default controller function. Shows the main page of the admin interface.
     */
    public function index()
    {
        if (!$this->user->isAuthenticated() || !$this->user->isAdmin())
        {
            return;
        }
        
        $this->showPage(lang('admin_title'), 'admin/main');
    }
    
    
    /**
     * This function is called by the view admin/main if the user searches for
     * a account.
     */
    public function search_account()
    {
        if (!$this->user->isAuthenticated() || !$this->user->isAdmin())
        {
            return;
        }

        // the searchfield has to contain at least one character        
        $rules['TMWusername']  = "required|min_length[1]";
        $this->validation->set_rules($rules);
        if ($this->validation->run() == false)
        {
            $this->showPage(lang('admin_title'), 'admin/main');
            return;
        }
        
        // search for the given account name
        // even if active record has a method ->like we write the code on our
        // own due to a bug which does wrong quoting in the searchstring... :(
        $search = '%' . $this->input->post('TMWusername') . '%';
        
        $this->db->where('username LIKE \'' . $search . '\'');
        $this->db->order_by('username');
        $res = $this->db->get(User::ACCOUNT_TBL);
        
        if ($res->num_rows() > 0)
        {
            $param = array('result_account' => $res->result());
        }
        else
        {
            $param = array('result_account' => false);
        }
        
        $this->showPage(lang('admin_title'), 'admin/main', $param);
    }
    
    
    /**
     * This function is called by the view admin/main if the user searches for
     * a character.
     */
    public function search_character()
    {
        if (!$this->user->isAuthenticated() || !$this->user->isAdmin())
        {
            return;
        }
        
        $param = array('result_character' => true);
        $this->showPage(lang('admin_title'), 'admin/main', $param);
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

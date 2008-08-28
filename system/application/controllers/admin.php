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

        
        // check if the user is currently logged in
        if (!$this->user->isAuthenticated() || !$this->user->isAdmin())
        {
            $param = array('has_errors' => false); 
            $this->translationprovider->loadLanguage('account');
            $this->output->showPage(lang('account_login'), 
                'tmwweb/login_form', $param);
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
        
        $this->output->showPage(lang('admin_title'), 'admin/main');
    }
    
    
    /**
     * This function is called by the view admin/main if the user searches for
     * a account.
     */
    public function search_account()
    {
        if (!$this->user->hasRight('see_account_list'))
        {
            $param = array('error' => 'You are not allowed to see account list');
            $this->output->showPage(lang('admin_title'), 'admin/main', $param);
            return;
        }

        // the searchfield has to contain at least one character        
        $rules['TMWusername']  = "required|min_length[1]";
        $this->validation->set_rules($rules);
        if ($this->validation->run() == false)
        {
            $this->output->showPage(lang('admin_title'), 'admin/main');
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
            $param = array(
                'result_account' => $res->result(),
                'searchstring'   => $this->input->post('TMWusername')
            );
        }
        else
        {
            $param = array('result_account' => false);
        }
        
        $this->output->showPage(lang('admin_title'), 'admin/main', $param);
    }
    
    
    /**
     * This function is called by the view admin/main if the user searches for
     * a character.
     */
    public function search_character()
    {
        if (!$this->user->hasRight('see_character_list'))
        {
            $param = array('error' => 'You are not allowed to see character list');
            $this->output->showPage(lang('admin_title'), 'admin/main', $param);
            return;
        }
        
        // the searchfield has to contain at least one character        
        $rules['TMWcharacter']  = "required|min_length[1]";
        $this->validation->set_rules($rules);
        if ($this->validation->run() == false)
        {
            $this->output->showPage(lang('admin_title'), 'admin/main');
            return;
        }
        
        // search for the given character name
        // due to another bug in pdo that wraps parenthesis around the table
        // but not araound the join part, sqlite returns an error
        $search = '%' . $this->input->post('TMWcharacter') . '%';

        $sql = "SELECT ".Character::CHARACTER_TBL.".*, "
             . "       ".User::ACCOUNT_TBL.".username"
             . "  FROM ".Character::CHARACTER_TBL
             . "  JOIN ".User::ACCOUNT_TBL
             . "    ON ".Character::CHARACTER_TBL.".user_id = ".
                         User::ACCOUNT_TBL.".id"
             . " WHERE ".Character::CHARACTER_TBL.".name LIKE '".$search."'"
             . " ORDER BY ".Character::CHARACTER_TBL.".name";
             
        $res = $this->db->query($sql);
        
        if ($res->num_rows() > 0)
        {
            // transform sql results to char objects
            $result = array();
            foreach ($res->result() as $row)
            {
                $result[] = new Character($row);
            }
            
            $param = array(
                'result_character' => $result,
                'searchstring'     => $this->input->post('TMWcharacter')
            );
        }
        else
        {
            $param = array('result_character' => false);
        }
        
        $this->output->showPage(lang('admin_title'), 'admin/main', $param);
    } // function search_character()
    
} // class Myaccount
?>

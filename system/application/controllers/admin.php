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
 * The admin controller is responsible for all actions a admin or gm can do 
 * to administrate tmwweb.
 * Each functions in the admin controller need an authorized user with 
 * administrative rights, so the authentication is just done in the 
 * constructor, rather in every single function.
 * 
 * @author Andreas Habel <mail@exceptionfault.de>
 * @ingroup controllers
 */
class Admin extends Controller {

    /**
     * This constant defines the format CodeIgniter writes its logfiles
     * to the log_path directory. 
     */
    const LOGFILE_FORMAT = "/^log-\d{4}-\d{2}-\d{2}\.php$/";
    
    
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
     * This function is called by the maintenance view if the user requests to
     * execute a maintenance task.
     *
     * @param action (String) Action that should be executed,
     */
    public function maintenance($action=null)
    {
        if (!$this->user->isAuthenticated() || !$this->user->isAdmin())
        {
            return;
        }
        
        $this->load->library('mapprovider');
        $this->load->library('dalprovider');
        
        // execute the requested action
        $retmsg = null;
        $params = array();
        
        switch ($action)
        {
            case 'reload_items.xml';
                $retmsg = $this->_reload_items_file($params);
                break;
            case 'reload_maps.xml':
                $retmsg = $this->_reload_maps_file($params);
                break;
        }
        
        $params['maps_file_age'] = $this->mapprovider->getMapVersion();
        $params = array_merge($params, $this->_count_error_logs());
        
        $this->output->showPage(lang('maintenance_title'), 
            'admin/maintenance', $params);
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
    
    
    /**
     * This function tries to reload the maps.xml from tmwserv and updates the
     * local cache.
     * @param[in,out] params (Array) Parameter that should be send to the view
     */
    private function _reload_maps_file(& $params)
    {
        $this->mapprovider->load_maps_file();
        $params['action_result'] = lang('maps_file_reloaded');
    }
    
    /**
     * This function tries to reload the items.xml from tmwserv and updates the
     * database table.
     * @param[in,out] params (Array) Parameter that should be send to the view
     */
    private function _reload_items_file(& $params)
    {
        $retval = $this->dalprovider->refreshStorage();
        $params['action_result']       = lang('items_file_reloaded');
        $params['missing_item_images'] = $retval;
    }
    
    
    /** 
     * This function looks into the error log directory and counts the number 
     * and size of all error logs as well as the date of the first and the
     * last file.
     *
     * @return (Array) Returns an array with the resulting file informations.
     */
    private function _count_error_logs()
    {
        $log_path = $this->config->item('log_path');
        if (strlen($log_path) == 0)
        {
            $log_path = './system/logs';
        } 
        
        $retval = array(
            'log_count'     => 0, 
            'logfile_size'  => 0,
            'log_path'      => $log_path
        );
       
        foreach(scandir($log_path) as $entry)
        {
            // skip directories and index.html file
            if (is_dir($entry) && $entry == "index.html")
            {
                continue;
            }
                       
            // check if the file is a logfile e.g. "log-2008-09-03.php"
            if (preg_match(Admin::LOGFILE_FORMAT , $entry) > 0)
            {
                $retval['log_count']++;
                /** todo: get the filesize and add to the array */
                $retval['logfile_size'] += 0;
                /** todo: get the mod date and compute oldest and newest file */
                $retval['logfile_size'] += 0;
            }
        }
        return $retval;
    }
    
} // class Myaccount
?>

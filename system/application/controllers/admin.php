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

/**
 * The admin controller is responsible for all actions a admin or gm can do 
 * to administrate manaweb.
 * Each functions in the admin controller need an authorized user with 
 * administrative rights, so the authentication is just done in the 
 * constructor, rather in every single function.
 * 
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
            $this->config->item('mana_enable_profiler')
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
                'manaweb/login_form', $param);
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
     * Shows a account sheet.
     * @param id (int) Unique id of the account.
     */
    public function show_account($id)
    {
        if (!$this->user->isAuthenticated() || !$this->user->isAdmin())
        {
            return;
        }
        $this->translationprovider->loadLanguage('account');
        $params = array();
        $acc = Account::getAccount($id);
        $params['account'] = $acc;
        $page = 'admin/account';

        $this->output->showPage(lang('account_username').': '. $acc->getUsername(),
            $page, $params);
    }

    /**
     * Shows a character sheet.
     * @param id (int) Show the character with the given id.
     */
    public function show_character($id)
    {
        if (!$this->user->isAuthenticated() || !$this->user->isAdmin())
        {
            return;
        }
        $this->translationprovider->loadLanguage('character');
        $this->load->library('mapprovider');
        
        $params = array();
        $char   = $this->user->getCharacter($id);
        $params['char'] = $char;
        $page = 'admin/character';

        $this->output->showPage(lang('character').': '. $char->getName(),
            $page, $params);
    }

    /**
     * This function is called by the maintenance view if the user requests to
     * execute a maintenance task.
     *
     * @param action (String) Action that should be executed,
     */
    public function maintenance($action=null, $param=null)
    {
        if (!$this->user->isAuthenticated() || !$this->user->isAdmin())
        {
            return;
        }
        
        $this->load->library('mapprovider');
        $this->load->library('skillprovider');
        $this->load->library('dalprovider');
        
        // execute the requested action
        $retmsg = null;
        $params = array();
        
        switch ($action)
        {
            case 'reload_item_images';
                $retmsg = $this->_reload_items_file($params);
                break;
            case 'reload_maps.xml':
                $retmsg = $this->_reload_maps_file($params);
                break;
            case 'reload_skills.xml':
                $retmsg = $this->_reload_skills_file($params);
                break;
            case 'list_logfiles':
                $retmsg = $this->_load_logfiles($params);
                break;
            case 'show_log':
                $retmsg = $this->_show_logfile($params, $param);
                break;
            case 'delete_log':
                $retmsg = $this->_delete_logfile($params, $param);
                break;
        }
        
        $params['maps_file_age'] = $this->mapprovider->getMapVersion();
        $params['skills_file_age'] = $this->skillprovider->getSkillsCacheVersion();

        // load logfiles if not already done
        if (!isset($params['logfiles']))
        {
            $params = array_merge($params, $this->_count_error_logs());
        }
        
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
        $res = $this->db->get(Account::ACCOUNT_TBL);
        
        if ($res->num_rows() > 0)
        {
            $accounts = array();
            foreach($res->result() as $row)
            {
                $accounts[] = new Account($row);
            }
            $param = array(
                'result_account' => $accounts,
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
     * This function is called by the main admin view when the user types
     * in the "search Account" field.
     * @return HTML list containing all searched account names.
     */
    public function search_account_ajax()
    {
        if (!$this->user->hasRight('see_account_list'))
        {
            echo "<ul></ul>";
            return;
        }
        
        if (strlen($this->input->post('TMWusername')) < 1)
        {
            echo "<ul></ul>";
            return;
        }

        $search = $this->input->post('TMWusername') . '%';
        $this->db->where('username LIKE \'' . $search . '\'');
        $res = $this->db->get(Account::ACCOUNT_TBL);

        echo "<ul>";
        foreach ($res->result() as $row)
        {
            echo "<li>" . $row->username . "</li>";
        }
        echo "</ul>";
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
             . "       ".Account::ACCOUNT_TBL.".username"
             . "  FROM ".Character::CHARACTER_TBL
             . "  JOIN ".Account::ACCOUNT_TBL
             . "    ON ".Character::CHARACTER_TBL.".user_id = ".
                         Account::ACCOUNT_TBL.".id"
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
     * This function is called by the main admin view when the user types
     * in the "search Character" field.
     * @return HTML list containing all searched character names.
     */
    public function search_character_ajax()
    {
        if (!$this->user->hasRight('see_character_list'))
        {
            echo "<ul></ul>";
            return;
        }

        if (strlen($this->input->post('TMWcharacter')) < 1)
        {
            echo "<ul></ul>";
            return;
        }

        $search = $this->input->post('TMWcharacter') . '%';
        $this->db->where('name LIKE \'' . $search . '\'');
        $res = $this->db->get(Character::CHARACTER_TBL);

        echo "<ul>";
        foreach ($res->result() as $row)
        {
            echo "<li>" . $row->name . "</li>";
        }
        echo "</ul>";
    }
    
    /**
     * This function tries to reload the XML_MAPS_FILE from manaserv and updates the
     * local cache.
     * @param[in,out] params (Array) Parameter that should be send to the view
     */
    private function _reload_maps_file(& $params)
    {
        $this->mapprovider->load_maps_file();
        $params['action_result'] = lang('maps_file_reloaded');
    }

    /**
     * This function tries to reload the XML_SKILLS_FILE from manaserv and updates the
     * local cache.
     * @param[in,out] params (Array) Parameter that should be send to the view
     */
    private function _reload_skills_file(& $params)
    {
        $this->skillprovider->loadSkillsFile();
        $params['action_result'] = lang('skills_file_reloaded');
    }
    
    /**
     * This function tries to reload the items.xml from manaserv and updates the
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
     * Returns a list of all logfiles written by CodeIgniter.
     * @param[in,out] params (array) Parameter that should be send to the view
     */
    private function _load_logfiles(& $params)
    {
        $params['action_result']       = "";
        $params['show_logfiles']       = true;

        $params = array_merge($params, $this->_count_error_logs());
    }

    /**
     * Shows a logfile from the log directory of CodeIgniter
     * @param[in,out] params (array) Parameter that should be send to the view
     * @param filename (string) filename to show
     */
    private function _show_logfile(& $params, $filename)
    {
        // load the list of logfiles
        $this->_load_logfiles($params);

        // determine the directory of logfiles
        $log_path = $this->config->item('log_path');
        if (strlen($log_path) == 0)
        {
            $log_path = './system/logs';
        }
        if (is_file($log_path . "/" . $filename))
        {
            $msg = "";
            ob_start();
            readfile($log_path . "/" . $filename);

            $params['logfiles'][$filename]['content'] = ob_get_contents();
            ob_end_clean();
        }
        else
        {
            $msg = "Error: The logfile <tt>" . $filename . "</tt> was not found.";
        }
        
        $params['action_result'] = $msg;
    }

    /**
     * Deletes a logfile from the log directory of CodeIgniter
     * @param[in,out] params (array) Parameter that should be send to the view
     * @param filename (string) filename to delete
     */
    private function _delete_logfile(& $params, $filename)
    {
        // determine the directory of logfiles
        $log_path = $this->config->item('log_path');
        if (strlen($log_path) == 0)
        {
            $log_path = './system/logs';
        }

        if (is_file($log_path . "/" . $filename))
        {
            $msg = "The logfile <tt>" . $filename . "</tt> has been deleted.";
            unlink($log_path . "/" . $filename);
        }
        else
        {
            $msg = "Error: The logfile <tt>" . $filename . "</tt> was not found.";
        }

        $params = array_merge($params, $this->_count_error_logs());
        $params['action_result'] = $msg;
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

        $files = array();
        $retval = array(
            'log_count'     => 0, 
            'logfile_size'  => 0,
            'log_path'      => $log_path,
            'min_date'      => null,
            'max_date'      => null,
            'logfiles'      => null
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

                $filesize = filesize($log_path . "/" . $entry);
                $retval['logfile_size'] += $filesize;

                // compute oldest and newest modification date of logfiles
                $mtime = filemtime($log_path . "/" . $entry);
                if (!isset($retval['min_date']))
                {
                    $retval['min_date'] = $mtime;
                    $retval['max_date'] = $mtime;
                }
                else
                {
                    if ($mtime < $retval['min_date'])
                        $retval['min_date'] = $mtime;
                    if ($mtime > $retval['max_date'])
                        $retval['max_date'] = $mtime;
                }

                $files[$entry] = array('filename'=>$entry, 'filesize'=>$filesize, 'filedate'=>$mtime);
            }
        }
        $retval['logfiles'] = $files;
        return $retval;
    }
    
} // class Myaccount
?>

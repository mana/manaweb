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
 */


/**
 * The CharController is responsible for all actions a user can do 
 * with its character.
 * 
 * @ingroup controllers
 */ 
class Charcontroller extends Controller {

    /**
     * Initializes the Home controller.
     */
    function __construct()
    {
        parent::Controller();
        $this->output->enable_profiler(
            $this->config->item('tmw_enable_profiler')
        );

        $this->load->library('validation');        
        $this->load->helper('form');
            
        // check if the user is currently logged in
        if (!$this->user->isAuthenticated())
        {
            $param = array('has_errors' => false); 
            $this->translationprovider->loadLanguage('account');
            $this->output->showPage(lang('account_login'), 
                'tmwweb/login_form', $param);
        }
    }
    
    
    /**
     * This function is called from the character overview page and 
     * leeds to the character details with a given character id.
     * The function checks wheter the current user may see this details
     * and forwards to the details view.
     *
     * @param id      (int) Unique id of the character
     * @param subpage (String) Subpage to show, e.g. skills or inventory
     */ 
    public function index($id, $subpage="sheet")
    {
        if (!$this->user->isAuthenticated())
        {
            return;
        }
        
        $this->translationprovider->loadLanguage('character');
        $this->load->library('Mapprovider');
        $this->load->library('Imageprovider');
        
        // check if the user is the owner of this char
        if (!$this->user->hasCharacter($id))
        {
           show_error(lang('character_view_forbidden'));
        }        
        
        $params = array();
        $char   = $this->user->getCharacter($id);
        $params['char'] = $char;
        
        // enable character menu
        $this->menuprovider->setChar($char);
        
        
        switch ($subpage)
        {
            case 'admin':
                $page = 'tmwweb/character_administration';
                break;
            case 'inventory':
                $page = 'tmwweb/character_inventory';
                $params['imageprovider']  = $this->imageprovider;
                break;
            case 'guilds':
                $page = 'tmwweb/character_guilds';
                break;
            case 'skills':
                $page = 'tmwweb/character_skills';
                break;
            case 'sheet':
            default:
                $page = 'tmwweb/character';
                break;
        }
        
        $this->output->showPage(lang('character').': '. $char->getName(), 
            $page, $params);
    }
    
} // class CharController
?>

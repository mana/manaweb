<?php
/*
 *  The Mana Server Account Manager
 *  Copyright 2009 The Mana Project Development Team
 *
 *  This file is part of The Mana Server.
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
 */


/**
 * The Guildcontroller responsible for all actions according to guilds and
 * parties.
 * 
 * @ingroup controllers
 */ 
class Guildcontroller extends Controller {

    /**
     * Initializes the Guildcontroller.
     */
    function __construct()
    {
        parent::Controller();
        $this->output->enable_profiler(
            $this->config->item('mana_enable_profiler')
        );

        $this->translationprovider->loadLanguage('guilds');

        // check if the user is currently logged in
        if (!$this->user->isAuthenticated())
        {
            $param = array('has_errors' => false); 
            $this->translationprovider->loadLanguage('account');
            $this->output->showPage(lang('account_login'), 
                'manaweb/login_form', $param);
        }
    }
    
    
    /**
     * This function is called from the character overview page and 
     * leeds to the character details with a given character id.
     * The function checks wheter the current user may see this details
     * and forwards to the details view.
     *
     * @param id      (int) Unique id of the guild
     */ 
    public function index($id)
    {
        if (!$this->user->isAuthenticated())
        {
            return;
        }
        
        if (!$this->user->isMemberOfGuild($id))
        {
            echo "forbidden";
            return;
        }

        $guild = Guild::getGuild($id);
        $param = array('guild' => $guild, 'user' => $this->user->getuser());
        $this->output->showPage(
                sprintf(lang('guild_details_header'),$guild->getName()),
                'manaweb/guilds/show_guild', $param);
    }
    
} // class Guildcontroller
?>

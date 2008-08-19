<?php
/*
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
 *  $Id: $
 */

/**
 * The Server_statistics model deals with all global data according to a 
 * server.
 */ 
class Server_statistics extends Model {

    //*************************************************************************
    
    /**
     * Constant for statistic value of player count.
     */
    const PLAYER_COUNT = 'player_count';
    /**
     * Constant for statistic value of character count.
     */
    const CHARACTER_COUNT = 'character_count';
    /**
     * Constant for statistic value of guild count.
     */
    const GUILD_COUNT = 'guild_count';
    
    //*************************************************************************
    
    
    /**
     * Constructor initializes a new instalnce of the Server_statistics model.
     */
    public function __construct()
    {
        parent::Model();
    }
    
    
    /**
     * This function gathers all global statistics from the tmw server and 
     * summarizes them in an array.
     * 
     * @returns Array Array with server statistics
     */
    public function getGlobalStats()
    { 
        $stats = array();
        
        $stats[Server_statistics::PLAYER_COUNT] = $this->getPlayerCount();
        $stats[Server_statistics::CHARACTER_COUNT] = $this->getCharacterCount();
        $stats[Server_statistics::GUILD_COUNT] = $this->getGuildCount();
        
        return $stats;
    }
    
    
    /** 
     * This function counts all registered accounts.
     * 
     * @returns int Number of accounts registered at the tmw server.
     */
    private function getPlayerCount()
    {
        return $this->db->count_all('tmw_accounts');
    }
    
    
    /** 
     * This function counts all created characters.
     * 
     * @returns int Number of created characters on the tmw server.
     */
    private function getCharacterCount()
    {
        return $this->db->count_all('tmw_characters');
    }
    
    
    /** 
     * This function counts all created guilds.
     * 
     * @returns int Number of created guilds on the tmw server.
     */
    private function getGuildCount()
    {
        return $this->db->count_all('tmw_guilds');
    }
    
} // class Server Statistics
?>

<?php
/*
 *  The Mana Server Account Manager
 *  Copyright 2008 The Mana Server Development Team
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
 */

// load dependecies
require_once(APPPATH.'models/guild'.EXT);
require_once(APPPATH.'models/character'.EXT);


/**
 * The Server_statistics model deals with all global data according to a
 * server.
 *
 * @ingroup models
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
     * Constant for statistic value of character topten list.
     */
    const CHARACTER_TOPTEN = 'character_topten';
    /**
     * Constant for statistic value of character topten list.
     */
    const ECONOMY_PURCHASE_POW = 'economy_purchase_power';
    /**
     * Constant for statistic value of guild count.
     */
    const GUILD_COUNT = 'guild_count';
    /**
     * Constant for statistic value of guild topten list.
     */
    const GUILD_TOPTEN = 'guild_topten';

    //*************************************************************************


    /**
     * Constructor initializes a new instalnce of the Server_statistics model.
     */
    public function __construct()
    {
        parent::Model();
    }


    /**
     * This function gathers all global statistics from the manaserv server and
     * summarizes them in an array.
     *
     * @return Array with server statistics
     */
    public function getGlobalStats()
    {
        $stats = array();

        // simple stats
        $stats[Server_statistics::PLAYER_COUNT] = $this->getPlayerCount();
        $stats[Server_statistics::CHARACTER_COUNT] = $this->getCharacterCount();
        $stats[Server_statistics::GUILD_COUNT] = $this->getGuildCount();
        $stats[Server_statistics::ECONOMY_PURCHASE_POW] = $this->getPurchasingPower();

        // more complex stats
        $stats[Server_statistics::GUILD_TOPTEN] = $this->getGuildTopTen();
        $stats[Server_statistics::CHARACTER_TOPTEN] = $this->getCharacterTopTen();

        return $stats;
    }


    /**
     * This function counts all registered accounts.
     *
     * @return (int) Number of accounts registered at the manaserv.
     */
    private function getPlayerCount()
    {
        // TODO: use constants for database names
        return $this->db->count_all('mana_accounts');
    }


    /**
     * This function counts all created characters.
     *
     * @return (int) Number of created characters on the manaserv.
     */
    private function getCharacterCount()
    {
        return $this->db->count_all('mana_characters');
    }


    /**
     * This function counts all created guilds.
     *
     * @return (int) Number of created guilds on the manaserv.
     */
    private function getGuildCount()
    {
        return $this->db->count_all('mana_guilds');
    }


    /**
     * This function returns the amount of money all characters have together.
     *
     * @return (int) Purchasing power of The Mana Server population.
     */
    private function getPurchasingPower()
    {
        $this->db->select_sum('attr_base');
        $query = $this->db->get_where( 'mana_char_attr',
                    array('attr_id' => Character::CHAR_ATTR_GP));

        return $query->row()->attr_base;
    }


    /**
     * This function returns a top ten list of all registered guilds.
     * At the moment the top ten is computed, simply by counting the number of
     * members.
     *
     * @return (Array) Topten list of guilds
     */
    private function getGuildTopTen()
    {
        // as the statements get more complex its easier and more efficient to
        // write statements per dbsystem individually
        if (($this->db->dbdriver == 'pdo') ||
            ($this->db->dbdriver ==  'mysql' ))
        {
            // should work for mysql and sqlite
            $sql = "SELECT g.ID AS ID, "
                 . "        g.NAME AS NAME, "
                 . "        COUNT(m.GUILD_ID) AS MEMBERS "
                 . "  FROM " . Guild::GUILD_TBL . " g "
                 . " LEFT OUTER JOIN " . Guild::GUILD_MEMBER_TBL . " m "
                 . "    ON g.ID = m.guild_id "
                 . " GROUP BY g.ID, g.NAME "
                 . " ORDER BY MEMBERS DESC, NAME "
                 . " LIMIT 10 ";
        }
        else
        {
            log_message('error', 'models/server_statistics: requested statement ' .
                'for unknown database system. This need implementation!');
            show_error( "this feature is not implemented for your ".
             "database system!");
        }

        $res = $this->db->query($sql);


        if ($res->num_rows() == 0)
        {
            return false;
        }

        return $res->result();
    } // function getGuildTopTen()


    /**
     * This function returns a top ten list of all registered guilds.
     * At the moment the top ten is computed, simply by counting the number of
     * members.
     *
     * @return (Array) Topten list of characters
     */
    private function getCharacterTopTen()
    {
        // as the statements get more complex its easier and more efficient to
        // write statements per dbsystem individually
        if (($this->db->dbdriver == 'pdo') ||
            ($this->db->dbdriver ==  'mysql' ))
        {
            // should work for mysql and sqlite
            $sql = "SELECT c.ID AS ID, "
                 . "       c.NAME as NAME, "
                 . "       c.LEVEL AS LEVEL, "
                 . "       u.username AS USERNAME "
                 . "  FROM " . Character::CHARACTER_TBL . " c "
                 . "  JOIN " . Account::ACCOUNT_TBL . " u "
                 . "    ON c.user_id = u.id "
                 . " ORDER BY c.LEVEL DESC, NAME DESC "
                 . " LIMIT 10 ";
        }
        else
        {
            log_message('error', 'models/server_statistics: requested statement ' .
                'for unknown database system. This need implementation!');
            show_error( "this feature is not implemented for your ".
             "database system!");
        }

        $res = $this->db->query($sql);

        if ($res->num_rows() == 0)
        {
            return false;
        }

        return $res->result();

    } // function getCharacterTopTen()

} // class Server Statistics
?>

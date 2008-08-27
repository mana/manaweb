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
 *  @subpackage models
 */


/**
 * The guild model deals with all data according to guilds.
 * This class is not used as a model in terms of CodeIgniter. It is
 * used as a simple data object.
 *
 *  @author Andreas Habel <mail@exceptionfault.de>
 *  @copyright Copyright 2008 The Mana World Development Team
 *
 *  @package tmwweb
 *  @subpackage models
 */ 
class Guild {

    /**
     * Name of the guilds table
     * @var string
     */
    const GUILD_TBL        = 'tmw_guilds';
    
    /**
     * Name of the guild memberships table
     * @var string
     */
    const GUILD_MEMBER_TBL = 'tmw_guild_members';
    
    /**
     * Permissions defining rights of guild members
     * Those constants are derived from tmwser/trunk/src/chat-server/guild.hpp
     *
     * Members with NONE cannot invite users or set permissions
     * Members with COMMANDER can invite other users but cannot set permissions
     * Members with LEADER can invite users and set permissions
     */
    const PERMISSION_NONE      = 0;
    const PERMISSION_COMMANDER = 1;
    const PERMISSION_LEADER    = 2;
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;
    
    /**
     * holds a reference to the database record.
     */
    private $guild;
    
    /**
     * Constructor initializes a new instance of the Character model.
     * The constructor needs a database record as parameter.
     */
    public function __construct($record)
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config directly
        $this->CI =& get_instance();
        $this->guild = $record;
    }
    
    
    /**
     * This function retruns an instance of the guild with the given id.
     *
     * @param  int    ID of the guild
     * @return object Guild object or false if the guild doesn't exist
     */
    public static function getGuild($id)
    {
        $CI  =& get_instance();
        $res = $CI->db->get_where( Guild::GUILD_TBL,
            array('id' => $id));
            
        if ($res->num_rows() > 0)
        {
            return new Guild($res->row());
        }
        else
        {
            return false;
        }           
    }
    
    
    /**
     * This function returns the unique Id of the guild.
     * @return int Id of the guild
     */
    public function getId()
    {
        return intval($this->guild->id);
    }
    
    
    /**
     * This function returns the name of the guild.
     * @return string Name of the guild
     */
    public function getName()
    {
        return $this->guild->name;
    }
    
    
    /**
     * This function returns a list with all members of the guild.
     * @todo implement this function when updated sqlite db is available
     * @return array Array with all members of the guild
     */
    public function getMembers()
    {
        //TODO: implement when new db is available
        return array();
    }
    
        
} // class Guild

?>

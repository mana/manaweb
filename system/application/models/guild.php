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
 * The guild model deals with all data according to guilds.
 * This class is not used as a model in terms of CodeIgniter. It is
 * used as a simple data object.
 *
 * @ingroup tmwweb models
 */ 
class Guild {

    /**
     * Name of the guilds table
     */
    const GUILD_TBL        = 'tmw_guilds';
    
    /**
     * Name of the guild memberships table
     */
    const GUILD_MEMBER_TBL = 'tmw_guild_members';
    
    
    //
    // Permissions defining rights of guild members.
    // Those constants are derived from 
    // <tt>defines.h</tt>
    //
     
    /**
     * Guild member permissions
     * Members with NONE cannot invite users or set permissions
     * Members with TOPIC_CHANGE can change the guild channel topic
     * Members with INVITE can invite other users
     * Memeber with KICK can remove other users
     * Members with OWNER can invite users and set permissions
     */
    const GAL_NONE         = 0;
    const GAL_TOPIC_CHANGE = 1;
    const GAL_INVITE       = 2;
    const GAL_KICK         = 4;
    const GAL_OWNER        = 255;
    
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
     *
     * @param record (Array) Database record to initialite values of the Guild.
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
     * @param  id (int) ID of the guild
     * @return (Object) Guild object or false if the guild doesn't exist
     */
    static function getGuild($id)
    {
        $res = $this->CI->db->get_where( Guild::GUILD_TBL,
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
     * @return (int) Id of the guild
     */
    public function getId()
    {
        return intval($this->guild->id);
    }
    
    
    /**
     * This function returns the name of the guild.
     * @return (String) Name of the guild
     */
    public function getName()
    {
        return $this->guild->name;
    }
    
    
    /**
     * This function returns a list with all members of the guild.
     * 
     * @return (Array) Returns Array with all members_ids of the guild and their
     *                 current rights.
     */
    public function getMembers()
    {
        $res = $this->CI->db->get_where( Guild::GUILD_MEMBER_TBL,
            array('guild_id' => $this->getId()));

        $members = array();
        if ($res->num_rows() > 0)
        {
            foreach($res->result() as $member)
            {
                $members[] = array( 'member_id' => $member['member_id'],
                    'rights' => $member['rights'] );
            }
        }
        return $members;
    }

    /**
     * This function returns a membercount of the guild.
     *
     * @return (int) Members in the guild
     */
    public function countMembers()
    {
        $res = $this->CI->db->get_where( Guild::GUILD_MEMBER_TBL,
            array('guild_id' => $this->getId()));
        return $res->num_rows();
    }
    
        
} // class Guild

?>

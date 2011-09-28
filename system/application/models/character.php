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

// load dependecies
require_once(APPPATH.'models/inventory'.EXT);
require_once(APPPATH.'models/guild'.EXT);

/**
 * The character model deals with all data according to a character.
 * This class is not used as a model in terms of CodeIgniter. It is
 * used as a simple data object.
 *
 * @ingroup models
 */
class Character {

    const GENDER_MALE   = 0;                            /**< Defines constant for male characters */
    const GENDER_FEMALE = 1;                            /**< Defines constant for female characters */

    // character attributes ///////////////////////////////////////////////////

    const CHAR_ATTR_STRENGTH     = "1";    /**< Constant for character attribute STRENGTH */
    const CHAR_ATTR_AGILITY      = "2";    /**< Constant for character attribute AGILITY */
    const CHAR_ATTR_DEXTERITY    = "3";    /**< Constant for character attribute DEXTERITY */
    const CHAR_ATTR_VITALITY     = "4";    /**< Constant for character attribute VITALITY */
    const CHAR_ATTR_INTELLIGENCE = "5";    /**< Constant for character attribute INTELLIGENCE */
    const CHAR_ATTR_WILLPOWER    = "6";   /**< Constant for character attribute WILLPOWER */
    const CHAR_ATTR_GP           = "18";   /**< Constant for character attribute GP */

    /** List of all online characters. */
    private static $onlinelist;

    ///////////////////////////////////////////////////////////////////////////

    /**
     * This constant defines the exponent of the \a experienceForLevel function
     * to determine the needed experience points to level up.
     */
    const EXPCURVE_EXPONENT = 3;
    /**
     * This constant defines the factor of the \a experienceForLevel function
     * to determine the needed experience points to level up.
     */
    const EXPCURVE_FACTOR = 10;

    ///////////////////////////////////////////////////////////////////////////

    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;

    /**
     * holds a reference to the database record.
     */
    private $char;

    /**
     * Holds a reference to the \a Inventory model of the character.
     * This variable only gets initialized, when the method
     * \a Character::getInventory is called.
     */
    private $inventory;

    /**
     * Database record representing the owning user of this character.
     * This variable only gets initialized, when the method
     * \a Character::getUsername() or \a Character::getOwner() is called.
     */
    private $user;

    /**
     * Array storing all experiences of a character.
     */
    private $skills;


    /**
     * This function returns the needed experience points for a character to
     * reach the given level.
     *
     * @param level (int) Level the character wants to reach
     * @return (int) Experience points a character needs to level up.
     */
    static function experienceForLevel($level)
    {
        return intval(pow($level, Character::EXPCURVE_EXPONENT) *
            Character::EXPCURVE_FACTOR);
    }


    /**
     * Constructor initializes a new instance of the Character model.
     * The constructor needs a database record as parameter.
     *
     * @param record (Array) Database record to initialite values of the
     *                       Character.
     */
    public function __construct($record)
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and
        // therefore we cannot access $this->config directly
        $this->CI =& get_instance();
        $this->char = $record;
        $this->inventory = null;
        $this->user = null;
        $this->skills = array();
        $this->attributes = array();

        // characters need informations about maps so load the mapprovider
        if (!isset($this->CI->mapprovider))
        {
            $this->CI->load->library('mapprovider');
        }
    }

    /**
     * This function returns the unique id of the character.
     *
     * @return (int) Unique id of the character
     */
    public function getID()
    {
        return $this->char->id;
    }

    /**
     * This function returns the name of the character.
     *
     * @return (String) Name of the character
     */
    public function getName()
    {
        return $this->char->name;
    }

    /**
     * Returns the owner of the character.
     * @return (object) Owner of the character.
     */
    public function getOwner()
    {
        if (!isset($this->user))
        {
            $tblAccounts = $this->CI->config->item('tbl_name_accounts');
            
            $query = $this->CI->db->get_where($tblAccounts,
                array('id' => $this->char->user_id), 1);
            $this->user = $query->result();
            $this->user = $this->user[0];
        }
        return $this->user;
    }

    /**
     * Returns the Id of the owner.
     * @return (int) ID of the owning account.
     */
    public function getOwnerId()
    {
        return $this->char->user_id;
    }

    /**
     * This function returns the username of the account owning the character.
     * Normally, this column is not available as it is not present in the
     * characters table. But if the character model is initialized with a
     * resultset of a join between characters and accounts this property can
     * return the name of the user.
     *
     * @return (string) Username of the owning player.
     */
    public function getUsername()
    {
        if (isset($this->char->username))
        {
            return $this->char->username;
        }
        else
        {
            return $this->getOwner()->username;
        }
    }

    /**
     * This function returns the level of the character.
     *
     * @return (int) Level of the character
     */
    public function getLevel()
    {
        return $this->char->level;
    }

    /**
     * This function returns the gender of the character.
     * See constants GENDER_MALE and GENDER_FEMALE.
     *
     * @param  format (String)
     *                If $format is \c 'int', the gender is given as id.
     *                If $format is \c 'image', the gender is given as html
     *                image tag.
     * @return (Mixed) Gender of the character
     */
    public function getGender($format='int')
    {
        if ($format == 'image')
        {
            switch ($this->char->gender)
            {
                case Character::GENDER_MALE:
                    return "<img class=\"online-led\"
                        src=\"". base_url()."images/gender_male.gif\">";
                    break;
                case Character::GENDER_FEMALE:
                    return "<img class=\"online-led\"
                        src=\"".base_url()."images/gender_female.gif\">";
                    break;
            }
        }
        else
        {
            return $this->char->gender;
        }
    }

    /**
     * This function returns the map, the character is located on.
     *
     * @return (Object) Map the character is located.
     */
    public function getMap()
    {
        return $this->CI->mapprovider->getMap($this->char->map_id);
    }

    /**
     * This function returns the attribute value of the character.
     * Use the constants Character::CHAR_ATTR_* as input of this function.
     *
     * @param attribute (String) Attribute name.
     * @return (int) Attribute value.
     */
    public function getAttribute($attribute)
    {
        // attributes are not initialized yet, do it now!
        if (sizeof($this->attributes) == 0)
        {
            $tblCharAttr = $this->CI->config->item('tbl_name_char_attr');
            $query = $this->CI->db->get_where($tblCharAttr,
                array('char_id' => $this->char->id));

            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    $this->attributes[$row->attr_id] = $row->attr_mod;
                }
            }
        }

        if (isset($this->attributes[$attribute]))
        {
            return $this->attributes[$attribute];
        }
        return 0;
    }

    /**
     * This function returns a skill value of the character.
     * Use the constants Character::CHAR_SKILL_* as input of this function.
     *
     * @param skill (String) Skill name.
     * @return (int) Skill value.
     */
    public function getSkill($skill)
    {
        // skills are not initialized yet, do it now!
        if (sizeof($this->skills) == 0)
        {
            $tblCharSkills = $this->CI->config->item('tbl_name_char_skills');

            $query = $this->CI->db->get_where($tblCharSkills,
                array('char_id' => $this->char->id));

            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    $this->skills[$row->skill_id] = $row->skill_exp;
                }
            }
        }

        if (isset($this->skills[$skill]))
        {
            return $this->skills[$skill];
        }
        return 0;
    }

    /**
     * Gets the level for a given skill
     *
     * @param skill (String) Skill name.
     * @return (\a int) Level of the skill.
     */
    public function getSkillLevel($skill)
    {
        $level = 0;
        $exp = $this->getSkill($skill);
        while ($exp >= Character::experienceForLevel($level + 1))
        {
            $level++;
        }

        return $level;
    }

    /**
     * Returns an array with lots of informations about the current skill level
     * of a character.
     *
     * @param skill (int) Id of the skill.
     * @return (array) Array with los of skill informations.
     */
    public function getSkillInfo($skill)
    {
        $info = array();

        $info['exp'] = $this->getSkill($skill);
        $info['level'] = $this->getSkillLevel($skill);
        $info['level_exp_min'] = self::experienceForLevel($info['level']);
        $info['level_exp_max'] = self::experienceForLevel($info['level']+1);
        $info['exp_delta']     = $info['exp'] - $info['level_exp_min'];
        $info['exp_max_delta'] = $info['level_exp_max'] - $info['level_exp_min'];
        return $info;
    }

    /**
     * This function returns the inventory object of the character.
     *
     * @return (\a Inventory) Object of the character
     */
    public function getInventory()
    {
        if (!isset($this->inventory))
        {
            $this->inventory = new Inventory($this->char->id);
        }
        return $this->inventory;
    }

    /**
     * This function computes the maximum weight the character can carry.
     *
     * @remarks This algorithm is taken from the 0.0.* branch, so maybe it
     *          changes in later implementations of the 0.1.* branch.
     *
     * @return (int) Maximum weight the character can carry.
     */
    public function getMaximumWeight()
    {
        return intval($this->getAttribute(Character::CHAR_ATTR_STRENGTH) * 100);
    }

    /**
     * This functions is used to check wheter a character is member of at least
     * one guild.
     *
     * @return (bool) \c true, if the character is member of a guild,
                    otherwise \c false
     */
    public function isGuildMember()
    {
        $tblGuildMember = $this->CI->config->item('tbl_name_guild_members');
        $query = $this->CI->db->get_where($tblGuildMember,
            array('member_id' => $this->char->id), 1);

        if ($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * This function returns a list of guilds the character is member of.
     * @return (Array) List if guilds
     */
    public function getGuilds()
    {
        $db =& $this->CI->db;

        $tblGuildMember = $this->CI->config->item('tbl_name_guild_members');
        $tblGuilds = $this->CI->config->item('tbl_name_guilds');
        
        $db->from($tblGuilds.' g' );
        $db->join($tblGuildMember, 'id = '.$tblGuildMember.'.guild_id' );
        $db->where($tblGuildMember.'.member_id', $this->char->id);
        $query = $db->get();

        $guilds = array();
        foreach ($query->result() as $row)
        {
            $guilds[] = new Guild($row);
        }
        return $guilds;
    }

    /**
     * This function checks wheter the character is online or not.
     *
     * @param  format (String)
     *         If $format is \c 'bool', the function returns \c true or \c false
     *         If $format is \c 'img', the function returns a html img element
     * @return (object) Online status of the character
     */
    public function isOnline($format='bool')
    {
        $online = false;

        if (!isset(self::$onlinelist))
        {
            // load list of online users
            $tblOnlineList = $this->CI->config->item('tbl_name_online_list');
            $query = $this->CI->db->get($tblOnlineList);
            foreach ($query->result() as $char)
            {
                self::$onlinelist[$char->char_id] = $char;
            }
        }

        if (isset(self::$onlinelist[$this->getID()]))
        {
            $online = true;
        }

        switch ($format)
        {
            case 'bool':
                return $online; break;
            case 'img':
            case 'image':
                if ($online)
                {
                    return "<img class=\"online-led\"
                        src=\"". base_url()."images/status_online.png\">";
                }
                else
                {
                    return "<img class=\"online-led\"
                        src=\"". base_url()."images/status_offline.png\">";
                }
                break;
        }
    }
}

?>

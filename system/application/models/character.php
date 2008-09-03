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

// load dependecies 
require_once(APPPATH.'models/inventory'.EXT);

/**
 * The caharcter model deals with all data according to a character.
 * This class is not used as a model in terms of CodeIgniter. It is
 * used as a simple data object.
 *
 * @author Andreas Habel <mail@exceptionfault.de>
 * @ingroup models
 */ 
class Character {

    /**
     * Name of the characters table
     */
    const CHARACTER_TBL = 'tmw_characters';
    
    /** 
     * Defines constant for male characters
     */
    const GENDER_MALE   = 0;
    
    /**
     * Defines constant for female characters
     */
    const GENDER_FEMALE = 1;
    
    
    // character attributes ///////////////////////////////////////////////////
    
    /**
     * Constant for character attribute STRENGTH 
     */
    const CHAR_ATTR_STRENGTH = "str";
    
    /**
     * Constant for character attribute AGILITY
     */
    const CHAR_ATTR_AGILITY  = "agi";

    /**
     * Constant for character attribute DEXTERITY
     */
    const CHAR_ATTR_DEXTERITY  = "dex";

    /**
     * Constant for character attribute VITALITY
     */
    const CHAR_ATTR_VITALITY  = "vit";

    /**
     * Constant for character attribute INTELLIGENCE
     */
    const CHAR_ATTR_INTELLIGENCE  = "int";
    
    /**
     * Constant for character attribute INTELLIGENCE
     */
    const CHAR_ATTR_WILLPOWER  = "will";

    // character experiences //////////////////////////////////////////////////

    /*
     * Constants for character experiences 
     */
     
    /** Skill for unarmed fights */
    const CHAR_SKILL_NONE      = "unarmed_exp";
    /** Skill for fighting with knifes */
    const CHAR_SKILL_KNIFE     = "knife_exp"; 
    /** Skill for fighting with swords */
    const CHAR_SKILL_SWORD     = "sword_exp";
    /** Skill for fighting with polearms */
    const CHAR_SKILL_POLEARM   = "polearm_exp";
    /** Skill for fighting with staffs */
    const CHAR_SKILL_STAFF     = "staff_exp";
    /** Skill for fighting with a whip */
    const CHAR_SKILL_WHIP      = "whip_exp";
    /** Skill for shooting with bows */
    const CHAR_SKILL_BOW       = "bow_exp";
    /** Skill for shooting */
    const CHAR_SKILL_SHOOTING  = "shoot_exp";
    /** Skill for fighting with maces */
    const CHAR_SKILL_MACE      = "mace_exp";
    /** Skill for fighting with axes */
    const CHAR_SKILL_AXE       = "axe_exp";
    /** Skill for throwing weapons */
    const CHAR_SKILL_THROWN    = "thrown_exp";
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * This constant defines the exponent of the \a experienceForLevel function
     * to determine the needed experience points to level up.
     *
     * @todo These values should be stored in a external config file to allow
     *       tmwserv and tmwweb shared access to thoses constants.
     */
    const EXPCURVE_EXPONENT = 3;
    /**
     * This constant defines the factor of the \a experienceForLevel function
     * to determine the needed experience points to level up.
     *
     * @todo These values should be stored in a external config file to allow
     *       tmwserv and tmwweb shared access to thoses constants.
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
     * This function returns the username of the account owning the character.
     * Normally, this column is not available as it is not present in the
     * characters table. But if the character model is initialized with a
     * resultset of a join between characters and accounts this property can
     * return the name of the user. 
     *
     * @todo  think about if we should load the username in case it is not 
     *        included in the initializing record instead of returning null
     */
    public function getUsername()
    {
        if (isset($this->char->username))
        {
            return $this->char->username;
        }
        return null;
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
                    return "<img src=\"". base_url()."images/gender_male.gif\">";
                    break;
                case Character::GENDER_FEMALE:
                    return "<img src=\"".base_url()."images/gender_female.gif\">";
                    break;
            }
        }
        else
        {
            return $this->char->gender;
        }
    }
    
    /** 
     * This functions returns the money of the character. 
     *
     * @param  format (String) 
     *                If $format is \c 'int', the money is given as integer value.
     *                If $format is \c 'string', the money is formated for display
     * @return (Mixed) The money of the character
     */
    public function getMoney($format='int')
    {
        if ($format == 'string')
        {
            return number_format($this->char->money, 0, ".", ",");
            
        }
        else
        {
            return intval($this->char->money);
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
        return $this->char->$attribute;
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
        return $this->char->$skill;
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
        $query = $this->CI->db->get_where('tmw_guild_members', 
            array('member_id' => $this->char->id), 1);
            
        if ($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        };
    }
        
}

?>

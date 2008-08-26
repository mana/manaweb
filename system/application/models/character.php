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
 * The caharcter model deals with all data according to a character.
 * This class is not used as a model in terms of CodeIgniter. It is
 * used as a simple data object.
 *
 *  @author Andreas Habel <mail@exceptionfault.de>
 *  @copyright Copyright 2008 The Mana World Development Team
 *
 *  @package tmwweb
 *  @subpackage models
 */ 
class Character {

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

    /**
     * Constants for character experiences 
     */
    const CHAR_SKILL_NONE      = "unarmed_exp";
    const CHAR_SKILL_KNIFE     = "knife_exp";
    const CHAR_SKILL_SWORD     = "sword_exp";
    const CHAR_SKILL_POLEARM   = "polearm_exp";
    const CHAR_SKILL_STAFF     = "staff_exp";
    const CHAR_SKILL_WHIP      = "whip_exp";
    const CHAR_SKILL_BOW       = "bow_exp";
    const CHAR_SKILL_SHOOTING  = "shoot_exp";
    const CHAR_SKILL_MACE      = "mace_exp";
    const CHAR_SKILL_AXE       = "axe_exp";
    const CHAR_SKILL_THROWN    = "thrown_exp";
    
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
     * Constructor initializes a new instance of the Character model.
     * The constructor needs a database record as parameter.
     */
    public function __construct($record)
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config directly
        $this->CI =& get_instance();
        $this->char = $record;
        
        // characters need informations about maps so load the mapprovider
        if (!isset($this->CI->mapprovider))
        {
            $this->CI->load->library('mapprovider');
        }
    }
    
    /**
     * This function returns the unique id of the character.
     * 
     * @return int Unique id of the character
     */
    public function getID()
    {
        return $this->char->id;
    }
    
    
    /**
     * This function returns the name of the character.
     * 
     * @return string Name of the character
     */
    public function getName()
    {
        return $this->char->name;
    }
    
    /**
     * This function returns the level of the character.
     * 
     * @return int Level of the character
     */
    public function getLevel()
    {
        return $this->char->level;
    }
    
    /**
     * This function returns the gender of the character.
     * See constants GENDER_MALE and GENDER_FEMALE.
     * 
     * @return int Gender of the character
     */
    public function getGender()
    {
        return $this->char->gender;
    }
    
    /** 
     * This functions returns the money of the character.
     * 
     * @return int The money of the character
     */
    public function getMoney()
    {
        return $this->char->money;
    }
    
    
    /** 
     * This function returns the map, the character is located on.
     *
     * @return object Map the character is located.
     */
    public function getMap()
    {
        return $this->CI->mapprovider->getMap($this->char->map_id);
    }
    
    
    /** 
     * This function returns the attribute value of the character.
     * Use the constants Character::CHAR_ATTR_* as input of this function.
     *
     * @param string Attribute name.
     * @return int Attribute value.
     */
    public function getAttribute($attribute)
    {
        return $this->char->$attribute;
    }

    
    /** 
     * This function returns a skill value of the character.
     * Use the constants Character::CHAR_SKILL_* as input of this function.
     *
     * @param string Skill name.
     * @return int Skill value.
     */    
    public function getSkill($skill)
    {
        return $this->char->$skill;
    }
    
    
    /**
     * This functions is used to check wheter a character is member of at least
     * one guild.
     *
     * @return bool true, if the character is member of a guild, 
                    otherwise false
     */
    public function isGuildMember()
    {
        $query = $this->CI->db->get_where('tmw_guild_members', 
            array('member_name' => $this->char->name), 1);
            
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

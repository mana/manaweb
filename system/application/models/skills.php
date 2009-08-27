<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * The Model repesents a skillset like magic skills, crafts or weapons
 *
 * @ingroup models
 */
class Skillset
{
    /**
     * Unique name of the skillset
     */
    private $name;

    /**
     * List of skills that belong to the skillset.
     */
    private $skills;

    /**
     * Creates a new skillset object.
     *
     * @param name (String) Name of the skillset
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->skills = array();
    }

    /**
     * Adds a skill to this skillset.
     * @param (Skill) $skill The skill to add.
     */
    public function addSkill(Skill $skill)
    {
        $this->skills[$skill->getId()] = $skill;
    }

    /**
     * Returns the name of the skillset.
     * @return (String) Name of the skillset
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns a list of skills that belong to this skillset.
     * @return (Array) List of skills.
     */
    public function getSkills()
    {
        return $this->skills;
    }

} // class Skillset


/**
 * The Model repesents a specific skill grouped in a skillset
 *
 * @ingroup models
 */
class Skill
{
    /**
     * Unique id of the skill.
     */
    private $id;

    /**
     * Name of the skill.
     */
    private $name;

    /**
     * An optional icon representing the skill.
     */
    private $icon;

    /**
     * Creates a new skill object.
     *
     * @param id   (int)    Unique id of the skill
     * @param name (String) Name of the skill
     * @param
     */
    public function __construct($id, $name, $icon=null)
    {
        $this->id   = $id;
        $this->name = $name;
        $this->icon = $icon;
    }

    /**
     * Returns the unique id of the skill.
     * @return (int) Unique id of the skill.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the name of the skill.
     * @return (String) Name of the skill.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the name and path of the representing icon.
     * Could be null.
     * @return (String) Name and path of the icon or null if not present.
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Returns wheter the skill has a defined icon.
     * @return (Boolean) True, if a icon is defined, false otherwise
     */
    public function hasIcon()
    {
        return isset($this->icon);
    }

    /**
     * Sets the name and path of the representing icon.
     * @param (String) $icon Name and path of the icon.
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

} // class Skill

?>

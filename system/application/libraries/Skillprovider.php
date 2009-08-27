<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  The Mana World Account Manager
 *  Copyright 2009 The Mana World Development Team
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

// load dependencies
require_once(APPPATH.'models/skills'.EXT);

/**
 * The skillprovider is responsible for all actions according to skills in the
 * tmwserv module. It reads the XML_SKILLS_FILE file, and delivers informations about
 * skills to the user.
 *
 * @ingroup libraries
 */
class Skillprovider
{
    /**
     *  Defines the location and filename where to store the locally cached
     *  skills data.
     */
    const SKILLS_STORAGE = './data/skills.php.db';

    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;

    /**
     * Configured path and name of the XML_SKILLS_FILE file.
     */
    private $skills_file;

    /**
     * List of all available skillsets loaded from the XML_SKILLS_FILE or the serialized
     * data object.
     */
    private $skillsets;


    /**
     * Initialize a new instance of the Skillprovider.
     */
    function __construct()
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and
        // therefore we cannot access $this->config
        $this->CI =& get_instance();

        // initialize variables
        $this->skills_file = "";
        $this->skillsets = array();


        // check if the serialized data object is present
        if (file_exists(Skillprovider::SKILLS_STORAGE))
        {
            $this->skillsets = unserialize(
                file_get_contents(Skillprovider::SKILLS_STORAGE)
            );
        }
        else
        {
            // try to load XML_SKILLS_FILE file
            $this->loadSkillsFile();
        }
    } // __construct


    /**
     * This function returns all informations stored to the skillset with the given
     * name.
     * @param name (String) Name of the skillset
     * @return (Skillset) Skillset object
     */
    public function getSkillset($name)
    {
        if (isset($this->skillsets[$name]))
        {
            return $this->skillsets[$name];
        }
        else
        {
            show_error('A skillset with the name ' . $name . ' is unknown. Maybe you '.
                'have to reload the '. XML_SKILLS_FILE .' file');
        }
    }

    /**
     * Returns a list of all known skillsets
     * @return (Array) list of Skillsets
     */
    public function getSkillsets()
    {
        return $this->skillsets;
    }


    /**
     * This function tries to load and serialize the XML_SKILLS_FILE file.
     */
    public function loadSkillsFile()
    {
        log_message('debug', 'Reloading '. XML_SKILLS_FILE .' file from tmwserv');

        // load the configured path and filename from config file
        $this->skills_file = $this->CI->config->item('tmwdata_path') . XML_SKILLS_FILE;

        // check if the file really exists and is readable
        if (!file_exists($this->skills_file))
        {
            show_error('The '. XML_SKILLS_FILE .' file ' . $this->skills_file . ' configured'.
                ' in tmw_config.php cannot be found');
            return;
        }
        else
        {
            // reset current maps
            $this->skillsets = array();

            // load and parse the xml file
            $skillset = simplexml_load_file($this->skills_file);
            foreach ($skillset as $sks)
            {
                // loop through defined skillsets and skill and build internal array

                $ss = new Skillset(
                    strval($sks->attributes()->name)
                );

                foreach($sks as $skill)
                {
                    $s = new Skill(
                        intval($skill->attributes()->id), // id
                        strval($skill->attributes()->name) // name
                    );

                    // set icon if available
                    if (strlen(strval($skill->attributes()->icon)) > 0)
                    {
                        $s->setIcon(strval($skill->attributes()->icon));
                    }

                    $ss->addSkill($s);
                }

                $this->skillsets[$ss->getName()] = $ss;
            }

            $this->flushSkillsets();
        }
        log_message('debug', 'Reloading '. XML_SKILLS_FILE .' file ... done');
    } // function loadSkillsFile


    /**
     * This function returns the date and time of the last modification to the
     * local map cache as unix timestamp.
     *
     * @return (int) Time of the last modification as Unixtimestamp.
     */
    public function getSkillsCacheVersion()
    {
        return filemtime(Skillprovider::SKILLS_STORAGE);
    }


    /**
     * This function writes the memory structure stored in the private skills
     * variable to disk for faster access than reading XML_MAPS_FILE on each
     * request.
     */
    private function flushSkillsets()
    {
        $fp = fopen(Skillprovider::SKILLS_STORAGE, "w");
        fwrite($fp, serialize($this->skillsets));
        fclose($fp);
    }


} // class Skillprovider
?>

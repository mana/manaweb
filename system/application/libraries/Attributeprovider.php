<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  The Mana Server Account Manager
 *  Copyright 2009 The Mana Server Development Team
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

// load dependencies
require_once(APPPATH.'models/attributes'.EXT);

/**
 * The attributeprovider is responsible for all actions according to attributes in the
 * manaserv module. It reads the XML_ATTRIBUTES_FILE file, and delivers informations about
 * attributes to the user.
 *
 * @ingroup libraries
 */
class Attributeprovider
{
    /**
     *  Defines the location and filename where to store the locally cached
     *  attributes data.
     */
    const ATTRIBUTES_STORAGE = './data/attributes.php.db';

    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;

    /**
     * Configured path and name of the XML_ATTRIBUTES_FILE file.
     */
    private $attributes_file;

    /**
     * List of all available attributesets loaded from the XML_ATTRIBUTES_FILE or the serialized
     * data object.
     */
    private $attributesets;


    /**
     * Initialize a new instance of the Attributeprovider.
     */
    function __construct()
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and
        // therefore we cannot access $this->config
        $this->CI =& get_instance();

        // initialize variables
        $this->attributes_file = "";
        $this->attributesets = array();


        // check if the serialized data object is present
        if (file_exists(Attributeprovider::ATTRIBUTES_STORAGE))
        {
            $this->attributesets = unserialize(
                file_get_contents(Attributeprovider::ATTRIBUTES_STORAGE)
            );
        }
        else
        {
            // try to load XML_ATTRIBUTES_FILE file
            $this->loadAttributesFile();
        }
    } // __construct


    /**
     * This function returns all informations stored to the attributeset with the given
     * name.
     * @param name (String) Name of the attributeset
     * @return (Attributeset) Attributeset object
     */
    public function getAttributeset($name)
    {
        if (isset($this->attributesets[$name]))
        {
            return $this->attributesets[$name];
        }
        else
        {
            show_error('A attributeset with the name ' . $name . ' is unknown. Maybe you '.
                'have to reload the '. XML_ATTRIBUTES_FILE .' file');
        }
    }

    /**
     * Returns a list of all known attributesets
     * @return (Array) list of Attributesets
     */
    public function getAttributesets()
    {
        return $this->attributesets;
    }


    /**
     * This function tries to load and serialize the XML_ATTRIBUTES_FILE file.
     */
    public function loadAttributesFile()
    {
        log_message('debug', 'Reloading '. XML_ATTRIBUTES_FILE .' file from manaserv');

        // load the configured path and filename from config file
        $this->attributes_file = $this->CI->config->item('manadata_path') . XML_ATTRIBUTES_FILE;

        // check if the file really exists and is readable
        if (!file_exists($this->attributes_file))
        {
            show_error('The '. XML_ATTRIBUTES_FILE .' file ' . $this->attributes_file . ' configured'.
                ' in mana_config.php cannot be found');
            return;
        }
        else
        {
            // reset current maps
            $this->attributesets = array();

            // load and parse the xml file
            $attributeset = simplexml_load_file($this->attributes_file);
            foreach ($attributeset as $sks)
            {
                // loop through defined attributesets and attribute and build internal array

                $ss = new Attributeset(
                    strval($sks->attributes()->name)
                );

                foreach($sks as $attribute)
                {
                    $s = new Attribute(
                        intval($attribute->attributes()->id), // id
                        strval($attribute->attributes()->name) // name
                    );

                    $ss->addAttribute($s);
                }

                $this->attributesets[$ss->getName()] = $ss;
            }

            $this->flushAttributesets();
        }
        log_message('debug', 'Reloading '. XML_ATTRIBUTES_FILE .' file ... done');
    } // function loadAttributesFile


    /**
     * This function returns the date and time of the last modification to the
     * local map cache as unix timestamp.
     *
     * @return (int) Time of the last modification as Unixtimestamp.
     */
    public function getAttributesCacheVersion()
    {
        return filemtime(Attributeprovider::ATTRIBUTES_STORAGE);
    }


    /**
     * This function writes the memory structure stored in the private attributes
     * variable to disk for faster access than reading XML_ATTRIBUTES_FILE on each
     * request.
     */
    private function flushAttributesets()
    {
        $fp = fopen(Attributeprovider::ATTRIBUTES_STORAGE, "w");
        fwrite($fp, serialize($this->attributesets));
        fclose($fp);
    }


} // class Attributeprovider
?>

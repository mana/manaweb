<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

/**
 * The Model repesents a attributeset
 *
 * @ingroup models
 */
class Attributeset
{
    /**
     * Unique name of the attributeset
     */
    private $name;

    /**
     * List of attributes that belong to the attributeset.
     */
    private $attributes;

    /**
     * Creates a new attributeset object.
     *
     * @param name (String) Name of the attributeset
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->attributes = array();
    }

    /**
     * Adds a attribute to this attributeset.
     * @param (Attribute) $attribute The attribute to add.
     */
    public function addAttribute(Attribute $attribute)
    {
        $this->attributes[$attribute->getId()] = $attribute;
    }

    /**
     * Returns the name of the attributeset.
     * @return (String) Name of the attributeset
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns a list of attributes that belong to this attributeset.
     * @return (Array) List of attributes.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

} // class Attributeset


/**
 * The Model repesents a specific attribute grouped in a attributeset
 *
 * @ingroup models
 */
class Attribute
{
    /**
     * Unique id of the attribute.
     */
    private $id;

    /**
     * Name of the attribute.
     */
    private $name;

    /**
     * Creates a new attribute object.
     *
     * @param id   (int)    Unique id of the attribute
     * @param name (String) Name of the attribute
     * @param
     */
    public function __construct($id, $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    /**
     * Returns the unique id of the attribute.
     * @return (int) Unique id of the attribute.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the name of the attribute.
     * @return (String) Name of the attribute.
     */
    public function getName()
    {
        return $this->name;
    }
} // class Attribute

?>

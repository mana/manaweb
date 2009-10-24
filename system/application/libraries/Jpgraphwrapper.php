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
 * The JPGraph library is a wrapper for the famous jpgraph framework to use 
 * with CodeIgnoter. To avoid naming conflicts this class is called 
 * JPGraphWrapper.
 * 
 * @ingroup libraries
 */ 
class Jpgraphwrapper
{
    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;
    
    /**
     * Holds the absolute path to the jpgraph library, configured in mana_config
     */
    private $lib_path;
    
    
    /**
     * Initialize a new instance of the Jpgraphwrapper.
     */
    public function __construct()
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config
        $this->CI =& get_instance();
        
        // load the jpgprah library
        $this->lib_path = $this->CI->config->item('mana_plugins_jpgraph');
        require_once( $this->lib_path . 'jpgraph.php' );
    }
    

    /**
     * This function is used to load additional JpGraph library files.
     *
     * @param name (String) Name of the library to load
     */
    private function loadLib($name)
    {
        $filename = $this->lib_path . 'jpgraph_'.$name.'.php';            

        if (file_exists($filename))
        {
            require_once($filename);
        }
        else
        {
            show_error("The library file could not be found: ".$filename);
        }
    }    
    
    /**
     * This function initializes a PieGraph object with the given width and
     * height, sets the filename and a time how long the resulting graph 
     * should be cached.
     *
     * @param xwidth    (int) Width of the generated piechart
     * @param ywidth    (int) Height of the generated piechart
     * @param name      (String) Filename of the generated image
     * @param cachetime (int) duration of cached items
     * @return (Object) Returns a initialized pieGraph object from JpGraph 
     *         library
     */
    public function PieChart($xwidth, $ywidth, $name, $cachetime)
    {
        $this->loadLib('pie');
        $this->loadLib('pie3d');
        return new PieGraph($xwidth, $ywidth, $name, $cachetime, false); 
    }
    
} // class Jpgraphwrapper

?>

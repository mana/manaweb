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
 * The imageprovider is used to dye images and manage the image cache of dyed
 * images.
 * 
 * @ingroup libraries
 */ 
class Imageprovider
{
	
    /**
     * Reference to the CodeIgniter framework
     */
    private $CI;
    
    /**
     * Initialize a new instance of the Menuprovider.
     */
    function __construct()
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config
        $this->CI =& get_instance();
        
    } // __construct
    
    
    /** 
     * This function returns the URL to the image of the given item.
     * If the image is a dyed version of a grayscaled image, the function 
     * checks the image cache and if not present, dyes the image and caches the
     * result.
     *
     * @param itemid Unique id of the item, used to build the name of the cached image.
     * @param image  Filename of the original image.
     * @param dyestring String defining the colorization operation.
     * @return URL of the image the represents the item.
     */
    public function getItemImage($itemid, $image, $dyestring="")
    {
        $images_dir = $this->CI->config->item('tmwdata_path') . 'graphics/items/';

	    // the image has not to be dyed... just return the base image
	    if ($dyestring == "")
	    {
            return base_url() . $images_dir . $image;
	    }


        // TODO: reimplement support for dyed items
        return base_url() . $images_dir . $image;
        
	    // image dyeing is disabled, return the base image
	    if (!$this->CI->config->item('tmwserv_enable_dyecmd'))
	    {
		    return base_url() . $images_dir . $image;
	    }
	    
	    // the image has to be dyed, so check the cache
	    $cachename = $itemid . "_" . md5( $dyestring ) . ".png";
	    if (!file_exists("./images/items/" . $cachename))
	    {
			// dye image
			$this->DyeImage(
				"./images/items/" . $image, 
				"./images/items/" . $cachename, 
				$dyestring);
	    } 
	    
	    return base_url() . "images/items/" . $cachename;
    }
    
    /**
     * Converts a color defined as hexadecimal string into an array containing
     * rgb values.
     * This code is taken from Scott Ellis, developer of the Manabay.
     *
     * @param hexstr hexadecimal representation of a color.
     * @return Array with r, g, b values
     */
    private function HexToRgb($hexstr) 
    {
    	$int = hexdec($hexstr);
    	return array("r" => 0xFF & ($int >> 0x10), "g" => 0xFF & ($int >> 0x8), "b" => 0xFF & $int);
	}
	
	/**
	 * This function takes in input image, dyes it according the dye string and
	 * saves it at the target destination.
	 *
	 * @param source Input filename
	 * @param target Output filename
	 * @param dyestring String defining the dye
	 */
	private function DyeImage($source, $target, $dyestring)
	{
		$cmd = $this->CI->config->item('tmwserv_dyecmd') . 
			" \"$source\" \"$target\" \"$dyestring\"";
		exec( $cmd );
	}

} // class Mapprovider
?>

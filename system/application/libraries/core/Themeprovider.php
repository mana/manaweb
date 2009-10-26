<?php
/**
 *  The Mana Server Server
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
 *
 */

require_once(APPPATH.'models/core/Theme'.EXT);



class Themeprovider
{
    const THEMES_DIR = "data/themes";

    private $basedir;
    private $themeinfo;

    public function Themeprovider()
    {
        $this->basedir = ROOTPATH . "/" . Themeprovider::THEMES_DIR;
        $ci =& get_instance();
        $val = $ci->session->userdata('theme');
        if ($val == null)
        {
            $this->setTheme("default");
        }
        else
        {
            $this->setTheme($ci->session->userdata('theme'));
        }
    }

    public function existsTheme($name)
    {
        return file_exists($this->basedir . "/" . $name);
    }

    public function setTheme( $name )
    {
        $this->themeinfo = new ThemeInfo($this->basedir, $name);
    }

    public function getThemeInfo()
    {
        return $this->themeinfo;
    }

    public function getTheme()
    {
        return $this->themeinfo->getTheme();
    }

    public function getThemes($onlyValid = false)
    {
        $themelist = array();
        

        foreach (scandir($this->basedir) as $dirname)
        {
            if ($dirname == "." || $dirname == "..")
                continue;

            $themeinfo = new ThemeInfo( $this->basedir, $dirname );
            if ($themeinfo->isValid() || !$onlyValid)
                $themelist[] = $themeinfo;
        }
        return $themelist;
    }
}

?>
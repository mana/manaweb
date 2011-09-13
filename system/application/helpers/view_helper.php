<?php
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
 * This helper function generates a link "to the top" that enables the user to
 * jump to the beginning of each page.
 *
 * @return (String) HTML code to use in views to link to the top of a page.
 * @ingroup helpers
 */
function to_the_top()
{
    return "\n "
         . "<div style=\"text-align: right;\">\n"
         . "<a href=\"#top\">"
         . "<img src=\"".base_url()."images/go-up.png\" "
         . "border=\"0\" style=\"vertical-align: middle;\"> " . T_('go_up')
         . "</a>\n"
         . "</div>\n\n";
}

?>
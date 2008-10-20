<?php
/*
 *  The Mana World Server
 *  Copyright 2004 The Mana World Development Team
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
 *  $Id: $
 */

/*
|--------------------------------------------------------------------------
| Static Links for outer navigation menu
|--------------------------------------------------------------------------
|
| Define links and their description that should alway be visible in the
| menu. Define each link as array with the properties 'url' and 'name', 
| e.g. array( 'name'=>'News', 'url'=>site_url('home'))
| Your can use function site_url to link to a specific controller or one of
| its functions.
|
*/
$_tmw_static_links = array( 
   array( 'name'=>'News',        'url'=>'http://themanaworld.org'           ),
   array( 'name'=>'Wiki',        'url'=>'http://wiki.themanaworld.org'      ),
   array( 'name'=>'Forums',      'url'=>'http://forums.themanaworld.org/'   ),
   array( 'name'=>'Bug tracker', 'url'=>'http://mantis.themanaworld.org/'   ),
   array( 'name'=>'Statistics',  'url'=>site_url('statistics')              ),
   array( 'name'=>'Items',       'url'=>site_url('itemslist')               )
);

$config['tmw_static_links'] = $_tmw_static_links;


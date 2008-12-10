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
 */

/*
 * define some constants to identify the current running version of tmwweb
 */
define ('TMWWEB_VERSION', '1.0.0');
define ('TMWWEB_VERSION_DATE', '2008-12-01');

/*
|--------------------------------------------------------------------------
| Maps config file
|--------------------------------------------------------------------------
|
| tmwweb tries to read the configured maps.xml file shipped with tmwserv to
| show the current location of characters as human readable string.
|
*/
$config['tmwserv_maps.xml'] = '/path/to/your/tmwserv/installation/maps.xml'; 

/*
|--------------------------------------------------------------------------
| Items config file
|--------------------------------------------------------------------------
|
| TMWWeb tries to show the equipment and inventory graphics of characters.
| Therefore it needs to copy the item graphics into the local directory
| ./images/items directory if they don't exist yet.
| Therfore you have to configure the absolute path, where the images are 
| located. Normally, this is your tmwdata/trunk/graphics/items directory.
|
| Don't forget the trailing slash after the images directory!
|
*/
$config['tmwserv_items_images'] = '/path/to/your/tmwdata/trunk/graphics/items/'; 

/*
|--------------------------------------------------------------------------
| Image dyeing
|--------------------------------------------------------------------------
|
| Some images defined in the items.xml file are stored as greyscaled images
| to be able to colorize them in different colors. As the GD library shipped
| with PHP has some problems with non-true-color images, tmwweb needs an 
| external tool that dyes the images for tmwweb. You can find this tool 
| called dyecmd in version controll under tmw\trunk\tools\dyecmd.
|
*/
$config['tmwserv_enable_dyecmd'] = false;
$config['tmwserv_dyecmd']        = '/path/to/your/dyecmd/tool';

/*
|--------------------------------------------------------------------------
| Plugins and extensions
|--------------------------------------------------------------------------
|
| tmwweb uses some extensions or plugins that are not shipped together with
| the main distribution of CodeIgniter. All these extensions will be installed
| under ./ext directory. Normally you don't have to configure anything...
|
*/
$config['tmw_plugins_jpgraph'] = pathinfo( FCPATH, PATHINFO_DIRNAME ) .
                                 '/ext/jpgraph-2.3.3/src/';                

/*
|--------------------------------------------------------------------------
| Available languages
|--------------------------------------------------------------------------
|
| Provide a list with all available languages. The user can choose during 
| login which language he prefers. The translations should be added under
| system/application/language
|
*/

$_tmw_languages = array( 
    array('dir'=>'english', 'name'=>'english'),
    array('dir'=>'german', 'name'=>'deutsch')
);

$config['tmw_languages'] = $_tmw_languages; 
 

/*
|--------------------------------------------------------------------------
| Enable or disable different options of the webinterface
|--------------------------------------------------------------------------
*/


/* 
| Set this option to true, if users should be able to delete one of their
| character via the webinterface. Set this to false, if this option should be 
| disabled. The only way to delete a character in this case is to use the 
| client.
|
| possible values: true | false
*/
$config['tmw_allow_user_delete_character'] = true;

/* 
| Set this option to true, to enable the internal profiler shipped with 
| CodeIgniter. This should be disabled in production!!!
|
| possible values: true | false
*/
$config['tmw_enable_profiler'] = false;




/*
|--------------------------------------------------------------------------
| Ranges to display user levels
|--------------------------------------------------------------------------
|
| The table tmw_accounts has a level column, that shows the priviledged level
| of a user. To visualize the level of a user in the management area, there
| should be names for each levels or ranges of levels.
|
| A normal player would have permissions of 1
| A tester would have permissions of 3 (AL_PLAYER | AL_TESTER)
| A dev would have permissions of 7 (AL_PLAYER | AL_TESTER | AL_DEV)
| A gm would have permissions of 11 (AL_PLAYER | AL_TESTER | AL_GM)
| A admin would have permissions of 255 (*)
|
| see tmwserv/src/defines.h
|    AL_BANNED =   0,     < This user is currently banned.
|    AL_PLAYER =   1,     < User has regular rights.
|    AL_TESTER =   2,     < User can perform testing tasks.
|    AL_DEV    =   4,     < User is a developer and can perform dev tasks
|    AL_GM     =   8,     < User is a moderator and can perform mod tasks
|    AL_ADMIN  =  128     < User can perform administrator tasks.
|
*/
define('AL_BANNED',   0);
define('AL_PLAYER',   1);
define('AL_TESTER',   2);
define('AL_DEV',      4);
define('AL_GM',       8);
define('AL_ADMIN',  128 );

$_tmw_levels = array( 
    array( 'byte'=> AL_BANNED, 'name'=>'banned User'   ),
    array( 'byte'=> AL_PLAYER, 'name'=>'Player'        ),
    array( 'byte'=> AL_TESTER, 'name'=>'Tester'        ),
    array( 'byte'=> AL_DEV,    'name'=>'Developer'     ),
    array( 'byte'=> AL_GM,     'name'=>'GM'            ),
    array( 'byte'=> AL_ADMIN,  'name'=>'Administrator' )
);

$config['tmw_account_levels'] = $_tmw_levels;


/*
|--------------------------------------------------------------------------
| Permissions and securtity
|--------------------------------------------------------------------------
|
| This sections defines some rights and permissions and the minimum level
| a user needs to execute or even see the part of the admin interface.
|
*/

/* 
| "tmwadmin_level" defines the minimum level to have access to the tmwweb admin
| interface. It just enables the link in the menu and gives no other rights.
*/
$config['tmwweb_admin_permissions'] = array(

    // needed level to see a list of all accounts
    'see_account_list'          => array( 'group' => AL_ADMIN ),
    
    // needed level to see a list of all characters
    'see_character_list'        => array( 'group' => AL_ADMIN ),
    
    // needed level to reset a password of an account
    'reset_account_password'    => array( 'group' => AL_ADMIN ),
    
    // needed level to ban an account for a given time
    'ban_account'               => array( 'group' => AL_ADMIN ),
    
    // needed level to unban an account for a given time
    'unban_account'             => array( 'group' => AL_ADMIN ),
    
    // needed level to modify the level of an account
    'modify_account_level'      => array( 'group' => AL_ADMIN ),
    
    // needed level to delete an account
    'delete_account'            => array( 'group' => AL_ADMIN )
);


/*
|--------------------------------------------------------------------------
| Configuration for password change 
|--------------------------------------------------------------------------
| 
| When a user losts his password and requests to change it, we will send him a 
| mail with a link where he can change it. The link should point to your tmwweb
| installation. The url should contain two paramters in sprintf syntax:
|   %s (string) the username
|   %s (string) a generic key stored in the database to validate the mailbox
|
*/
$config['tmw_change_password_link'] = "http://example.com/index.php". 
    "/myaccount/changepassword/%s/%s";

    
/*
| Define the subject of the mail that should be sent to the user.
*/    
$config['tmw_change_password_subject'] = "TMW: Your password change request";

        
/*
| Define the mailaddress from which mails are sent to the user.
| e.g. something like "noreply@themanaworld.org"
*/    
$config['tmw_email_from_address'] = "noreply@example.com";

/*
| Define the visible name of the address configured above.
*/
$config['tmw_email_from_name'] = "Account Manager";

?>

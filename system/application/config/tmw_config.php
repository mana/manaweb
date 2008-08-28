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
 *
 *  $Id$
 */

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
| Each range should be defined as array with a minimium value and a description
| of the level. The given minimum level is included in the range.
|
| All ranges should be defined from smallest to highest!
|
| see tmwserv/src/defines.h
| enum
| {
|     AL_BANNED =  0,     < This user is currently banned.
|     AL_NORMAL = 10,     < User has regular rights.
|     AL_GM     = 50,     < User can perform a subset of administrator tasks.
|     AL_ADMIN  = 99      < User can perform administrator tasks.
| };
|
*/
$_tmw_levels = array( 
    array( 'min'=>  0, 'name'=>'banned User'   ),
    array( 'min'=> 10, 'name'=>'User'          ),
    array( 'min'=> 50, 'name'=>'GM'            ),
    array( 'min'=> 99, 'name'=>'Administrator' )
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
$config['tmwweb_admin_level'] = 50;

$config['tmwweb_admin_permissions'] = array(

    // needed level to see a list of all accounts
    'see_account_list'          => array( 'level' => 50 ),
    
    // needed level to see a list of all characters
    'see_character_list'        => array( 'level' => 50 ),
    
    // needed level to reset a password of an account
    'reset_account_password'    => array( 'level' => 50 ),
    
    // needed level to ban an account for a given time
    'ban_account'               => array( 'level' => 50 ),
    
    // needed level to unban an account for a given time
    'unban_account'             => array( 'level' => 50 ),
    
    // needed level to modify the level of an account
    'modify_account_level'      => array( 'level' => 99 ),
    
    // needed level to delete an account
    'delete_account'            => array( 'level' => 99 )
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

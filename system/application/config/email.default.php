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
 *  $Id$
 */

/*
|
| This is a custom email configuration script. It is loaded by the CodeIgniter
| library 'email' automatically when trying to send mails. 
| see http://codeigniter.com/user_guide/libraries/email.html for further 
| details.
|
| The following is a list of all the preferences that can be set when 
| sending email.
*/


/*
| The "user agent" in the mail header
| default = "CodeIgniter"
*/
$config['useragent'] = 'CodeIgniter';

/*
| The mail sending protocol
| possible values are mail, sendmail, or smtp
*/
$config['protocol'] = 'sendmail';

/*
| The server path to sendmail, if protocol = "sendmail"
*/
$config['mailpath'] = '/usr/bin/sendmail';

/*
| SMTP Server Address, username, port and password, if protocol = "smtp"
*/
//$config['smtp_host'] = '';
// $config['smtp_user'] = '';
// $config['smtp_pass'] = '';
// $config['smtp_port'] = 25;
//$config['smtp_timeout'] = 5; // in seconds

/*
| Enable word-wrap and character count to wrap at.
*/
$config['wordwrap'] = true;
$config['wrapchars'] = 76;

/*
| Type of mail. If you send HTML email you must send it as a complete web page. 
| Make sure you don't have any relative links or relative image paths otherwise 
| they will not work.
| possible values text or html
*/
$config['mailtype'] = 'text';

/*
| Character set (utf-8, iso-8859-1, etc.).
*/
$config['charset'] = 'utf-8';

/*
| Whether to validate the email address.
*/
$config['validate'] = false;

/*
| Email Priority. 1 = highest. 5 = lowest. 3 = normal.
*/
$config['priority'] = 3;

/*
| Newline character. (Use "\r\n" to comply with RFC 822).
*/
$config['crlf']    = "\r\n";
$config['newline'] = "\r\n";

/*
| Enable BCC Batch Mode and number of emails in each BCC batch.
*/
$config['bcc_batch_mode']  = false;
$config['bcc_batch_size']  = 200;


/* end of email.php */
?>
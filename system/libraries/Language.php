<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2010, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Language Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Language
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/language.html
 */
class CI_Language {      
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function CI_Language()
	{               
            //grab the config file
            @include(APPPATH.'config/config'.EXT);

            log_message('debug', "Language Class Initialized");

            //Init Gettext
            $locale = $config['language'];   // set language to default value
            $domain = 'default'; // set gettext domain
            $encoding = 'UTF-8'; // set character encoding

            //include gettext
            require_once('./ext/php-gettext/gettext.inc');

            //set locale
            T_setlocale(LC_MESSAGES, $locale);

            //set locale path
            T_bindtextdomain($domain, './locale/');

            //set character encoding
            T_bind_textdomain_codeset($domain, $encoding);

            //set domain
            T_textdomain($domain);
	}
}
// END Language Class

/* End of file Language.php */
/* Location: ./system/libraries/Language.php */
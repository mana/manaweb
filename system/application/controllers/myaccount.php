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


/**
 * The myaccount controller is responsible for account management like 
 * login, logout, regathering lost passwords and so on. Most of its functions 
 * can be called when not logged in.
 */ 
class Myaccount extends Controller {

    /**
     * Array to store data needed by the header view.
     */
    private $header_data = Array();
    
    
    /**
     * Initializes the Home controller.
     */
    function __construct()
    {
        parent::Controller();
        $this->load->library('validation');
        $this->load->helper('form');
        $this->header_data['static_menu'] = 
            $this->menuprovider->getStaticMenu();        
    }
    
    
    /** 
     * Default controller function. Shows either the login screen or the
     * homepage of the user account, depending of the login status of the 
     * current user.
     */
    function index()
    {
        // check if the user is currently logged in
        if ($this->user->isAuthenticated())
        {
            $this->_show_user_account();
        }
        else
        {
            $params = array( 'has_errors' => false ); 
            $this->showPage( 'Account Manager', 'tmwweb/login_form', $params);
        }
    }
    
    
    /**
     * Function when user tries to login via loginform
     */
    function login()
    {
        // define rules that must be met in the login form
        $rules['TMWusername']  = "required|min_length[4]|max_length[30]";
        $rules['TMWpassword']  = "required|min_length[4]|max_length[30]";
        $this->validation->set_rules($rules);
        
        // validate userinput against rules
        if ($this->validation->run() == false)
        {
            $params = array( 'has_errors' => true ); 
            $this->showPage( 'Account Manager', 'tmwweb/login_form', $params);
        }
        else
        {
            // validation passed, now check credentials
            // get username and password from post variables
            $user = $this->input->post('TMWusername');
            $pwd  = $this->input->post('TMWpassword');
            
            // try to authenticate user with membership provider
            $res = $this->user->authenticate($user, $pwd);
            
            if( $res === false )
            {
                $params = array( 'has_errors' => true );                
                $this->showPage( 'Account Manager', 'tmwweb/login_form', $params);
            }
            else
            {
                $this->_show_user_account();
            }
        }
    }
    
    
    /**
     * This function is called from user menu, if the user wants to logout
     * from the account manager
     */
    function logout()
    {
        $this->user->logout();
        $params = array( 'has_errors' => false ); 
        $this->showPage( 'Account Manager', 'tmwweb/login_form', $params);
    }
    
    
    /** 
     * This function is called by the view if the users clicks the lost 
     * password link. The controller then shows the view the lostpassword
     * form. 
     */
    function lostpassword()
    {
        $this->showPage('Account Manager', 'tmwweb/lost_password');
    }
    
    
    /** 
     * This function is called by the view tmwweb/lost_password if the user
     * requests to change his password. The function validates the combination 
     * of username and mailaddress and sends a generic random key to the user.
     * With this link he can change his password.
     */
    function resetpassword()
    {
        // define rules that must be met in the login form
        $rules['TMWusername'] = "required|min_length[4]|max_length[30]";
        $rules['TMWMail'] = "required|valid_email";
        $this->validation->set_rules($rules);
        
        // validate userinput against rules
        if ($this->validation->run() == false)
        {
            $params = array('has_errors'=>true); 
            $this->showPage('Account Manager', 'tmwweb/lost_password', $params);
        }
        else
        {
            // simple checks are ok, so extend the rules and recheck
            $rules['TMWusername'] = "callback_username_check"; 
            $this->validation->set_rules($rules);
            
            // validate again userinput against rules
            if ($this->validation->run() == false)
            {
                $params = array('has_errors'=>true); 
                $this->showPage('Account Manager', 'tmwweb/lost_password', 
                    $params);
            }
            else
            {
                $username = $this->input->post('TMWusername');
                $email = $this->input->post('TMWMail');
                
                // generate a key, store the key, send it to the user
                $this->_send_passwort_change_request($username, $email);
                    
                // forward to the success page
                $this->showPage('Account Manager', 
                    'tmwweb/lost_password_mailsent',
                    array('username'=>$username, 'email'=>$email));
            }
        }        
    }
    
    
    /**
     * This function is called by users that want to change their password
     * given a sevret identifier via mail. This key has to match the key in 
     * the database.    
     * 
     * @param String Username of the user who wants to change his password
     * @param String Secret key sent to the user via email
     */
    function changepassword($username, $key)
    {
        // check if a combination of username and key exist in db
        if ($this->membershipprovider->validateKeyForUser($username, $key))
        {
            // show view for changing password
            $this->showPage('Account Manager',
                'tmwweb/lost_password_change',
                array('username'=>$username, 'key'=>$key));
        }
        else
        {
            // show error page
            $this->showPage('Account Manager', 
                'tmwweb/lost_password_wrong_key');
        }
    }
    
    
    /** 
     * This function is called from the view lost_password_change and should
     * set a new password for the given user.
     */
    public function setnewpassword()
    {
        $username = $this->input->post('TMWUsername');
        $key = $this->input->post('TMWActivationKey');
        
        // check if a combination of username and key exist in db,
        // may happen if the user fakes the form/hidden fields
        if ($this->membershipprovider->validateKeyForUser($username, $key))
        {
            // define rules for the new password
            $rules['TMWpassword'] = "required|callback_password_strength";
            $rules['TMWpassword2'] = "required|matches[TMWpassword]";
            $rules['PasswordStrength'] = "";
            $this->validation->set_rules($rules);
            
            // validate again userinput against rules
            if ($this->validation->run() == false)
            {
                // validation fails, prepare params for change form
                $params = array(
                    'has_errors'=>true,
                    'username'=>$username,
                    'key'=>$key
                ); 
                
                $this->showPage('Account Manager', 
                    'tmwweb/lost_password_change', 
                    $params);
            }
            else
            {
                // the new password is ok. Set it, delete the key and forward
                // the user to the login form
                $this->membershipprovider->setPasswordForUser(
                    $username, 
                    $this->input->post('TMWpassword') );
                    
                $this->showPage('Account Manager', 
                    'tmwweb/login_form',
                    array('message'=>'Your new password has been set. You can'.
                    ' now login with your new credentials.' ));
            }
        }
        else
        {
            // show error page
            $this->showPage('Account Manager', 
                'tmwweb/lost_password_wrong_key');
        }
    }
    
    
    /** 
     * This is a callback function to validate the user given password against
     * password policy.
     *
     * @param String Password to validate
     * @returns boolean true, if the password fulfills the policy, otherwise 
     *                  false.
     */
    public function password_strength($pwd)
    {
        $username = $this->input->post('TMWUsername');
        $ret = Membershipprovider::validatePassword($pwd, $username);
        switch($ret)
        {
            case Membershipprovider::PASSWORD_OK:
                return true;
            case Membershipprovider::PASSWORD_TO_SHORT:
                $this->validation->set_message('password_strength', 
                'The given password is to short.');
                return false;
            case Membershipprovider::PASSWORD_TO_LONG:
                $this->validation->set_message('password_strength', 
                'The given password is to long.');
                return false;
            case Membershipprovider::PASSWORD_SIMILAR_TO_USERNAME:
                $this->validation->set_message('password_strength', 
                'The password must be different then your username.');
                return false;
        }
    }
    
    
    /** 
     * This is a callback function, called when the validation provider tries
     * to check the combination of username and mailaddress when requesting a
     * new password. The username is provided by the validation provider as
     * parameter. The mailaddress has to come from the input handler.
     *
     * @param String Username to validate
     * @returns boolean true, if the combination is valid, false otherwise
     */
    public function username_check($username)
    {
        // get mail from post and hash it 
        $mail  = $this->input->post('TMWMail');
        $hmail = hash('sha256', $mail);
        
        // select user from db with given name and mailaddress
        $query = $this->db->get_where('tmw_accounts', 
            array( 'username'=>$username, 'email'=>$hmail ));

        if ($query->num_rows == 1)
        {
            return true;
        }
        else
        {
            $this->validation->set_message('username_check', 
                'A user with this mailaddress could not be found.');
            return false;
        }
    } 
    
    
    /**
     * 
     */
    private function _send_passwort_change_request($username, $mailaddress)
    {
        // generate a unique key
        $key = Membershipprovider::getRandomHashKey( 24 );
        
        // build the link in the mail
        $pwdlink = sprintf( $this->config->item('tmw_change_password_link'),
            $username, $key );

        // load the template parsing library and parse the mail template
        $this->load->library('parser');
            
        $tpl = $this->parser->parse('mailtemplates/request_pwd_change',
            array('username'=>$username, 'password_link'=>$pwdlink),
            true ); // the third parameter true is used to return the template
            // and not to stream it into the output
            
        // load the email library and configure it for usage
        $this->load->library('email');
        $this->email->from($this->config->item('tmw_email_from_address'),
            $this->config->item('tmw_email_from_name'));
        
        $this->email->to($mailaddress); 
        $this->email->subject($this->config->item('tmw_change_password_subject'));
        $this->email->message($tpl); 
        
        // now store the key in the database related to the user
        $this->membershipprovider->setKeyForUser( $username, $key );
     
        // todo: comment this out in production
        // $this->email->send();
        
        // log the sending...
        // todo: comment this out in production
        // log_message( 'info', $this->email->print_debugger());
    }
    
    
    
    /**
     * This private function is used to show the account page of the user.
     */    
    private function _show_user_account()
    {
        $this->showPage( 'My Account', 'tmwweb/user_home', 
            $this->user->getHomepageData() );
    }
   
    
    /**
     * Use this function to show a view with the given parameters
     */
    private function showPage( $title, $filename, $params=array() )
    {
        $this->header_data['page_title'] = $title;
        $this->header_data['user_menu'] = $this->menuprovider->getUserMenu();
        $this->load->view('layout/header', $this->header_data);
        $this->load->view($filename, $params);
        $this->load->view('layout/footer');
    }
    
    
}
?>
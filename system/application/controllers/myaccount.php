<?php
/*
 *  The Mana Server Account Manager
 *  Copyright 2009 The Mana Project Development Team
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


/**
 * The myaccount controller is responsible for account management like
 * login, logout, regathering lost passwords and so on. Most of its functions
 * can be called when not logged in.
 *
 * @ingroup controllers
 */
class Myaccount extends Controller {

    /**
     * Initializes the Home controller.
     */
    function __construct()
    {
        parent::Controller();
        $this->output->enable_profiler(
            $this->config->item('mana_enable_profiler')
        );

        $this->load->library('validation');
        $this->load->helper('form');
        $this->translationprovider->loadLanguage('account');
    }


    /**
     * Default controller function. Shows either the login screen or the
     * homepage of the user account, depending of the login status of the
     * current user.
     */
    public function index()
    {
        // check if the user is currently logged in
        if ($this->user->isAuthenticated())
        {
            $this->_show_user_account();
        }
        else
        {
            $param = array('has_errors' => false);
            $this->output->showPage(lang('manaweb_title'),
                'manaweb/login_form', $param);
        }
    }


    /**
     * Function is called when the user tries to login via loginform.
     */
    public function login()
    {
        // if the user is still logged in, forward him to his main site
        if ($this->user->isAuthenticated())
        {
            $this->_show_user_account();
            return;
        }

        // define rules that must be met in the login form
        $rules['Manausername']  = "required|min_length[4]|max_length[30]";
        $rules['Manapassword']  = "required|min_length[4]|max_length[30]";
        $this->validation->set_rules($rules);

        // validate userinput against rules
        if ($this->validation->run() == false)
        {
            $param = array('has_errors' => true);
            $this->output->showPage(lang('manaweb_title'),
                'manaweb/login_form', $param);
        }
        else
        {
            // validation passed, now check credentials
            // get username and password from post variables
            $user = $this->input->post('Manausername');
            $pwd  = $this->input->post('Manapassword');
            $lang = $this->input->post('Manalanguage');

            // try to authenticate user with membership provider
            $res = $this->user->authenticate($user, $pwd);

            if( $res === false )
            {
                $this->validation->error_string =
                    'The given username or password is incorrect.';
                $params = array('has_errors' => true);
                $this->output->showPage(lang('manaweb_title'),
                    'manaweb/login_form', $params);
                return;
            }
            else
            {
                // set language preferences
                $this->translationprovider->setLanguage($lang);
                // show the account homepage of the user
                $this->themeprovider->setTheme( $this->input->post('Manastyle'));
                $this->session->set_userdata('theme', $this->input->post('Manastyle'));

                $this->_show_user_account();


            } // $res == true

        } // validation failed

    } // funtion login()


    /**
     * This function is called from user menu, if the user wants to logout
     * from the account manager
     */
    public function logout()
    {
        $this->user->logout();
        $params = array('has_errors' => false);
        $this->output->showPage(lang('manaweb_title'),
            'manaweb/login_form', $params);
    }


    /**
     * This function is called by the view if the users clicks the lost
     * password link. The controller then shows the view the lostpassword
     * form.
     */
    public function lostpassword()
    {
        $params = array('has_errors' => false);
        $this->output->showPage(lang('manaweb_title'),
            'manaweb/lost_password', $params);
    }


    /**
     * This function is called by the view manaweb/lost_password if the user
     * requests to change his password. The function validates the combination
     * of username and mailaddress and sends a generic random key to the user.
     * With this link he can change his password.
     */
    public function resetpassword()
    {
        // define rules that must be met in the login form
        $rules['Manausername'] = "required|min_length[4]|max_length[30]";
        $rules['ManaMail'] = "required|valid_email";
        $this->validation->set_rules($rules);

        // validate userinput against rules
        if ($this->validation->run() == false)
        {
            $params = array('has_errors'=>true);
            $this->output->showPage(lang('manaweb_title'),
                'manaweb/lost_password', $params);
        }
        else
        {
            // simple checks are ok, so extend the rules and recheck
            $rules['Manausername'] = "callback__username_check";
            $this->validation->set_rules($rules);

            // validate again userinput against rules
            if ($this->validation->run() == false)
            {
                $params = array('has_errors'=>true);
                $this->output->showPage(lang('manaweb_title'),
                    'manaweb/lost_password', $params);
            }
            else
            {
                $username = $this->input->post('Manausername');
                $email = $this->input->post('ManaMail');

                // generate a key, store the key, send it to the user
                $this->_send_passwort_change_request($username, $email);

                // forward to the success page
                $this->output->showPage(lang('manaweb_title'),
                    'manaweb/lost_password_mailsent',
                    array('username'=>$username, 'email'=>$email));
            }
        }
    }


    /**
     * This function is called by users that want to change their password
     * given a secret identifier via mail. This key has to match the key in
     * the database.
     *
     * @param username (String) Username of the user who wants to change
     *                 his password
     * @param key      (String) Secret key sent to the user via email
     */
    public function changepassword($username = null, $key = null)
    {
        // check if a combination of username and key exist in db
        if (isset($username) && isset($key) &&
            $this->membershipprovider->validateKeyForUser($username, $key))
        {
            // show view for changing password
            $this->output->showPage(lang('manaweb_title'),
                'manaweb/lost_password_change',
                array('username'=>$username, 'key'=>$key,
                    'has_errors'=>false));
        }
        else
        {
            // show error page
            $this->output->showPage(lang('manaweb_title'),
                'manaweb/lost_password_wrong_key');
        }
    }


    /**
     * This function is called from the view lost_password_change and should
     * set a new password for the given user.
     */
    public function setnewpassword()
    {
        $username = $this->input->post('ManaUsername');
        $key = $this->input->post('ManaActivationKey');

        // check if a combination of username and key exist in db,
        // may happen if the user fakes the form/hidden fields
        if ($this->membershipprovider->validateKeyForUser($username, $key))
        {
            // define rules for the new password
            $rules['Manapassword'] = "required|callback__password_strength";
            $rules['Manapassword2'] = "required|matches[Manapassword]";
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

                $this->output->showPage(lang('manaweb_title'),
                    'manaweb/lost_password_change', $params);
            }
            else
            {
                // the new password is ok. Set it, delete the key and forward
                // the user to the login form
                $this->membershipprovider->setPasswordForUser(
                    $username,
                    $this->input->post('Manapassword'));

                $this->output->showPage(lang('manaweb_title'),
                    'manaweb/login_form',
                    array('message'=>'Your new password has been set. You can'.
                    ' now login with your new credentials.',
                    'has_errors'=>false ));
            }
        }
        else
        {
            // show error page
            $this->output->showPage(lang('manaweb_title'),
                'manaweb/lost_password_wrong_key');
        }
    }


    /**
     * This is a callback function to validate the user given password against
     * password policy.
     *
     * @param pwd (String) Password to validate
     * @return (Bool) \c true, if the password fulfills the policy, otherwise
     *                \c false.
     */
    public function _password_strength($pwd)
    {
        $username = $this->input->post('ManaUsername');
        $ret = Membershipprovider::validatePassword($pwd, $username);
        switch($ret)
        {
            case Membershipprovider::PASSWORD_OK:
                return true;
            case Membershipprovider::PASSWORD_TO_SHORT:
                $this->validation->set_message('_password_strength',
                'The given password is to short.');
                return false;
            case Membershipprovider::PASSWORD_TO_LONG:
                $this->validation->set_message('_password_strength',
                'The given password is to long.');
                return false;
            case Membershipprovider::PASSWORD_SIMILAR_TO_USERNAME:
                $this->validation->set_message('_password_strength',
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
     * @param username (String) Username to validate
     * @return (Bool) \c true, if the combination is valid, otherwise \c false
     */
    public function _username_check($username)
    {
        // get mail from post and hash it
        $mail  = $this->input->post('ManaMail');
        $hmail = hash('sha256', $mail);

        // TODO: use constant for database table name

        // select user from db with given name and mailaddress
        $query = $this->db->get_where('mana_accounts',
            array( 'username'=>$username, 'email'=>$hmail ));

        if ($query->num_rows == 1)
        {
            return true;
        }
        else
        {
            $this->validation->set_message('_username_check',
                'A user with this mailaddress could not be found.');
            return false;
        }
    }


    /**
     * This function generates a unique hash and sends it via mail to the user.
     * The has is also stored in the mana_accounts table to verify it later.
     *
     * @param username    (String) User that wants to reset his password
     * @param mailaddress (String) Mailaddress we should send the mail to
     */
    private function _send_passwort_change_request($username, $mailaddress)
    {
        // generate a unique key
        $key = Membershipprovider::getRandomHashKey( 24 );

        // build the link in the mail
        $pwdlink = sprintf( $this->config->item('mana_change_password_link'),
            $username, $key );

        // load the template parsing library and parse the mail template
        $this->load->library('parser');

        $tpl = $this->parser->parse('mailtemplates/request_pwd_change',
            array('username'=>$username, 'password_link'=>$pwdlink),
            true ); // the third parameter true is used to return the template
            // and not to stream it into the output

        // load the email library and configure it for usage
        $this->load->library('email');
        $this->email->from($this->config->item('mana_email_from_address'),
            $this->config->item('mana_email_from_name'));

        $this->email->to($mailaddress);
        $this->email->subject($this->config->item('mana_change_password_subject'));
        $this->email->message($tpl);

        // now store the key in the database related to the user
        $this->membershipprovider->setKeyForUser( $username, $key );

        // todo: comment this out in production
        $this->email->send();

        // log the sending...
        // todo: comment this out in production
        // log_message( 'info', $this->email->print_debugger());
    }



    /**
     * This private function is used to show the account page of the user.
     */
    private function _show_user_account()
    {
        $this->translationprovider->loadLanguage('account');
        $this->output->showPage(lang('account_title'), 'manaweb/user_home',
            $this->user->getHomepageData() );
    }

} // class myaccount
?>

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
 * The accountmanager controller is responsible for all actions a user can do
 * with its account, beside the functions located in the myaccount controller.
 * Each functions in the accountmanager need an authorized user, so the
 * authentication is just done in the constructor, rather in every single
 * function.
 *
 * @ingroup controllers
 */
class Accountmanager extends Controller {

    /**
     * Initializes the Home controller.
     */
    function __construct()
    {
        parent::Controller();
        $this->output->enable_profiler(
            $this->config->item('mana_enable_profiler')
        );

        $this->load->library('form_validation');
        $this->load->helper('form');

        // check if the user is currently logged in
        if (!$this->user->isAuthenticated())
        {
            $param = array('has_errors' => false);
            $this->translationprovider->loadLanguage('account');
            $this->output->showPage(lang('account_login'),
                'manaweb/login_form', $param);
        }
    }


    /**
     * Default controller function. Shows either the login screen or the
     * homepage of the user account, depending of the login status of the
     * current user.
     */
    public function index()
    {
        if (!$this->user->isAuthenticated())
        {
            return;
        }

        $this->_show_user_account();
    }


    /**
     * This function is called from the user menu if the users requests to
     * show his account settings.
     */
    public function settings()
    {
        if (!$this->user->isAuthenticated())
        {
            return;
        }

        $this->translationprovider->loadLanguage('settings');
        $params = array('has_errors' => false);
        $this->output->showPage(lang('settings_title'),
            'manaweb/settings', $params);
    }

    /**
     * This function is used to show a form or view with the given name.
     * @param (String) $name  Name of the view.
     */
    public function showForm($name)
    {
        if (!$this->user->isAuthenticated())
        {
            return;
        }

        $this->translationprovider->loadLanguage('settings');
        switch ($name)
        {
            case "ChangeMailaddress":
                $this->output->showPage(lang('settings_change_mail_head'),
                    'manaweb/accountmanager/change_mailaddress_form' );
                break;
        }
    }


    /**
     * This function is called from the settings view if the user requests to
     * to delete its account. The function shows a view where the user has to
     * validate its request to delete everything.
     */
    public function delete_account()
    {
        if (!$this->user->isAuthenticated())
        {
            return;
        }

        $this->translationprovider->loadLanguage('settings');
        $this->output->showPage(lang('settings_title'),
            'manaweb/delete_account');
    }


    /**
     * This function is called from the delete_account view if the user decides
     * wheter or not to delete its account. If he pressed the cancel button we
     * go back to the settings view. Otherwise we will delete his account and
     * present a success view called delete_account_done.
     */
    public function execute_delete()
    {
        if (!$this->user->isAuthenticated())
        {
            return;
        }

        if (strlen($this->input->post('Manacancel')) > 0)
        {
            // don`t delete account
            $this->settings();
            return;
        }

        $this->user->deleteCurrentUser();

        $this->translationprovider->loadLanguage('settings');
        $this->output->showPage(lang('settings_title'),
            'manaweb/delete_account_done');
    }


    /**
     * This function is called from the view settings and should
     * set a new password for the given user.
     */
    public function changepassword()
    {
        if (!$this->user->isAuthenticated())
        {
            return;
        }

        $old_pwd  = $this->input->post('Mana_old_password');
        $new_pwd  = $this->input->post('Mana_new_password');
        $new2_pwd = $this->input->post('Mana_retype_password');

        // define rules for the new password
        // you may wonder why those validation function have 2 underscores "__"
        // in it. This is due to the fact, that callback functions have to
        // start with "callback_" and to make the function itself private for
        // CI, the function has to start with an underscore.

        $this->form_validation->set_rules('Mana_old_password',
            'lang:settings_old_password', "required|callback__validate_password" );

        $this->form_validation->set_rules('Mana_new_password',
            lang('settings_new_password'), "required|callback__password_strength" );

        $this->form_validation->set_rules('Mana_retype_password',
            lang('settings_retype_password'), "required|matches[Mana_new_password]" );

        $this->translationprovider->loadLanguage('settings');

        // validate userinput against rules
        if ($this->form_validation->run() == false)
        {
            // validation fails, prepare params for change form
            $param = array('has_errors' => true);
            $this->output->showPage(lang('settings_title'),
                'manaweb/settings', $param);
        }
        else
        {
            // the new password is ok.
            $this->membershipprovider->setPasswordForUser(
                $this->user->getUser()->getUsername(),
                $this->input->post('Mana_new_password'),
                false );

            $param = array(
                'has_errors' => false,
                'pwd_changed_message' => lang('settings_change_password_ok')
            );
            $this->output->showPage(lang('settings_title'),
                'manaweb/settings', $param);
        }
    }

    /**
     * This function is called from the view settings and should set a new
     * mailaddress for the current user.
     */
    public function changemailaddress()
    {
        if (!$this->user->isAuthenticated())
        {
            return;
        }
        $this->translationprovider->loadLanguage('settings');
        $params = array();

        $rules['Mana_new_mailaddress']    = "required";
        $rules['Mana_retype_mailaddress'] = "required";

        $this->form_validation->set_rules('Mana_current_password',
            'lang:settings_current_password' ,
            'required|callback__validate_password' );

        $this->form_validation->set_rules('Mana_new_mailaddress',
            lang('settings_new_mailaddress') ,
            'required|valid_email' );

        $this->form_validation->set_rules('Mana_retype_mailaddress',
            lang('settings_retype_mailaddress') ,
            'required|valid_email|matches[Mana_new_mailaddress]' );

        // validate userinput against rules
        if ($this->form_validation->run() == false)
        {
            // validation fails, prepare params for change form
            $params['has_errors'] = true;
        }
        else
        {
            $this->membershipprovider->setMailaddressForUser(
                $this->user->getUser()->getUsername(),
                $this->input->post('Mana_new_mailaddress')
            );
            $params['mailaddress_changed_message'] = lang('mailaddress_changed_message');
        }

        $this->output->showPage(lang('settings_change_mail_head'),
            'manaweb/accountmanager/change_mailaddress_form', $params );


    }

    /**
     * This is a callback function to validate the user given password against
     * password policy.
     *
     * @param pwd (String) Password to validate
     * @returns (Bool) \c true, if the password fulfills the policy, otherwise
     *                  \c false.
     */
    public function _password_strength($pwd)
    {
        $username = $this->user->getUser()->getUsername();
        $ret = Membershipprovider::validatePassword($pwd, $username);
        switch ($ret)
        {
            case Membershipprovider::PASSWORD_OK:
                return true;
            case Membershipprovider::PASSWORD_TO_SHORT:
                $this->form_validation->set_message('_password_strength',
                lang('settings_pwd_to_short'));
                return false;
            case Membershipprovider::PASSWORD_TO_LONG:
                $this->form_validation->set_message('_password_strength',
                lang('settings_pwd_to_long'));
                return false;
            case Membershipprovider::PASSWORD_SIMILAR_TO_USERNAME:
                $this->form_validation->set_message('_password_strength',
                lang('settings_pwd_eq_username'));
                return false;
        }
    }


    /**
     * This is a callback function to validate the current password of the user
     * if he tries to change his password.
     *
     * @param pwd (String) Password to validate
     * @return (Bool) \c  true, if the password matches the current,
     *                 otherwise \c false
     */
    public function _validate_password($pwd)
    {
        $name = $this->user->getUser()->getUsername();

        // call authenticate function from user model but set 3rd parameter to
        // false to prevent session from being modified
        $retval = $this->user->authenticate($name, $pwd, false);

        // if authentication fails, set correct error message
        if (!$retval)
        {
            $this->form_validation->set_message('_validate_password',
            'The old password is wrong.');
        }
        return $retval;
    }


    /**
     * This private function is used to show the account page of the user.
     */
    private function _show_user_account()
    {
        $this->output->showPage('My Account', 'manaweb/user_home',
            $this->user->getHomepageData());
    }


} // class Myaccount
?>

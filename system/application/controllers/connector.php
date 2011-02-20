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
 * Controller working as connector for external applications.
 *
 * @ingroup controllers
 */
class Connector extends Controller {

    /**
     * Initializes the Home controller.
     */
    function __construct()
    {
        parent::Controller();
        $this->output->enable_profiler(
            $this->config->item('mana_enable_profiler')
        );

    }

    /**
     * Generates a list of all online users in the preferred format.
     *
     * Currently the connector support 2 types of output formats:
     * - plain, generates a plain textfile listing one character each line
     * - xml, generates a xml file with more details informations
     *
     * @param format (string) Output format of the list.
     */
    public function onlineuser($format="plain")
    {
        $this->db->order_by('name');
        $query = $this->db->get(Character::ONLINE_CHARS_TBL);
        $users['users'] = $query->result_array();

        switch ($format)
        {
            case "xml":
                $this->load->view('connector/onlineuser_xml', $users);
                break;
            case "csv":
                $this->load->view('connector/onlineuser_csv', $users);
                break;
            case "plain":
            default:
                $this->load->view('connector/onlineuser_plain', $users);
                break;
        }

    }

} // class Statistics
?>
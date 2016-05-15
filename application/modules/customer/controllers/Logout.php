<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logout extends CMS_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {

        if ($this->isLogged) {
            // By setting the logout functions argument as 'TRUE', all browser sessions are logged out.
            $this->session->unset_userdata('cartAssocDet');
            $this->session->unset_userdata('cartReorderRef');
            $this->session->unset_userdata('cart_contents');
            $this->flexi_auth->logout(TRUE);

            // Set a message to the CI flashdata so that it is available after the page redirect.
            $this->session->set_flashdata('message', $this->flexi_auth->get_messages());
        }

        redirect('/');
    }


}

?>

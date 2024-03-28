<?php

class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_community_events()
    {
        $data = (object) $this->params;
        $data = null;
        // Fetch all rental home data
        $rentalHomes = $this->db->get("community_events", null);

        // Check if any rental homes were found
        if (!$rentalHomes) {
            $ResponseData = array(
                "message" => "No events found",
                "code" => FAILED,
                "status" => $this->translate('STATUS_FAILD')
            );
            $this->responseReturn($ResponseData);
        }

        // Rental homes found, prepare response
        $ResponseData = array(
            "message" => "Community events found",
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "rental_homes" => $rentalHomes
        );
        $this->responseReturn($ResponseData);
    }
}

?>

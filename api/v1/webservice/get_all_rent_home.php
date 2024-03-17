<?php

class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_all_rent_home()
    {
        $data = (object) $this->params;
        $data = null;
        // Fetch all rental home data
        $rentalHomes = $this->db->get("rent_home", null, array("country", "state", "city", "rent", "bhk"));

        // Check if any rental homes were found
        if (!$rentalHomes) {
            $ResponseData = array(
                "message" => "No rental homes found",
                "code" => FAILED,
                "status" => $this->translate('STATUS_FAILD')
            );
            $this->responseReturn($ResponseData);
        }

        // Rental homes found, prepare response
        $ResponseData = array(
            "message" => "Rental homes found",
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "rental_homes" => $rentalHomes
        );
        $this->responseReturn($ResponseData);
    }
}

?>

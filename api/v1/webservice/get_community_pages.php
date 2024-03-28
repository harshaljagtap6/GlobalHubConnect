<?php

class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_community_pages()
    {
        $data = (object) $this->params;
        $data = null;
        // Fetch all rental home data
        $rentalHomes = $this->db->get("community_pages", null);

        // Check if any rental homes were found
        if (!$rentalHomes) {
            $ResponseData = array(
                "message" => "No pages found",
                "code" => FAILED,
                "status" => $this->translate('STATUS_FAILD')
            );
            $this->responseReturn($ResponseData);
        }

        // Rental homes found, prepare response
        $ResponseData = array(
            "message" => "Community pages found",
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "rental_homes" => $rentalHomes
        );
        $this->responseReturn($ResponseData);
    }
}

?>

<?php
/**
 * Created by PhpStorm.
 * User: Avery
 * Date: 5/24/2015
 * Time: 1:22 PM
 */

class Friends {
    public $friend_id, $friender_id, $friended_id, $friended_date;

    public function __construct($friend_id, $friender_id, $friended_id, $friended_date){
        $this->friend_id = $friend_id;
        $this->friender_id = $friender_id;
        $this->friended_id = $friended_id;
        $this->friended_date = $friended_date;
    }
}
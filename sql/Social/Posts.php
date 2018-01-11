<?php
/**
 * Created by PhpStorm.
 * User: Avery
 * Date: 5/24/2015
 * Time: 1:36 PM
 */

class Posts {
    public $post_id, $account_id, $location, $message, $likes, $posted;

    public function __construct($post_id, $account_id, $location, $message, $likes, $posted){
        $this->post_id = $post_id;
        $this->account_id = $account_id;
        $this->location = $location;
        $this->message = $message;
        $this->likes = $likes;
        $this->posted = $posted;
    }
}
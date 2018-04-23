<?php

//initializing the variables required for push in the constructor,
// and giving us back an array with the required data in getPush() method.

 class Push {
    //notification title
    private $title;
 
    //notification message 
    private $message;
 
    //notification image url 
    private $image;
 
    //initializing values in this constructor
    function __construct($title, $message, $image) {
         $this->title = $title;
         $this->message = $message; 
         $this->image = $image; 
    }
    
    //getting the push notification
    public function getPush() {
        $res = array();
        $res['data']['title'] = $this->title;
        $res['data']['message'] = $this->message;
        $res['data']['image'] = $this->image;
        return $res;
    }
 
}
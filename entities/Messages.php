<?php


class Messages{

    /**
    * @var string
    */
    private $text;

    /**
     * @var User
     */
    private $from;

    /**
     * @var User
     */
    private $to;

    /**
     * @var DateTime
     */
    private $date;
    function __construct($text, $from, $to, $date){
        $this->text = $text;
        $this->from = $from;
        $this->to = $to;
        $this->date = $date;
    }
}
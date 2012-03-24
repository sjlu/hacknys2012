<?php

class Test extends CI_Controller {
    
    function index () {
        $this->load->model('Parsely_Articles');

        $test = array();
        $test[] = 'Steve Jobs';
        $test[] = 'Steve Wozniak';

        echo '<pre>';
        var_dump($this->Parsely_Articles->get($test));
    }

    function entities () {
        $this->load->model('Essay_Model');

        echo '<pre>';
        var_dump($this->Essay_Model->extract_entities("On Wednesday, Steve Jobs passed away. Working around media, I often think about the thousands of pre-written obituaries and pre-produced retrospectives just waiting to be released â€” this is not one of them. I have, in this publication, made several critical remarks about Steve Jobs and his Apple products, but never have I sought to understate the impact his contributions have made."));
    }

}

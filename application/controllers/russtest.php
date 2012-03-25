<?php

class Russtest extends CI_Controller {
    
    function index () {
        $this->load->model('NYTimes');

        $test = array();
        $test[] = 'Steve Jobs';
        $test[] = 'Steve Wozniak';
        $test[] = 'Obama';

        echo '<pre>';
        var_dump($this->NYTimes->get($test));
        echo '</pre>';

        $images = $this->NYTimes->get_images();

        foreach ($images as $topic) {
            foreach ($topic as $image) {
                echo "<img src='$image' />";
            }
        }
    }

};


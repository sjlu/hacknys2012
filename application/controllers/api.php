<?php

class API extends CI_Controller {
    
    function index () {
        $this->load->model('Essay_Model');
        $this->load->model('Parsely_Articles');
        $this->load->model('Bibliography');
        $this->load->model('NYTimes');

        $input = json_decode($_POST['data'], true);

        $entities = $this->Essay_Model->extract_entities($input['essay']);

        $nytimes_articles = $this->NYTimes->get($entities);
        $parsely_articles = $this->Parsely_Articles->get($entities);
        $articles = array_merge($nytimes_articles, $parsely_articles);

        $images = $this->NYTimes->get_images();

        $bibliography = $this->Bibliography->get_citations($nytimes_articles);

        $return = array();
        $return['essay'] = $input['essay'];

        echo json_encode($return);
    }

};


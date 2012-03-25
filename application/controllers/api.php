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

        $bibliography = $this->Bibliography->get_citations($articles);

        $essay_with_citations = $this->Essay_Model->add_intext_citations($articles,$bibliography);

        $return = array();
        $return['essay'] = $input['essay'];
        $return['bibliography'] = $bibliography;
        $return['essay_with_citations'] = $essay_with_citations;
        $return['images'] = $images;


        echo json_encode($return);
    }

};


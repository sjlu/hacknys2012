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
        $articles = $nytimes_articles;

        # ugly array merge
        foreach ($articles as $topic => $actual_articles) {
            if (isset($parsely_articles[$topic])) {
                $articles[$topic] = array_merge(
                    $actual_articles, $parsely_articles[$topic]);
            }
        }

        $images = $this->NYTimes->get_images();

        $bibliography = $this->Bibliography->get_citations($articles);

        $beefed_essay = $this->Essay_Model->add_intext_citations($articles,$bibliography);

        $return = array();
        $return['essay'] = $input['essay'];
        $return['bibliography'] = $bibliography;
        $return['beefed_essay'] = $beefed_essay;
        $return['images'] = $images;


        echo json_encode($return);
    }

};


<?php

class API extends CI_Controller {
    
    function index () {
        $this->load->model('Essay_Model');
        $this->load->model('Parsely_Articles');
        $this->load->model('Bibliography');
        $this->load->model('NYTimes');
        $this->load->model('Sentence');
        $this->load->model('Quotes');

        $input = json_decode($_POST['data'], true);

        $beefy_essay = $this->Essay_Model->get_parsley_text($input['essay']);
        $entities = $this->Essay_Model->extract_entities($beefy_essay);

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
        $sentences = $this->Sentence->tokenize($beefy_essay);
        $sentences = $this->Essay_Model->add_intext_citations($articles,$bibliography,$sentences);
        $sentences = $this->Essay_Model->add_filler_sentences($parsely_articles, $sentences,5);
        $sentences = $this->Quotes->run($entities, $parsely_articles, $sentences,4);
        $sentences = implode(" ",$sentences);
        $return = array();
        $return['essay'] = $input['essay'];
        $return['bibliography'] = $bibliography;
        $return['beefed_essay'] = $sentences;
        $return['images'] = $images;


        echo json_encode($return);
    }

};


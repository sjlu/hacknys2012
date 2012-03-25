<?php

class Russtest extends CI_Controller {
    
    function index () {
        $this->load->model('Essay_Model');
        $this->load->model('Parsely_Articles');
        $this->load->model('Bibliography');
        $this->load->model('NYTimes');
        $this->load->model('Quotes');
        $this->load->model('Sentence');

        $input = array();

        $input['essay'] = "Mr Obama first visited US troops in the Demilitarised Zone separating the Koreas, and is due in Seoul for talks.

        The US has voiced concern that the rocket launch due in apri: is a pretext for a missile test. Pyongyang says it wants to put a satellite into orbit.";

//        $entities = $this->Essay_Model->extract_entities($input['essay']);

        $entities[] = "Obama";
        $entities[] = "Rick Santorum";
        $entities[] = "Republican";

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

        $output = array();

        $inp = $this->Sentence->tokenize($input['essay']);

        $output['essay'] = $this->Quotes->run($entities, $parsely_articles, $inp);

        var_dump( $output['essay']);
    }

    function sentence () {
        $input = array();

        $input['essay'] = "Mr Obama first visited US troops in the Demilitarised Zone separating the Koreas, and is due in Seoul for talks.

        The US has voiced concern that the rocket launch due in apri: is a pretext for a missile test. Pyongyang says it wants to put a satellite into orbit.";

        $this->load->model('Sentence');

        echo '<pre>';

    }

};


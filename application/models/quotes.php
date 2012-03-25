<?php

class Quotes extends CI_Model {

    # inserts quotes
    function run ($topics, $articles, $text) {
        $this->load->model('Essay_Model');

        $possible_articles = array();

        foreach ($articles as $topics) {
            foreach ($topics as $article) {
                if (isset($article['full_content'])) {
                    $possible_articles[] = $article;
                }
            }
        }

        echo '<pre>';
        var_dump($possible_articles);

        return $text;
    }

}


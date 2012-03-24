<?php

class Parsely_Articles extends CI_Model {

    function simon_call ($query) {
        $base = 'http://simon.parsely.com:8983/solr/goldindex2/select/?wt=json&q=';
        $url = $base . urlencode($query);
        $data = json_decode(file_get_contents($url), TRUE);
        return $data['response']['docs'];
    }

    function get ($topics) {
        $return = array();

        foreach ($topics as $topic) {
            $data = $this->simon_call($topic);

            $return[$topic] = $data;
        }

        return $return;
    }

}

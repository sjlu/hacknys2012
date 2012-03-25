<?php

class NYTimes extends CI_Model {

    private $images = array();

    function api_call ($query) {
        $base = 'http://api.nytimes.com/svc/search/v1/article/?api-key=2be1ef573e3ae5ec814fb660c9d01dfb:1:65860901&fields=byline,author,title,url,date,small_image_url&query=title:';
        $url = $base . urlencode($query);
        $data = json_decode(file_get_contents($url), TRUE);
        return $data['results'];
    }

    function get ($topics) {
        $return = array();

        foreach ($topics as $topic) {
            $data = $this->api_call($topic);
            $ret = array();

            # extract images and cook the author field
            foreach ($data as $article) {
                if (!isset($article['byline'])) continue;

                $by = $article['byline'];
                preg_match("/(\s*[A-Z.]*\s*)*/", substr($by, 3), $matches);
                $by = ucwords(strtolower($matches[0]));
                $article['author'] = $by;
                $article['publisher'] = "New York Times";

                # probably the cooking just fucked up
                if (strlen($by) < 5) continue;

                if (!isset($this->images[$topic])) {
                    $this->images[$topic] = array();
                }

                if (isset($article['small_image_url'])) {
                    $this->images[$topic][] = str_replace(
                        'thumbStandard', 'popup', $article['small_image_url']);
                }

                $ret[] = $article;
            }

            $return[$topic] = $ret;
        }

        return $return;
    }

    function get_images () {
        return $this->images;
    }

}


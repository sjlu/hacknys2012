<?php

class Bibliography extends CI_Model {

    function simon_call ($query) {
        $base = 'http://simon.parsely.com:8983/solr/goldindex2/select/?wt=json&q=';
        $url = $base . urlencode($query);
        $data = json_decode(file_get_contents($url), TRUE);
        return $data['response']['docs'];
    }

    function get_citations($articles) {
        $return = array();

        // articles is Entity => 10 articles
        foreach ($articles as $key=>$entity){
            //grab a random article from the list of 10
            $topic = $entity[array_rand($entity)];
            //required fields:    title, publisher, url
            //additional possible fields: author_first,author_last, pub_date , issue_num

            $in_text = '("' . $topic['publisher'] . '")';
            $citation = "<span class='new citation'>";
            if(array_key_exists('author',$topic)){
                //move last name to front, put comma (assumes only 1 author) 
                $names =  preg_split('/\s+/', $topic['author']);
                if( count($names) >1){
                    $rev =  array_reverse($names);
                    $last = array_shift($rev);
                    while (! preg_match("/[a-z|A-Z]/",$last )){
                        $last = array_shift($rev); 
                    }
                    $citation .= $last . ",";

                    if(($replace_key= array_search($last, $names)) !== false)
                    {
                        unset($names[$replace_key]);
                    }
                    foreach($names as $name){
                        $citation .= " $name"; 
                    }
                    $citation .= ". ";
                    $in_text = "(" . $last . ")";
                }else if ( preg_match("/[a-z|A-Z]/",$topic['author'])){
                    //only 1 name, put it straight out
                    $citation .=  $topic['author'];
                    $citation .= ". ";
                    $in_text = "(" . $topic['author'] . ")";
                }
            }
            $citation .= sprintf("\"%s\" <i>%s</i>", $topic['title'], $topic['publisher']);

            if (array_key_exists("pub_date",$topic)){
                $pub_date = date( "d M Y:", strtotime($topic['pub_date']));
                $citation .= " $pub_date";
            }else{
                $citation .= " n. d." ;
            }

            $citation .= " Web.";
            $citation .= date(" d M Y"); 
            $citation .= "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&lt;" . $topic['url'] . "&gt;";
            $citation .= "<br/></span>\n";

            $return[$key] = array('in-text'=> $in_text, 'citation' => $citation);
        }
        return $return;
    }

}

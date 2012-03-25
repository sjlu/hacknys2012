<?php

class Essay_Model extends CI_Model{

    private $PARSELY_TIMEOUT_SECONDS = 20;
    private $PARSELY_API_ROOT = "http://hack.parsely.com";
    private $entities;
    private $text_with_entities;

    public function Essay_Model(){
        parent::__construct();
    }

    public function add_intext_citations($articles,$bibliography){
        $text_with_citations = $this->text_with_entities;
        //insert all the citations we have
        foreach ($articles as $keyword => $val){
            //everywhere we see $keyword </TOPIC>, replace with the in-text citation
            $citation_obj = $bibliography[$keyword];
            $text_with_citations = preg_replace("/$keyword.*?<\/TOPIC>/",$keyword . " " . $citation_obj['in-text'], $text_with_citations);
        }
        //just remove all other <TOPIC> stuff
        $text_with_citations = preg_replace("/<.*?TOPIC>/",'', $text_with_citations);
        return $text_with_citations;
    }

    public function get_parsley_text($essay_text){
        //post the essay to parse.ly
        $url = $this->PARSELY_API_ROOT . "/parse";
        $essay_text = preg_replace("/\n/", "---NEWLINE---",$essay_text);
        $result = self::post($url,array('text'=>$essay_text, 'wiki_filter' => 'false'));
        // if post went through
        if ($result ){
            // decode the json, get the url of the result
            $result = json_decode($result,true);
            if ($result['url']){
                //check the results until they are ready (or we timeout) 
                $seconds_waited = 0;      
                $seconds_to_sleep = 1;
                while ($seconds_waited <= $this->PARSELY_TIMEOUT_SECONDS){
                    $job_result = file_get_contents($this->PARSELY_API_ROOT . $result['url']);
                    $job_result = json_decode($job_result,true);
                    //if not done yet, wait a second and ask again
                    if ($job_result['status'] === "WORKING"){
                        sleep($seconds_to_sleep);
                        $seconds_waited += $seconds_to_sleep;
                    }else{
                        // done working, it should return the extracted entities 
                        $result = preg_replace("/---NEWLINE---/","\n",$job_result['data']);
                        $result = preg_replace("/ ,/",",",$result);
                        $result = preg_replace("/ '/","'",$result);
                        $result = preg_replace("/ \"/","\"",$result);

                        return $result;
                    }
                }
            }else{
                print "parse.ly did not return a url to check job status";
                print_r($result);
                return false; 
            }
        }else{
            print "parse.ly post failed";
            var_dump($result);
            return false;
        }

    }

    public function extract_entities($essay_text, $num_entities=5){
        $parsely_text = $this->get_parsley_text($essay_text);
        if(! $parsely_text){ 
            return false;
        }else{
            $this->text_with_entities=$parsely_text;

            //now, we have to extract and count all the entities
            preg_match_all("/<TOPIC>(.*?)<\/TOPIC>/",$this->text_with_entities,$all_entities,PREG_PATTERN_ORDER);
            $this->entities = array();
            foreach($all_entities[1] as $entity){
                if(array_key_exists($entity,$this->entities)){
                    $this->entities[$entity]++; 
                }else{
                    $this->entities[$entity] = 1; 
                }
            }
            array_multisort($this->entities,SORT_DESC);
            return array_keys(array_slice($this->entities,0,$num_entities));

        }
    }

    private function post($url,$fields){
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);
        return $result;
    }
}

?>

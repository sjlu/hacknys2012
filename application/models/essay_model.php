<?php

class Essay_Model extends CI_Model{

    private $PARSELY_TIMEOUT_SECONDS = 20;
    private $PARSELY_API_ROOT = "http://hack.parsely.com";

    public function Essay_Model(){
        parent::__construct();
    }

    public function extract_entities($essay_text, $num_entities=5){
        //post the essay to parse.ly
        $url = $this->PARSELY_API_ROOT . "/parse";
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
                        $text_with_entities = $job_result['data'];
                        //now, we have to extract and count all the entities
                        preg_match_all("/<TOPIC>(.*?)<\/TOPIC>/",$text_with_entities,$all_entities,PREG_PATTERN_ORDER);
                        $entities = array();
                        foreach($all_entities[1] as $entity){
                           if(array_key_exists($entity,$entities)){
                                $entities[$entity]++; 
                           }else{
                                $entities[$entity] = 1; 
                           }
                       }
                       array_multisort($entities,SORT_DESC);
                       return array_slice($entities,0,$num_entities);

                    }
                }
            //result['url'] is false
            }else{
                return $result; 
            }
        //post returned false
        }else{
            return "post to $url failed!";
            //error!
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

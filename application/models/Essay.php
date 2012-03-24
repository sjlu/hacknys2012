<?php

class Essay_Model extends Model{

    private $PARSELY_TIMEOUT_SECONDS = 15;
    private $PARSELY_API_ROOT = "http://hack.parsely.com";

    public function Essay_Model(){
        parent::Model(); 
    }

    public function extract_entities($essay_text){
        //post the essay to parse.ly
        $url = "$PARSELY_API_ROOT/parse";
        $result = post(array('text'=>$essay_text, 'wiki_filter' => 'false'));
        // if post went through
        if ($result ){
            // decode the json, get the url of the result
            $result = json_decode($result,true);
            if ($result['url']){
                //check the results until they are ready (or we timeout) 
                $seconds_waited = 0;      
                $seconds_to_sleep = 1;
                while ($time_waited <= $TIMEOUT){
                   $job_result = file_get_contents($PARSELY_API_ROOT . $result['url']);
                   $job_result = json_decode($entities,true);
                   //if not done yet, wait a second and ask again
                   if ($job_result['status'] === "WORKING"){
                        sleep($seconds_to_sleep);
                        $seconds_waited += $seconds_to_sleep;
                    }else{
                        // done working, it should return the extracted entities 
                        $text_with_entities = json_decode($job_result,true);
                        $text_with_entities = $text_with_entities['data'];
                        //now, we have to extract and count all the entities
                        preg_match_all("/<TOPIC>(.*?)</TOPIC>/g",$text_with_entities,$entities,PREG_PATTERN_ORDER);
                        return $entities[1];
                        //foreach($entities as $entity){
                       // }

                    }
                }
            }else{
                return false;
                //error
            }
        }else{
            return false;
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

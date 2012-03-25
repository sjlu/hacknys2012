<?php

class Essay_Model extends CI_Model{

    private $PARSELY_TIMEOUT_SECONDS = 20;
    private $PARSELY_API_ROOT = "http://hack.parsely.com";
    private $entities;
    private $text_with_entities;

    public function Essay_Model(){
        parent::__construct();
    }

    public function get_filler_sentence($keyword){
        $filler_templates = array(
                "While not initially obvious, careful analysis shows this reveals much of the true depth behind %s.",
                "Clearly, %s is subtle and complex. The following paragraphs attempt to explain in further detail.",
                "This was one of the most important and influential events to influence %s.",
                "Despite its seemingly simple manifestation, the importance of this cannot be overstated." ,
                "%s made considerable gains. In the following, it will be shown how that is largely due to this influence.",
                "Despite the fact that such an event may appear entirely unexpected, it is fully explainable and predictable given existing theories.",
                "Many experts argue that %s could be one of the largest influences of the century, and as such the importance cannot be overstated.",
                "Following this train of thought to its logical conclusion leads to frightening ramifications.",
                "This exemplifies both the strength and weaknesses of %s, and as such is an important issue to study.",
                "The issues surrounding %s have been an area of extreme interest for some time, and while much work has been done, many questions have yet to be answered.",
                "Therefore, it can be argued that %s has been entirely misunderstood. In fact, the common and popular opinion has laboured under misconceptions about %s for some time.",
                "Public opinion showed a distinct and unmistable response for %s in light of this.",
                "The study of %s, always a topic of ample interest, was elevated to colossal proportions as a result.",
                "This is only the surface of %s, however, and there is much more to explore",
                "%s was certainly larger than life. Perhaps too large.",
                "%s signified a change in traditional thought that frightened men while capturing the hearts of women and children.",
                "%s was the product of a revolution that spanned from inception to deletion.",
                "%s was said to have caused a massive spike in human population, and was dubbed \"The Daddy of All Daddys\" by the National Association of Paternal Names.",
                "%s had such a great impact that even the most expert of experts could not fully grasp the level of greatness in context. In other  words, %s was pretty great.",
                "%s's conception was foretold by a swallow, and heralded by the appearance of a double rainbow across the sky over the mountain with a new star in the heavens.",
                "At times, bringing up %s was known to lay cause for a spontaneous disrobing of the fairer gender.",
                "In west Philadelphia, born and raised. Is where %s would spend most days.",
                "%s's arrival as a harbringer of great news was fleeting at the moment someone decided to save 15 per-cent or more on their car insurance by switching to Geico."
               );
        //get random template
        $template = $filler_templates[array_rand($filler_templates)];
        return sprintf($template,$keyword,$keyword);

    }

    public function add_filler_sentences($articles,$sentences,$num_to_add){
        for($i =0; $i < $num_to_add; $i++){
            $keyword = array_rand($articles);     
            $random_sentence = "<span class='new filler'> " . self::get_filler_sentence($keyword). "</span>"; 
            $random_index = rand(3,count($sentences)-4);
            array_splice($sentences ,$random_index,0,$random_sentence);
        }
        return $sentences;
    }

    public function add_intext_citations($articles,$bibliography,$sentences){
        $return = array();        
        foreach ($sentences as $sentence){ 
            //insert all the citations we have
            foreach ($articles as $keyword => $val){
                //everywhere we see $keyword </TOPIC>, replace with the in-text citation
                $citation_obj = $bibliography[$keyword];
                //if this sentence has this keywork, add a citation at the end (and only one)
                if( preg_match("/$keyword.*?<\/TOPIC>/", $sentence)){
                    $sentence .= " <span class='new in-text'>" . $citation_obj['in-text'] . "</span>"; 
                    break;
                }
            }
            //just remove all other <TOPIC> stuff
            //$sentence = preg_replace("/<\/*TOPIC>/",'', $sentence);
            $return[] = $sentence;

        }
        return $return;
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
                        syslog(LOG_ERR,"waited for $seconds_waited");
                    }else{
                        // done working, it should return the extracted entities 
                        $result = preg_replace("/---NEWLINE---/","<br/>",$job_result['data']);
                        //$result = preg_replace("/ ([^[a-zA-Z0-9\s])/","$1",$result);

                        return $result;
                    }
                }
                print "parse.ly job timed out, more than " . $this->PARSELY_TIMEOUT_SECONDS . " seconds passed";
                return false;
            }else{
                syslog(LOG_ERR,"Parse.ly did not return url to check job status, exiting");
                print "parse.ly did not return a url to check job status";
                print_r($result);
                return false; 
            }
        }else{
                syslog(LOG_ERR,"Parse.ly post failed");
            print "parse.ly post failed";
            var_dump($result);
            return false;
        }

    }

    public function extract_entities($parsely_text, $num_entities=5){
        if(! $parsely_text){ 
            print "input text was empty";
                syslog(LOG_ERR,"input text was empty");
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

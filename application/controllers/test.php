<?php

class Test extends CI_Controller {
    
    function index () {
        $this->load->model('Parsely_Articles');

        $test = array();
        $test[] = 'Steve Jobs';
        $test[] = 'Steve Wozniak';

        #echo '<pre>';
        var_dump($this->Parsely_Articles->get($test));
    }

    function entities () {
        $this->load->model('Essay_Model');
        $this->load->model('Parsely_Articles');
        $this->load->model('Bibliography');
        $this->load->model('NYTimes');
        $this->load->model('Sentence');
        $this->load->model('Quotes');

        print '<pre>';
        $essay = "  Polls opened across Louisiana at 7 a.m. EDT and will remain open until 9 p.m. EDT. Just 20 of the state's delegates are at stake and will be allocated proportionally among candidates earning more than 25 percent of the vote. If no candidate earns above 25 percent, the delegates will remain uncommitted. An additional 23 delegates will be selected at the state Republican convention in June.
        
        The state has 2.86 million registered voters and residents cast 43,576 early ballots; about half of those were from Republicans, according to the Louisiana secretary of state's office. Registered Democrats are also voting in a statewide primary, but President Obama has only token opposition.
        
        All four remaining GOP presidential contenders campaigned in the Pelican State this week ahead of the contest, but were not planning appearances in the state Saturday. Romney is spending the weekend off the campaign trail at his home in La Jolla, Calif. Newt Gingrich planned to campaign in Pennsylvania ahead of that state's primary in late April. Ron Paul has no scheduled campaign events.
        
        At a conference in Pennsylvania Saturday, Santorum called his unsuccessful 2006 Senate reelection campaign a 'gift' that helped him better comprehend conservative frustrations with Washington, according to the Associated Press.
        
        The former senator said he used to think Pennsylvanians didn't understand how business was done in Washington. Instead, 'In a sense, I didn't understand,' he said.
        
        Santorum spoke in Pennsylvania on Saturday before flying to campaign events in Wisconsin.
        
        On Saturday Louisiana voters said they were concerned about the economy — and several expressed skepticism of Romney.
        
        Outside a polling place in Covington, La., Bobby Massa, 47, a warehouse worker, said he thought the former governor 'is going to be another Obama. I just get the sick feeling that he'll continue what Obama's been doing.'
        
        Massa said he voted for Gingrich, but would 'most likely' support Romney in November if he were the GOP nominee.
        
        'Gingrich is a straight shooter and Romney just works around the truth ‘til he gets what he wants,' Massa said.
        
        Covington is the seat of St. Tammany Parish, where more than 75 percent of voters favored Republican Sen. John McCain in 2008.
        
        Many voters said the nomination fight had gone on too long.
        
        'I think these guys need to put their egos in the closet and get out,' said Catherine Farrish, 60, a naturopath.
        
        Farrish said she considered voting for Romney so the race would end quicker. Instead she supported Santorum because she said 'he scares me the least.'
        
        'I'm not in love with any of them,' she said.
        
        Jules Richard, 67, an accountant, used a backhanded compliment to describe his feelings about Romney.
        
        'I think he's the best politician in the race,' Richard said. 'He gives people what they want to hear.'
        
        Richard said he voted for Santorum but would have no problem support Romney in the general election.
        
        'I voted for Santorum just to annoy Romney really,' he said.
        
        Romney appeared to acknowledge Friday his less-than-successful strategy in the South: 'You got a lot of delegates here,' he told supporters. 'I want, well, I'd like all of them. I'm probably not going to get all of them, but I'd like to get as many as I can.'
        
        But even if he wins just a handful of delegates Saturday, it will put Romney closer to the GOP nomination and further complicate the strategies of his opponents, who face a difficult stretch of contests next month. The District of Columbia, Maryland and Wisconsin hold primaries April 3. Connecticut, Delaware, New York, Pennsylvania and Rhode Island vote April 24.
        
        Several Louisiana Republicans said they were wrestling with what they would do if Romney becomes the party's pick.
        
        'I know that I can't vote for Obama, but I would seriously have to pray about it, but I'm not going to give up on Santorum,' said Nicole Hudgens, 23, of Shreveport, a teacher.
        
        In Metairie, Gerald Tranchant, 75, said he voted for Gingrich, knew that Romney would win the nomination, but said that's not enough to win his support.
        
        'He's a little too highfalutin — too much money,' Tranchant said. 'He don't know what life's all about. He don't work for a living.'
        
        O'Keefe reported from Washington. Staff writer Nia-Malika Henderson contributed to this report from Shreveport, La.
        ";
        $beefy_essay = $this->Essay_Model->get_parsley_text($essay);
        $entities = $this->Essay_Model->extract_entities($beefy_essay,5);
        print "We got these entities:\n";
        var_dump($entities);
        $articles = $this->Parsely_Articles->get($entities);
        $ny_articles =  $this->NYTimes->get($entities);
        $articles = array_merge_recursive($articles,$ny_articles);
        #print "Those gave us these articles:\n";
        //var_dump($articles);
        print "\n</pre>\n";
        print "generated this bibliography:<br/>";
        $bibliography = $this->Bibliography->get_citations($articles);

        foreach( $bibliography as $key=>$val){
            print $key . " - " . $val['in-text'] . "<br/>" . $val['citation'] . "\n<br/>"; 
        }
    
        $imgs = $this->NYTimes->get_images();
        foreach($imgs as $key=>$val){
            foreach ($val as $img){
                print "$key<br/><img src='$img' /><br/><br/>\n\n";
            }
        }

        print "<br/>We got the output:<br/>\n";
       // split input into sentences
       $beefy_essay= $this->Sentence->tokenize($beefy_essay);
       $beefy_essay = $this->Essay_Model->add_filler_sentences($articles,$beefy_essay,5);
       $beefy_essay = $this->Essay_Model->add_intext_citations($articles,$bibliography,$beefy_essay);
       //$beefy_essay= $this->Quotes->run($entities, $articles, $beefy_essay);
       $beefy_essay = implode(" ",$beefy_essay);
       print $beefy_essay;

    }

}

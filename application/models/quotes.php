<?php

class Quotes extends CI_Model {

    # inserts quotes
    function run ($topics, $input_articles, $essay) {
        $this->load->model('Essay_Model');
        $this->load->model('Sentence');

        $possible_articles = array();

        foreach ($input_articles as $topic => $articles) {
            foreach ($articles as $article) {
                if (isset($article['full_content'])) {
                    $possible_articles[$topic][] = $article;
                }
            }
        }

        $use_articles = array();

        foreach ($possible_articles as $topic => $articles) {
            $count = count($topics);

            if ($count <= 2) {
                $use_articles[$topic] = $articles;
            } else {
                $rnd1 = array_splice($articles, rand(0, $count), 1);
                $rnd2 = array_splice($articles, rand(0, $count - 1), 1);
                $use_articles[$topic][] = $rnd1[0];
                $use_articles[$topic][] = $rnd2[0];
            }
        }

        $quotes = array();

        foreach ($use_articles as $topic => $articles) {
            foreach ($articles as $article) {
                $text = $this->Sentence->tokenize($article['full_content']);
                foreach ($text as $line) {
                    if ($line[0] == "[" or $line[0] == "]" or $line[0] == "(" or $line[0] == ")" or
                        $line[0] == "\"") {
                        continue;
                    }
                    if (strstr($line, $topic) != NULL) {
                        $line = str_replace("\n", "", $line);
                        $quotes[$topic][] = $this->format($article['author'], $article['publisher'], $line);
                    }
                }
            }
        }

        foreach ($essay as $line_num => $line) {
            foreach ($topics as $topic) {
                if (strstr($line, $topic) != NULL) {
                    $rnd = rand(0, 2);
                    if ($rnd == 1) {
                        $rnd_quote = rand(0, count($quotes[$topic]));
                        array_splice($essay, $line_num, 0, $quotes[$topic][$rnd_quote]);
                    }
                }
            }
        }

        return $essay;
    }

    function format ($author, $publication, $quote) {
        $templates = array(
            'As #{AUTHOR} of #{PUBLICATION} says, "#{QUOTE}"',
            'Of course, "#{QUOTENOPERIOD}" as #{AUTHOR} of #{PUBLICATION} says.',
            '#{AUTHOR} of #{PUBLICATION} states "#{QUOTE}"',
            '#{AUTHOR} of #{PUBLICATION} says "#{QUOTE}"',
            'As said by #{AUTHOR} of #{PUBLICATION}, "#{QUOTE}"',
            '#{AUTHOR} from #{PUBLICATION} tells us "#{QUOTE}"'
        );
        
        $rnd = rand(0, count($templates) - 1);
        $str = $templates[$rnd];

        $str = str_replace("#{AUTHOR}", $author, $str);
        $str = str_replace("#{PUBLICATION}", $publication, $str);
        $str = str_replace("#{QUOTE}", $quote, $str);
        $str = str_replace("#{QUOTENOPERIOD}", str_replace(".", ",", $quote), $str);

        return $str;
    }

}


<?php

class Quotes extends CI_Model {

    # inserts quotes
    function run ($topics, $input_articles, $essay, $chance) {
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
            $count = count($articles);

            if ($count <= 2) {
                $use_articles[$topic] = $articles;
            } else {
                $rnd1 = array_splice($articles, rand(0, $count - 1), 1);
                $rnd2 = array_splice($articles, rand(0, $count - 2), 1);
                $use_articles[$topic][] = $rnd1[0];
                $use_articles[$topic][] = $rnd2[0];
            }
        }

        $quotes = array();

        foreach ($use_articles as $topic => $articles) {
            foreach ($articles as $article) {
                $text = $this->Sentence->tokenize($article['full_content']);
                foreach ($text as $line) {
                    if ($line == "") continue;
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
            if ($line_num == 0) continue;
            foreach ($topics as $topic) {
                if (!isset($quotes[$topic])) continue;
                if (strstr($line, $topic) != NULL) {
                    $rnd = rand(0, $chance);
                    if ($rnd == 1) {
                        $num = rand(0, count($quotes[$topic]) - 1);
                        $thequote = array_splice($quotes[$topic], $num, 1);
                        $rnd_quote = "<span class=\"new beefquote\">" . $thequote[0] . "</span>";
                        array_splice($essay, $line_num, 0, $rnd_quote);
                    }
                }
            }
        }

        return $essay;
    }

    function format ($author, $publication, $quote) {
        $templates = array(
            'As #{AUTHOR} of #{PUBLICATION} says, "#{QUOTE}"',
            'Of course, "#{QUOTENOPERIOD}" says #{AUTHOR} of #{PUBLICATION}.',
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


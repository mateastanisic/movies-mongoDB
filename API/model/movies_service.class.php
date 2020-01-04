<?php

//klasa koja predstavlja jedan film
class Movie{

    protected $movieid, $title, $year, $cast, $genres, $comments, $edited, $image;

    function __construct( $movieid, $title, $year, $cast, $genres, $comments, $edited, $image){
        $this->movieid = $movieid;
        $this->title = $title;
        $this->year = $year;
        $this->cast = $cast;
        $this->genres = $genres;
        $this->comments = $comments;
        $this->edited = $edited;
        $this->image = $image;
    }

    function __get( $prop ) { return $this->$prop; }
    function __set( $prop, $val ) { $this->$prop = $val; return $this; }
}


class movies_service {

    /* ----- DOHVATI 25 ZADNJIH FILMOVA -------- */
    function get_movies(){
        $db = DB::getConnection(); //vrati nmbp bazu
        $collection = $db->movies; //želimo kolekciju movies
        //vrati 25 zadnjih filmova
        $filter = [];
        $options2 = [ 'sort' => ['edited' => -1, 'year' => -1 ], 'limit' => 25 ]; //date of editing + year of creating
        $cursor = $collection->find($filter, $options2);

        $movies = [];
        foreach ($cursor as $document) {
            $doc_image = $this->get_file( $document['imageRef'] );
            $film = new Movie( strval($document['_id']), $document['title'], $document['year'], $document['cast'], $document['genres'], $document['comment'], $document['edited'], $doc_image );
            array_push( $movies, $film);
        }

        return $movies;
    }

    //dohvati sliku
    function get_file( $image_ref ){
        $db = DB::getConnection(); //vrati nmbp bazu
        $bucket = $db->selectGridFSBucket();
        $_id =  new MongoDB\BSON\ObjectId($image_ref);

        $stream = $bucket->openDownloadStream($_id);
        $meta = stream_get_meta_data($stream);

        $wrapper = $meta['wrapper_data']->stream_read(400000);

        return $wrapper;
    }

    /* ------ DODAJ NOVI KOMENTAR -------------- */
    function add_comment($new_comment, $movie_id){
        $db = DB::getConnection(); //vrati nmbp bazu
        $collection = $db->movies; //želimo kolekciju movies

        //dodavanje novog komentara -update
        $_id =  new MongoDB\BSON\ObjectId($movie_id);
        $query = array('_id' => $_id);
        $query2 = array('$addToSet' => array('comment' => $new_comment) );
        $collection->updateOne($query, $query2);

        echo date('c') . '<br>';
        $query3 = array('$set' => array('edited' => date('c')) );
        $collection->updateOne($query, $query3);
    }

    /* ------ MAP REDUCE ----------------------- */
    function stat_a(){
        $db = DB::getConnection(); //vrati nmbp bazu
        $collection = $db->movies; //želimo kolekciju movies
        $map = new \MongoDB\BSON\Javascript('function() { 
            if(this.comment !== undefined ){
                var num = this.comment.length; 
                emit( num, { count : 1} ); 
            }
        }');
        $reduce = new \MongoDB\BSON\Javascript('function(key,values){ 
            var rv = { count : 0 }; 
            values.forEach( function(value){ rv.count += value.count; } ); 
            return rv;  
        } ');
        $out = "movies_one_mr";
        $collection->mapReduce($map, $reduce, $out);

        $result = $db->movies_one_mr;
        return $result->find();
    }

    function stat_b(){
        $db = DB::getConnection(); //vrati nmbp bazu
        $collection = $db->movies; //želimo kolekciju movies
        $map = new \MongoDB\BSON\Javascript('function() { 
        if( this.comment !== undefined ){
            var num = this.comment.length; 
            if( num > 0 ){ 
                emit( 0, { zero : 0, sum : 1, avg : 0 } ); 
            } 
            else{ 
                emit( 0, { zero : 1, sum : 1, avg : 0 } ); 
            } 
        }  }');
        $reduce = new \MongoDB\BSON\Javascript('function(key,values){ 
            var rv = { zero : 0, sum : 0, avg : 0 }; 
            values.forEach( function(value){ 
                rv.zero += value.zero; 
                rv.sum += value.sum; 
            } ); 
            return rv;  
        } ');
        $final = new \MongoDB\BSON\Javascript('function(key,reduced_value){
            reduced_value.avg = (reduced_value.zero / reduced_value.sum ) * 100 ;
            return reduced_value;
        }');
        $out = "movies_two_mr";
        $collection->mapReduce($map, $reduce, $out, [ "finalize" => $final]);

        $result = $db->movies_two_mr;
        return $result->find();
    }

    function stat_c(){
        $db = DB::getConnection(); //vrati nmbp bazu
        $collection = $db->movies; //želimo kolekciju movies
        $map = new \MongoDB\BSON\Javascript('function(){
                var stopwords = [\'i\',\'me\',\'my\',\'myself\',\'we\',\'our\',\'ours\',\'ourselves\',\'you\',\'your\',\'yours\',\'yourself\',\'yourselves\',\'he\',\'him\',\'his\',\'himself\',\'she\',\'her\',\'hers\',\'herself\',\'it\',\'its\',\'itself\',\'they\',\'them\',\'their\',\'theirs\',\'themselves\',\'what\',\'which\',\'who\',\'whom\',\'this\',\'that\',\'these\',\'those\',\'am\',\'is\',\'are\',\'was\',\'were\',\'be\',\'been\',\'being\',\'have\',\'has\',\'had\',\'having\',\'do\',\'does\',\'did\',\'doing\',\'a\',\'an\',\'the\',\'and\',\'but\',\'if\',\'or\',\'because\',\'as\',\'until\',\'while\',\'of\',\'at\',\'by\',\'for\',\'with\',\'about\',\'against\',\'between\',\'into\',\'through\',\'during\',\'before\',\'after\',\'above\',\'below\',\'to\',\'from\',\'up\',\'down\',\'in\',\'out\',\'on\',\'off\',\'over\',\'under\',\'again\',\'further\',\'then\',\'once\',\'here\',\'there\',\'when\',\'where\',\'why\',\'how\',\'all\',\'any\',\'both\',\'each\',\'few\',\'more\',\'most\',\'other\',\'some\',\'such\',\'no\',\'nor\',\'not\',\'only\',\'own\',\'same\',\'so\',\'than\',\'too\',\'very\',\'s\',\'t\',\'can\',\'will\',\'just\',\'don\',\'should\',\'now\'];
                if(this.title !== undefined){
                    var words = [];
                    var one = "";
                    var text = this.title.toLowerCase();
                    for( var i=0; i<text.length; i++ ){
                        if(text[i] === \' \' || text[i] === \',\' || text[i] === \'.\' || text[i] === \'!\' ){
                            if(one !== "" && !stopwords.includes(one) ) words.push(one);
                            one = "";
                        }
                        else one += text[i];
                    }
                    if(one !== "" ) words.push(one);
            
                    var value = {"words" : [], "counts" : [] };
                    words.forEach( function(word){
                        var found = false;
                        var count = 0;
            
                        for(var i = 0; i < value.words.length; i++) {
                            if ( value.words[i] === word) {
                                found = true;
                                break;
                            }
                        }
                        if( found === false ){
                            for(var i = 0; i < words.length; i++) {
                                if (words[i] === word) {
                                    count += 1;
                                }
                            }
                            value.words.push(word);
                            value.counts.push(count);
                        }
                    })
                    emit(this.autor, value);
                }
            }');
        $reduce = new \MongoDB\BSON\Javascript('function(key,values){ 
                var rv = { "words" : [], "counts" : [] };
                values.forEach( function(value){ 
                    var value_words = value.words;
                    var value_counts = value.counts;
                    var found = false;
                    var count = 0;
            
                    for(var i = 0; i < value_words.length; i++) {
                        var ind = -1;
                        for(var j=0; j<rv.words.length; j++ ){ 
                            if(rv.words[j] === value_words[i] ){ 
                                ind = j; 
                                break; 
                            }
                        }
                        if( ind != -1 ){
                            rv.counts[ind] += value_counts[i];
                        }
                        else{
                            rv.words.push(value_words[i]);
                            rv.counts.push(value_counts[i]);
                        }
                    }
                } );
                return rv;
            }');
        $final = new \MongoDB\BSON\Javascript('function(key, reduced_value){
            var words = reduced_value.words;
            var counts = reduced_value.counts;
            var min_count = 1;

            var indexes = [];
            var most_used_words_counts = [];
            for( var i=0; i<counts.length; i++ ){
                if( most_used_words_counts.length < 10 ){
                    most_used_words_counts.push( counts[i] );
                    indexes.push( i );
                }
                else if( most_used_words_counts.length === 10 ){
                    min_count = Math.min(...most_used_words_counts);
                    if( counts[i] > min_count ){
                        var k = Math.min(...most_used_words_counts);;
                        most_used_words_counts[k] = counts[i];
                        indexes[k] = i;
                        min_count = Math.min(...most_used_words_counts);
                    }
                }
                else if( counts[i] > min_count  ){
                    var k = min_count = Math.min(...most_used_words_counts);
                    most_used_words_counts[k] = counts[i];
                    indexes[k] = i;
                }
            }
            var most_used_words = [];
            for (var i = 0; i<indexes.length; i++ ){
                most_used_words.push(words[indexes[i]]);
            }

            reduced_value.words = most_used_words;
            reduced_value.counts = most_used_words_counts;
            return reduced_value;       
        }');
        $out = "movies_three_mr";
        $collection->mapReduce($map, $reduce, $out, [ "finalize" => $final ]);
 /*
        $result = $db->movies_three_mr;
        $cursor = $result->find();

        $return_doc = [];
        //nađi najboljih 10
        foreach ($cursor as $document) {
            $words = $document['value']['words'];
            $counts = $document['value']['counts'];
            $min_count = 1;

            $indexes = [];
            $most_used_words_counts = [];
            for( $i=0; $i<count($counts); $i++ ){
                if( count($most_used_words_counts) < 10 ){
                    array_push($most_used_words_counts, $counts[$i] );
                    array_push($indexes, $i );
                }
                else if( count($most_used_words_counts) === 10 ){
                    $min_count = min($most_used_words_counts);
                    if( $counts[$i] > $min_count ){
                        $k = array_search($min_count, $most_used_words_counts);
                        $most_used_words_counts[$k] = $counts[$i];
                        $indexes[$k] = $i;
                        $min_count = min($most_used_words_counts);
                    }
                }
                else if( $counts[$i] > $min_count  ){
                    $k = array_search($min_count, $most_used_words_counts);
                    $most_used_words_counts[$k] = $counts[$i];
                    $indexes[$k] = $i;
                }
            }

            array_multisort($most_used_words_counts, SORT_DESC, $indexes);
            $most_used_words = [];
            for ($i = 0; $i<count($indexes); $i++ ){
                array_push($most_used_words, $words[$indexes[$i]]);
            }
            $return_doc[$document['_id']] = $most_used_words;
        }
        return $return_doc;*/
        $result = $db->movies_three_mr;
        return $result->find();
    }
};

?>
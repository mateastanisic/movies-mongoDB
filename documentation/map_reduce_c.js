var map = function(){
	var stopwords = ['i','me','my','myself','we','our','ours','ourselves','you','your','yours','yourself','yourselves','he','him','his','himself','she','her','hers','herself','it','its','itself','they','them','their','theirs','themselves','what','which','who','whom','this','that','these','those','am','is','are','was','were','be','been','being','have','has','had','having','do','does','did','doing','a','an','the','and','but','if','or','because','as','until','while','of','at','by','for','with','about','against','between','into','through','during','before','after','above','below','to','from','up','down','in','out','on','off','over','under','again','further','then','once','here','there','when','where','why','how','all','any','both','each','few','more','most','other','some','such','no','nor','not','only','own','same','so','than','too','very','s','t','can','will','just','don','should','now'];
	if(this.title !== undefined){
		var words = [];
		var one = "";
		for( var i=0; i<this.title.length; i++ ){
			if(this.title[i] === ' ' || this.title === ',' || this.title === '.' || this.title === '!' ){
				one = one.toLowerCase();
				if(one !== "" && !stopwords.includes(one) ) words.push(one);
				one = "";
			}
			else one += this.title[i];
		}
		if(one !== "" ) words.push(one);

		var value = {"words" : [], "counts" : []};
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
}

var reduce = function(key,values){ 
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
}

# movies 
[php/javascript/html/css]
WEB APLIKACIJA - nosql projekt iz kolegija NMBP

zadatak se sastojao od dva dijela:
- napraviti minimalni portal temeljen na MongoDB (10 bodova)
- mapReduce upit ( 2 + 2 + 6 = 10 bodova)

## korišteni podaci 
Podatke sam dohvatila sa <a href="https://raw.githubusercontent.com/prust/wikipedia-movie-data/master/movies.json">github repozitorija </a>.  Naredbom

	mongoimport --db nmbp --collection movies --file /path/to/file/filename --jsonArray

sam importala preuzete podatke u lokalnu 'nmbp' bazu s nazivom kolekcije 'movies'.

Podaci se sastoje 28795 objekata, a svaki od objekata je početno imao definirane:
- _id
- title
- year (of making)
- cast (array)
- genres (array)

Radi potreba projekta, dodala sam svakom objektu i:
- comment (array)
- imageRef
- edited 
- autor

Prije izvršavanja samih updejta kolekcije, dodala sam i sljedeće indekse radi bržeg pretraživanja uvijeta.

	db.movies.createIndex({ year : 1 })
	db.movies.createIndex({cast : "text", genres : "text", comment : "text", name : "text"} )

Lokalnu bazu u exportanom json obliku može se preuzeti na <a href="https://github.com/mateastanisic/movies/blob/master/documentation/movies.json"> githubu</a>. Za pokretanje rješenja potrebno je importati gore navedeni .json file u svoju lokalnu bazu i otvoriti *API/index.html* u web pregledniku.


## update kolekcije 

### upload komentara
Neki primjeri linija s kojim sam popunila kolekciju sa komentarima:

	db.movies.update( { cast : "Joaquin Phoenix" }, {'$addToSet': {"comment":"What an experience! It was magnificent and really powerful. Brilliant lead actor."}}, false, true )
	db.movies.update( { cast : "Chris Pratt" }, {'$addToSet': {"comment":"What an experience! It was magnificent and really powerful. Brilliant lead actor."}}, false, true )
	db.movies.update( { cast : "David Tennant" }, {'$addToSet': {"comment":"What an experience! It was magnificent and really powerful. Brilliant lead actor."}}, false, true )
	db.movies.update( { cast : "David Tennant" }, {'$addToSet': {"comment":"I loved him in Doctor Who. I loved him here. He is apsolutely amazing actor!"}}, false, true )
	db.movies.update( { cast : "Lady Gaga" }, {'$addToSet': {"comment":"What an experience! It was magnificent and really powerful. Brilliant lead actor."}}, false, true )
	db.movies.update( { genres : "Drama", year : 2017 }, {'$addToSet': {"comment":"Astonishing performance. It’s a really good movie and worth watching if you are both a fan and a movie lover."}}, false, true )
	db.movies.update( { genres : "Spy", year : { $gt : 1990, $lte : 2010 } }, {'$addToSet': {"comment":"Astonishing performance. It’s a really good movie and worth watching if you are both a fan and a movie lover."}}, false, true )
	db.movies.update( { genres : "Supernatural" }, {'$addToSet': {"comment":"I've just stepped out of the cinema having watched the worst movie of the year. I feel like the director has played me for a fool. I feel like the joke here."}}, false, true )
	db.movies.update( { genres : "Action" }, {'$addToSet': {"comment":"I've just stepped out of the cinema having watched the worst movie of the year. I feel like the director has played me for a fool. I feel like the joke here."}}, false, true )

"Sve" linije s kojima sam dodala komentare nalaze se na <a href="https://github.com/mateastanisic/movies/blob/master/documentation/set_comments.txt"> githubu </a>.

### upload edited time
Radi potrebe projekta, dodala sam i vrijednost kada je zadnji puta dodan komentar na neki film.
Bili su potrebni neki početni podaci, pa sam ih izgenerirala sa:

	db.movies.update( {$text:{$search : "David"}}, {$set: {"edited": "2019-05-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "Mary"}}, {$set: {"edited": "2019-06-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "John"}}, {$set: {"edited": "2019-07-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "Charles"}}, {$set: {"edited": "2019-08-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "Amy"}}, {$set: {"edited": "2019-09-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "James"}}, {$set: {"edited": "2019-10-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "Smith"}}, {$set: {"edited": "2019-12-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "Chris"}}, {$set: {"edited": "2019-11-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "Susan"}}, {$set: {"edited": "2019-04-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "Sarah"}}, {$set: {"edited": "2019-03-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "Scott"}}, {$set: {"edited": "2019-02-04T05:36:33+01:00" }}, false, true )
	db.movies.update( {$text:{$search : "Tom"}}, {$set: {"edited": "2019-01-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Teen" }, {$set: {"edited": "2019-09-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Drama" }, {$set: {"edited": "2019-10-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Comedy" }, {$set: {"edited": "2019-11-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "War" }, {$set: {"edited": "2019-12-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Noir" }, {$set: {"edited": "2017-08-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Wester" }, {$set: {"edited": "2019-07-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Dance" }, {$set: {"edited": "2019-06-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Animated" }, {$set: {"edited": "2019-05-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Comedy" }, {$set: {"edited": "2019-04-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Drama" }, {$set: {"edited": "2019-03-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Romance" }, {$set: {"edited": "2019-02-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Thriller" }, {$set: {"edited": "2019-01-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Action" }, {$set: {"edited": "2018-01-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Mystery" }, {$set: {"edited": "2018-05-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Family" }, {$set: {"edited": "2018-12-04T05:36:33+01:00" }}, false, true )
	db.movies.update( { genres : "Supernatural" }, {$set: {"edited": "2020-01-02T05:36:33+01:00" }}, false, true )

Aplikacija prilikom dodavanja novog komentara za neki film, mjenja i ovu vrijednost tog filma tako da ako dodamo neki komentar za neki film u aplikaciji pri sljedećem "refreshu", taj film nalazit će nam se na prvome mjestu.


### upload images
Slike se nalaze na  <a href="https://github.com/mateastanisic/movies/tree/master/documentation/images"> githubu </a>.

  	mongofiles --db nmbp put -l "/home/matea/Downloads/image.jpg" image.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/image.jpg" image.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/action.jpg" action.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/adventure.jpg" adventure.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/animatead.jpg" animated.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/bio.jpg" bio.jpg
 	 mongofiles --db nmbp put -l "/home/matea/Downloads/slike/comedy.jpg" comedy.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/crime.jpg" crime.jpg
 	 mongofiles --db nmbp put -l "/home/matea/Downloads/slike/dance.jpg" dance.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/disaster.jpg" disaster.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/documentary.jpg" documentary.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/drama.jpg" drama.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/family.jpg" family.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/fantasy.jpg" fantasy.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/foundf.jpg" foundf.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/historical.jpg" historical.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/horror.jpg" horror.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/indie.jpeg" indie.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/legal.jpg" legal.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/live_action.jpg" live_action.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/martial_arts.jpg" martial_arts.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/musical.jpg" musical.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/mystery.jpg" 
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/mystery.jpg" mystery.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/noir.jpg" noir.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/performance.jpg" performance.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/political.jpg" political.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/romance.jpg" romance.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/satire.jpg" satire.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/sf.jpg" sf.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/short.jpg" short.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/silent.jpg" silent.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/slasher.jpg" slasher.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/sport.jpg" sport.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/spy.jpg" spy.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/superhero.jpg" superhero.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/supernatural.jpg" supernatural.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/suspense.jpg" suspense.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/teen.jpg" teen.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/thriller.jpg" thriller.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/war.jpg" war.jpg
  	mongofiles --db nmbp put -l "/home/matea/Downloads/slike/western.jpg" western.jpg

Slike su se pohranile u **fs.files**, odnosno **fs.chunks* kolekcije. Pomoću sljedećih naredbi dodala sam **imageRef* vrijednost za objekte u kolekciji *movies*.

	db.movies.update( {}, {'$set': {"imageRef": ObjectId("5e0f6378ea9483a887c51143") }}, false, true )
	db.movies.update( {genres : "Martial Arts"}, {'$set': {"imageRef": ObjectId("5e0f648d334817627e56e18c") }}, false, true )
	db.movies.update( {genres : "Performance"}, {'$set': {"imageRef": ObjectId("5e0f663ffd6a6d8a65361ac6") }}, false, true )
	db.movies.update( {genres : "Slasher"}, {'$set': {"imageRef": ObjectId("5e0f6688158c0ad6cce7fa3c") }}, false, true )
	db.movies.update( {genres : "Found Footage"}, {'$set': {"imageRef": ObjectId("5e0f6435389903f485d12f05") }}, false, true )
	db.movies.update( {genres : "Action"}, {'$set': {"imageRef": ObjectId("5e0f6394f544833a6e8c0636")}}, false, true )
	db.movies.update( {genres : "Adventure"}, {'$set': {"imageRef": ObjectId("5e0f63a0544c818f6644de60") }}, false, true )
	db.movies.update( {genres : "Animated"}, {'$set': {"imageRef": ObjectId("5e0f63a94b486a79542d0eed") }}, false, true )
	db.movies.update( {genres : "Biography"}, {'$set': {"imageRef": ObjectId("5e0f63b3e51772952efbac2d") }}, false, true )
	db.movies.update( {genres : "Comedy"}, {'$set': {"imageRef": ObjectId("5e0f63becc718e82a99b52ab") }}, false, true )
	db.movies.update( {genres : "Crime"}, {'$set': {"imageRef": ObjectId("5e0f63cfa1b7a8b41d36d2a6") }}, false, true )
	db.movies.update( {genres : "Dance"}, {'$set': {"imageRef": ObjectId("5e0f63da61f818c9c64074fc") }}, false, true )
	db.movies.update( {genres : "Disaster"}, {'$set': {"imageRef": ObjectId("5e0f63e4aef23499221e2333") }}, false, true )
	db.movies.update( {genres : "Documentary"}, {'$set': {"imageRef": ObjectId("5e0f63fffbacc09cc1731ecc") }}, false, true )
	db.movies.update( {genres : "Drama"}, {'$set': {"imageRef": ObjectId("5e0f640b28575597633393c1") }}, false, true )
	db.movies.update( {genres : "Family"}, {'$set': {"imageRef": ObjectId("5e0f6417e8d21afff6af0c64") }}, false, true )
	db.movies.update( {genres : "Fantasy"}, {'$set': {"imageRef": ObjectId("5e0f64265bc3128fceb51d9a") }}, false, true )
	db.movies.update( {genres : "Historical"}, {'$set': {"imageRef": ObjectId("5e0f64409168e494817e4e41") }}, false, true )
	db.movies.update( {genres : "Horror"}, {'$set': {"imageRef": "_id" : ObjectId("5e0f644e0ed4ec630cd77012") }}, false, true )
	db.movies.update( {genres : "Independent"}, {'$set': {"imageRef": ObjectId("5e0f6458fd19f266e396e830") }}, false, true )
	db.movies.update( {genres : "Legal"}, {'$set': {"imageRef": ObjectId("5e0f6466732b55289fa80531") }}, false, true )
	db.movies.update( {genres : "Live Action"}, {'$set': {"imageRef": ObjectId("5e0f6479301d8cfb327491b0") }}, false, true )
	db.movies.update( {genres : "Musical"}, {'$set': {"imageRef": ObjectId("5e0f649db12578635e707b22") }}, false, true )
	db.movies.update( {genres : "Mystery"}, {'$set': {"imageRef": ObjectId("5e0f64ae7b8ae292e0c77da1") }}, false, true )
	db.movies.update( {genres : "Noir"}, {'$set': {"imageRef": ObjectId("5e0f65382219f535c5c6d414") }}, false, true )
	db.movies.update( {genres : "Political"}, {'$set': {"imageRef": ObjectId("5e0f6647165255c98d4029ab") }}, false, true )
	db.movies.update( {genres : "Romance"}, {'$set': {"imageRef": ObjectId("5e0f66551cc8f88f516b30ff") }}, false, true )
	db.movies.update( {genres : "Satire"}, {'$set': {"imageRef": ObjectId("5e0f665e63563d0c5c172d33") }}, false, true )
	db.movies.update( {genres : "Science Fiction"}, {'$set': {"imageRef": ObjectId("5e0f66651bd09c4e0d47c8c7") }}, false, true )
	db.movies.update( {genres : "Short"}, {'$set': {"imageRef": ObjectId("5e0f666e8074df4d2aeaaeb7") }}, false, true )
	db.movies.update( {genres : "Silent"}, {'$set': {"imageRef": ObjectId("5e0f667ab60b6d23f22b3e31") }}, false, true )
	db.movies.update( {genres : "Sport"}, {'$set': {"imageRef": ObjectId("5e0f6694c6c8c4ab49c8e9ee") }}, false, true )
	db.movies.update( {genres : "Sports"}, {'$set': {"imageRef": ObjectId("5e0f6694c6c8c4ab49c8e9ee") }}, false, true )
	db.movies.update( {genres : "Spy"}, {'$set': {"imageRef": ObjectId("5e0f66a110e56495f4ab4dbd") }}, false, true )
	db.movies.update( {genres : "Superhero"}, {'$set': {"imageRef": ObjectId("5e0f66ad9b382b851228c988") }}, false, true )
	db.movies.update( {genres : "Supernatural"}, {'$set': {"imageRef": ObjectId("5e0f66b85c17c08298457054") }}, false, true )
	db.movies.update( {genres : "Suspense"}, {'$set': {"imageRef": ObjectId("5e0f66c1de569263004e706c") }}, false, true )
	db.movies.update( {genres : "Teen"}, {'$set': {"imageRef": ObjectId("5e0f66cb1651e45dabd69746") }}, false, true )
	db.movies.update( {genres : "Thriller"}, {'$set': {"imageRef": ObjectId("5e0f66d517c8c216cecdec7d") }}, false, true )
	db.movies.update( {genres : "War"}, {'$set': {"imageRef": ObjectId("5e0f66dfd0fce480cdf9912a") }}, false, true )
	db.movies.update( {genres : "Western"}, {'$set': {"imageRef": ObjectId("5e0f66e92fb041d690d02cfc") }}, false, true )

### update autora
Ukupno 11 kreiranih autora. (Pretpostavimo, dodali su film u bazu.) Npr.

	db.movies.update( {$text:{$search : "David"}}, {$set: {"autor" : "matea"}}, false, true )
	db.movies.update( {$text:{$search : "Mary"}}, {$set: {"autor" : "sandra"}}, false, true )
	db.movies.update( {$text:{$search : "John"}}, {$set: {"autor" : "mirko"}}, false, true )
	db.movies.update( {$text:{$search : "Charles"}}, {$set: {"autor" : "dinko"}}, false, true )
	db.movies.update( {$text:{$search : "Amy"}}, {$set: {"autor" : "slavko"}}, false, true )
	db.movies.update( {$text:{$search : "James"}}, {$set: {"autor" : "marko"}}, false, true )
	db.movies.update( {$text:{$search : "Smith"}}, {$set: {"autor" : "darko"}}, false, true )
	db.movies.update( {$text:{$search : "Chris"}}, {$set: {"autor" : "vanja"}}, false, true )
	db.movies.update( {$text:{$search : "Susan"}}, {$set: {"autor" : "tanja"}}, false, true )
	db.movies.update( {$text:{$search : "Sarah"}}, {$set: {"autor" : "sanja"}}, false, true )
	db.movies.update( {$text:{$search : "Scott"}}, {$set: {"autor" : "matea"}}, false, true )
	db.movies.update( {$text:{$search : "Tom"}}, {$set: {"autor" : "sandra"}}, false, true )
	db.movies.update( {$text:{$search : "Amelia"}}, {$set: {"autor" : "sanja"}}, false, true )
	db.movies.update( {$text:{$search : "Olivia"}}, {$set: {"autor" : "tanja"}}, false, true )
	db.movies.update( {$text:{$search : "Lily"}}, {$set: {"autor" : "vanja"}}, false, true )
	db.movies.update( {$text:{$search : "Oliver"}}, {$set: {"autor" : "darko"}}, false, true )
	db.movies.update( {$text:{$search : "Jack"}}, {$set: {"autor" : "marko"}}, false, true )
	db.movies.update( {$text:{$search : "Harry"}}, {$set: {"autor" : "slavko"}}, false, true )
	db.movies.update( {$text:{$search : "Michael"}}, {$set: {"autor" : "dinko"}}, false, true )
	db.movies.update( {$text:{$search : "Megan"}}, {$set: {"autor" : "mirko"}}, false, true )
	db.movies.update( { genres : "Teen" }, {$set: {"autor" : "dinko"}}, false, true )
	db.movies.update( { genres : "Drama" }, {$set: {"autor" : "mirko"}}, false, true )
	db.movies.update( { genres : "Comedy" }, {$set: {"autor" : "sandra"}}, false, true )
	db.movies.update( { genres : "War" }, {$set: {"autor" : "matea"}}, false, true )
	db.movies.update( { genres : "Noir" }, {$set: {"autor" : "slavko"}}, false, true )
	db.movies.update( { genres : "Wester" }, {$set: {"autor" : "marko"}}, false, true )
	db.movies.update( { genres : "Dance" }, {$set: {"autor" : "darko"}}, false, true )
	db.movies.update( { genres : "Animated" }, {$set: {"autor" : "vanja"}}, false, true )
	db.movies.update( { genres : "Comedy" }, {$set: {"autor" : "tanja"}}, false, true )
	db.movies.update( { genres : "Drama" }, {$set: {"autor" : "sanja"}}, false, true )
	db.movies.update( { genres : "Romance" }, {$set: {"autor" : "sanja"}}, false, true )
	db.movies.update( { genres : "Thriller" }, {$set: {"autor" : "marko"}}, false, true )
	db.movies.update( { genres : "Action" }, {$set: {"autor" : "slavko"}}, false, true )
	db.movies.update( { genres : "Mystery" }, {$set: {"autor" : "darko"}}, false, true )
	db.movies.update( { genres : "Family" }, {$set: {"autor" : "tanja"}}, false, true )
	db.movies.update( { genres : "Supernatural" }, {$set: {"autor": "matea" }}, false, true )

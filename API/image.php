<?php
    require 'mongo-php-driver/vendor/autoload.php'; // include Composer's autoloader

    $id = '5e0f5a9f5f3ca8e7a2e25b04';
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
    $db = $mongo->nmbp; //returns a nmbp database
    $gridFS = $db->selectGridFSBucket();
    $_id =  new MongoDB\BSON\ObjectId($id);

    //query the file object
    $object = $gridFS->findOne(array('_id' => $_id ) );
    $stream = $gridFS->openDownloadStream($_id);
    $meta = stream_get_meta_data($stream);
    $wrapper = $meta['wrapper_data'];

    //set content-type header, output in browser
    header('Content-type: image/jpg');
    echo $wrapper->context;
    echo 'echooooooooooooo';
?>

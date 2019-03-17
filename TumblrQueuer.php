<?php

/* AUTHOR: LYNOSKA GARCIA
DATE: 3/30/2018
PURPOSE: This program searches through different hashtags for posts add
to the queue. This allows for ease when attempting to keep blog active.
this will add 56 posts to the queue from different hashtags, and lets the
user quickly review them if needed.
*/

include("config.js");

require __DIR__.VENDOR_LOCATION;

//Keys for the application
$consumerKey = $CONSUMER_KEY;
$consumerSecret = $CONSUMER_SECRET;

//Token and Secret that grants access
$token = $TOKEN;
$tokenSecret = $TOKEN_SECRET;

//Assigns key and secret to the client
$client = new Tumblr\API\Client($consumerKey, $consumerSecret, $token, $tokenSecret);
//$clientApiKey = new Tumblr\API\Client($apiKey);

//Name of the user's blog
$blogname = $client->getUserInfo()->user->name;

//Tags that are tracked. KEEP ORDER CONSISTNT TO THE ORDER IN tagAndReblog() !
//If order is not the same, posts will get tag incorrectly
/* $tags = array('throne of glass','acomaf','aftg','studyblr','this savage song',
  'booklr','the raven boys','aedion ashryver','assassins creed 2',
  'dragon age','game of thrones','hamilton the musical',
  'six of crows', 'illuminae', 'queen band', 'kingdom of ash', 'fenrys moonbeam'); */

  /*   $tagsArray = array('tog,throne of glass,sarah j maas,booklr',
                       'acomaf,acotar,sarah j maas,acowar,booklr',
                       'aftg,tfc,tkm,trk,nora sakavic,booklr',
                       'studyblr',
                       'this savage song, tss, victoria schwab, v.e. schwab',
                       'books, booklr, book photography',
                       'the raven boys, the dream thieves, blue lily lily blue, the raven king, trb,trc,bllb,tdt,trk, maggie stiefvater',  'aedion ashryver,aedion,tog,sjm,booklr',
                       'assassins creed 2,AC2, ezio auditore, ezio',
                       'dragon age,da,bioware',
                       'game of thrones, GoT',
                       'hamilton the musical',
                       'six of crows,leigh bardugo, SoC,booklr',
                       'illuminae, amie kaufman, jay kristoff, the illuminae files, booklr, kady grant, ezra mason',
                       'queen, queen band, freddie mercury',
                       'koa, kingdom of ash',
                       'fenrys, fenrys moonbeam, koa, kingdom of ash');
                       */
  $tags = array('throne of glass'=>'tog,throne of glass,sarah j maas,booklr',
                'acomaf'=>'acomaf,acotar,sarah j maas,acowar,booklr',
                'aftg'=>'aftg,tfc,tkm,trk,nora sakavic,booklr',
                'studyblr'=>'studyblr',
                'this savage song'=>'this savage song, tss, victoria schwab, v.e. schwab',
                'booklr'=>'books, booklr, book photography',
                'the raven boys'=>'the raven boys, the dream thieves, blue lily lily blue, the raven king, trb,trc,bllb,tdt,trk, maggie stiefvater',
                'aedion ashryver'=>'aedion ashryver,aedion,tog,sjm,booklr',
                'assassins creed 2'=>'assassins creed 2,AC2, ezio auditore, ezio',
                'dragon age'=>'dragon age,da,bioware',
                'game of thrones'=>'game of thrones, GoT',
                'hamilton the musical'=>'hamilton the musical, lin-manuel miranda',
                'six of crows'=>'six of crows,leigh bardugo, SoC,booklr',
                'illuminae'=>'illuminae, amie kaufman, jay kristoff, the illuminae files, booklr, kady grant, ezra mason',
                'queen band'=>'queen, queen band, freddie mercury',
                'kingdom of ash'=>'koa, kingdom of ash,tog,throne of glass,sarah j maas,booklr',
                'fenrys moonbeam'=>'fenrys, fenrys moonbeam, koa, kingdom of ash,tog,throne of glass,sarah j maas,booklr'
              );




/**
 * Prints out information regarding number of posts you want to reblog, tags
 * being trackes and number of posts per tag that will be queued. This is so
 * that you are aware that it happened and see the confirmation on the screen.
 * This function can be removed, if that's desire, delete this and the calling
 * in line 163
 *
 * @param numOfPosts: amount of posts you want to add to queue
 * @return void
 */
function info($numOfPosts) {
  global $tags, $blogname;

  echo "Tumblr user: $blogname<br><br>";

  //number of tags being tracked
  $size = count($tags);


  echo "<br><br>You wanted $numOfPosts posts to be added to the queue <br>";

  //Distributes, as best as possible, the number of posts coming from each
  //tag. Rounds down to get an int value instead of float/double
  $numPerPost = intval($numOfPosts/$size);

  echo "Number of posts per tag: " . $numPerPost . "<br><br>";
}


/**
 * Searches through tags and adds them to an array to be queued
 *
 * @param numOfPosts: number of posts the user wants to add to the queue
 * @return   toQueue: the array with the posts to be queued
 */
function SearchTag($numOfPosts) {
  //global $client, $tagsSelected;
  global $client, $tags;

  //Array where the selected post to be queued are stored
  $toQueue = array();

  //number of tags being tracked
  //$size = count($tagsSelected);
  $size = count($tags);
  echo "size of tags array: " . count($tags) . "<br />";

  //Distributes, as best as possible, the number of posts coming from each
  //tag. Rounds down to get an int value instead of float/double
  $numPerPost = intval($numOfPosts/$size);

  //**************************************************************************//

  //Loop that searches through the tag and assigns the post to $toQueue
  for($i=0; $i<$size; $i++) {
    //$toQueue[$i] = $client->getTaggedPosts($tagsSelected[$i], array("limit" => $numPerPost));
    //print_r($toQueue[$i]);
    //echo "<br>";
    $toQueue[$i] = $client->getTaggedPosts($tags[$i], array("limit" => $numPerPost));
  }
  return $toQueue;
}

/**
 * Determines the tags that are going to be added the reblogs.
 * then tags each post accordingly and posts it to the queue
 *
 * @param array $arrayPosts: posts that are to be rebloggued to the queue
 * @param int   $numOfPosts: amoutn of posts the user wants add to the queue
 * @return void
 */
function tagAndReblog($arrayPosts, $numOfPosts) {
  global $client, $blogname;

  //KEEP ORDER CONSISTANT TO THE ORDER OF THE TAGS IN LINES 41-42!
  //If order is not the same, posts will get tag incorrectly


  //$TaggedReblogs = array(); //Stores new tagged posts to be reblogged
  $postIdArray = array(); //Stores the ID of the post ot be reblogged
  $reblogKeyArray = array(); //Stores the reblog key for the post to be reblogged

  $size = count($arrayPosts); //number of tags tracked

  //Divides the total number of post wanted by the tags to distribute the posts
  //Forces it to be an int
  $numPerPost = intval($numOfPosts/$size);

  //Gets the URL of the your tumblr
  $blogURL = $blogname . ".tumblr.com";

  $count = 0; //Number of times the tag has been used
  $index = 0; //Array of tags being used at the moment
  $total = 0; //Overall count for tags

  for($j=0; $j<$size; $j++) {
    $temp = $arrayPosts[$j];

    //Separates the ID and the reblog key into individual arrays
    for($i=0; $i<$numPerPost; $i++) {
      $postIdArray[$total] = $temp[$i]->id;
      $reblogKeyArray[$total] = $temp[$i]->reblog_key;

      $total++;
    }
  }

  //Posts to the queue
  for($k=0; $k<(count($postIdArray)); $k++) {
    $count++;
    $currentTag = $tag[$k];
    //$postToReblog = $client->reblogPost($blogURL, $postIdArray[$k], $reblogKeyArray[$k],
    //array('state' => 'queue', 'tags' => $tagsArray[$index]));
    $postToReblog = $client->reblogPost($blogURL, $postIdArray[$k], $reblogKeyArray[$k],
    array('state' => 'queue', 'tags' => $currentTag->$k));

    //Checks that the reblogs are tagged with the appropriate tags accordingly
    //to how many posts are requested by user and the number of tags
    if($count == ($numPerPost)) {
      $count = 0; //reset the count
      $index++; //move onto the next tag
    }
  }
}

//$numOfPosts = $_SESSION["numOfPosts"];
//$tags = $_SESSION["tags"];
//$TagsReturned = SearchTag($numOfPosts);
//tagAndReblog($TagsReturned, $numOfPosts);

//info($numOfPosts);

//TODO: fix the count($tags) is coming out as 0
?>

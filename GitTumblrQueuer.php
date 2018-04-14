<?php

/* AUTHOR: LYNOSKA GARCIA
DATE: 3/30/2018
PURPOSE: This program searches through different hashtags for posts add
to the queue. This allows for ease when attempting to keep blog active.
this will add 56 posts to the queue from different hashtags, and lets the
user quickly review them if needed.
*/

//You have to specify where the vendor/autoload file is
require __DIR__.'XXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

//Keys for the application
$consumerKey = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
$consumerSecret = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

//Token and Secret that grants access
$token = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
$tokenSecret = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

//Assigns key and secret to the client
$client = new Tumblr\API\Client($consumerKey, $consumerSecret, $token, $tokenSecret);

//Name of the user's blog
$blogname = $client->getUserInfo()->user->name;

echo "Tumblr user: $blogname<br><br>";

//Tags that are tracked. KEEP ORDER CONSISTNT TO THE ORDER IN tagAndReblog() !
//If order is not the same, posts will get tag incorrectly
$tags = array('throne of glass','acomaf','aftg','studyblr','black panther',
 'booklr','svthsa');

print_r($tags);
echo "<br>";


/**
 * Searches through tags and adds them to an array to be queued
 *
 * @param numOfPosts: number of posts the user wants to add to the queue
 * @return   toQueue: the array with the posts to be queued
 */
function SearchTag($numOfPosts) {
  global $client, $tags;

  //Array where the selected post to be queued are stored
  $toQueue = array();

  //number of tags being tracked
  $size = count($tags);
  echo $size . " tags are being tracked. <br>";

  //Distributes, as best as possible, the number of posts coming from each
  //tag. Rounds down to get an int value instead of float/double
  $numPerPost = intval($numOfPosts/$size);

  echo "Number of posts per tag: " . $numPerPost . "<br><br>";

  //Loop that searches through the tag and assigns the post to $toQueue
  for($i=0; $i<$size; $i++) {
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
  $tagsArray = array('tog,throne of glass,sarah j maas','acomaf,acotar,sarah j maas,acowar',
  'aftg,tfc,tkm,trk,nora sakavic','studyblr','black panther,wakanda forever,tchalla,marvel',
  'books,booklr','simon spier,bram greenfeld,svthsa,love simon');

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

    $postToReblog = $client->reblogPost($blogURL, $postIdArray[$k], $reblogKeyArray[$k],
    array('state' => 'queue', 'tags' => $tagsArray[$index]));

    //Checks that the reblogs are tagged with the appropriate tags accordingly
    //to how many posts are requested by user and the number of tags
    if($count == ($numPerPost)) {
      $count = 0; //reset the count
      $index++; //move onto the next tag
    }
  }
}

$numOfPosts = 56;
$TagsReturned = SearchTag($numOfPosts);
tagAndReblog($TagsReturned, $numOfPosts);

?>

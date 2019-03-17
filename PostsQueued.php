<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Posts Queued!</title>
</head>

<?php

include("stylesheet.css");
include("TumblrQueuer.php");

?>

<br>
<body>

  <div id="content" class="content">
    <h2>Posts Queued!</h2>
    <div class="form-confirmation">

      <!-- <span class="label-input">Total number of posts queued:</span> -->
      <section class="total-posts-req">
        <div class="form-output total-posts">
          <span class="label-output total-posts">Total number of queued posts requested<br></span>
        </div>

        <div class="result total-posts">
          <?php echo $_POST["numOfPosts"];?>
        </div>
      </section>

      <div class="form-output">
        <span class="label-output">Tags selected<br></span>

        <!-- TODO: Add checkboxes already checked -->
        <div class="result tags-queued">
          <?php

            //$tagsSelected = array();
            //base string for the tags. name values are formatted as: name="tag-#"
            $t = "tag-";
            $tagsCount = 0;

            //removes the need to print all the tags individually
            for($i=0; $i<count($tags); $i++) {
              //name="tag-#"  starts at 1
              $temp = $t . ($i+1);

              if(isset($_POST[$temp])) {
                echo "<div class='result-indiv-tags'>";
                //array_push($tagsSelected, $tags[$i]);
                print_r($tags[$i]);

                $tagsCount++;
                echo "</div>";
              }
            }
            echo "<br>";
            ?>
          </div>

      </div>

      <section class="post-per-tag-req">
        <div class="form-output post-per-tag">
          <span class="label-output">Number of posts per tag</span>
        </div>
        <div class="result post-per-tag">
          <?php
            $postsPerTag = intval($_POST["numOfPosts"]/$tagsCount);
            echo $postsPerTag;
          ?>
        </div>
      </section>

      <div class="form-output-total">
        <span class="label-output">In total, <u><?php echo ($postsPerTag*$tagsCount); ?></u> posts were queued</span>
      </div>

      <div class="submit">
        <a href="./"><button type="submit" class="button">Queue More Posts!</button></a>
      </div>

    </div>


  </div>

</body>
</html>

<?php
  $_SESSION["numOfPosts"] = $_POST["numOfPosts"];
  //$_SESSION["tags"] = $tags;
?>

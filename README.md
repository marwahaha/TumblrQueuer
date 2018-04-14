# TumblrQueuer

This program selects post from tags pre-specified and adds them to the queue.
It allows for users to keep their blogs active easier as they might only need
to review the posts rather than select them one by one.

The number of posts that are added to the queue is specified in line **160**.
Keep in mind that if the number of posts wanted is less than the number of tags
being tracked, it will not post anything because the program distributes the
number of wnated posts by the tags tracked to attempt to evenly add posts.
Therefore, it will result in a result of *0* and nothing will be posted.

The tags tracked are specified in line **30** in the ```($tags)``` variable and
the tags to be added to the reblogged posts are specified in lines **110-112**
in the ```($tagsArray)``` variable.

**WARNING:** If the tags tracked ```($tags)``` order does not match the order of the
tags to be added to the reblogged posts ```($tagsArray)```, it will tag the posts
improperly. The tags will not match the posts!

## Requirements:
* You must have an application registered through Tumblr. You can register one by
going to (https://www.tumblr.com/oauth/apps).
  * Update the ```$consumerKey```,```$consumerSecret```, ```$token```, and
  ```$tokenSecret``` variables with your keys
* You must have the ```vendor/autoload``` file obtained through composer. To get
it go to: (https://getcomposer.org).
  * Update line 12 with the location of your *autoload* file. You only need the
  relative path

## Notes:
* The ```info()``` function is simply there so that you can tell it ran successfully.
You may remove it if you wish. If so, delete lines *34-50* and *161*.
* This program uses the Tumblr PHP client (https://github.com/tumblr/tumblr.php)
for the Tumblr API (https://tumblr.com/docs/en/api/v2)

### Inspiration:
* https://github.com/Lapomeray/Simple-Tweet

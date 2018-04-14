# TumblrQueuer

This program selects post from tags pre-specified and adds them to the queue.
It allows for users to keep their blogs active easier as they might only need
to review the posts rather than select them one by one.

The number of posts that are added to the queue is specified in line **160**.
Keep in mind that if the number of posts wanted is less than the number of tags
being tracked, it will not post anything because the program distributes the
number of wnated posts by the tags tracked to attempt to evenly add posts.
Therefore, it will result in a result of *0* and nothing will be posted.

The tags tracked are specified in line **30** and the tags to be added to the
reblogged posts are specified in lines **110-112**.
**WARNING** If the tags tracked ($tags) order does not match the order of the
tags to be added to the reblogged posts ($tagsArray), it will tag the posts
improperly. The tags will not match the posts!

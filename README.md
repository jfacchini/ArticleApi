Article API
===========

This project is a demonstration purpose.

It contains an API that :
* Create an article
* Create a comments (related to an article)
* Create a rate in the range of [0 - 5]
* Compute the average rating
* Retrieve an article with all its comments
* Send an email to the author of a given article if he received comments for the last 24 hours

# Runing tests

    $ ./bin/phpunit -c app src/

# Fixture loading

    $ ./app/console d:f:l

# Runing notification command

    $ ./app/console blog:notify:user {article_id}
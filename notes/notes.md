# section 2 lecture 5 
- flexbox layout (google search term under videos)
    - https://www.youtube.com/watch?v=k32voqQhODc
    - This explains the styling of "display: flex"


# section 2 lecture 8
- jquery google cdn (google search term)
    - https://developers.google.com/speed/libraries/
- get bootstrap (google search term)
    - https://getbootstrap.com/


# section 2 lecture 10
- icon 8 (google search)
    - https://icons8.com/


# section 3 lecture 17
    - We use a class for creating the upload form so we don't violate "DRY."
    - This is a link to the [forms page](https://getbootstrap.com/docs/4.0/components/forms/).


# section 3 lecture 18 
    - This is a link to the page with [input examples](https://getbootstrap.com/docs/4.0/components/forms/).


# section 3 lecture 19
    - This is a link to the page with [select box examples](https://getbootstrap.com/docs/4.0/components/forms/).

# section 3 lecture 20
    - We used phpMyAdmin.
        - First we went to the "New" to generate a database called, "VideoTube."
        - After this we selected the database and created a table called "categories."
            - While I was watching this video I thought to myself about naming it "Category" but that is a topic for
            another day in regards to the naming convention and something I'm not worried about for this project.
        - After this I used the "Insert" tab to add in the sample categories.
        - After this I would view the inserted rows by viewing the "Browse" tab.    
         
# section 3 lecture 21
    - ob_start(): a way to prevent the output and to later output (after code is executed).
    - http://php.net/manual/en/function.date-default-timezone-set.php
        - clicking on List of Supported Timezones. should lead [here](http://php.net/manual/en/timezones.php)
        - following that clicking on "America" should lead [here](http://php.net/manual/en/timezones.america.php).
    - http://php.net/manual/en/pdo.error-handling.php
        - documentation for PDO::ATTR_ERRMODE.    
        
# section 3 lecture 23
    - $con error is due to the concept of scope.
        - our function createCategoriesInput() only looks for $con inside of the local scope.
        - we resolved this issue by creating a constructor to create the $con object which can be referenced
        in our createCategoriesInput() function.
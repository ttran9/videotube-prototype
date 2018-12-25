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
    
    
# section 4 lecture 29
- the upload process is the following..
    - we take the video that's uploaded and move it into our videos directory.
    - we then convert it (to mp4).
    - we replace it with another file (we delete the old one and keep the new one, mp4 one).
    - the above steps are done because not all browsers support all uploaded video types.
    
    
# section 4 lecture 32
- it wasn't shown in the video but I came across error code: 1 (the file size was too large) so I had to go into the
php/php.ini and modify the post_max_size=2M to 8M because I was trying to upload a file that was too large.


# section 4 lecture 35
- Link to [ffmpeg](https://www.ffmpeg.org/).
    - This helps with converting the video file, can also convert audio files too.
- I added ffmpeg to the .gitignore because I did not want to force the user into having a certain platform dependent
version of ffmpeg.
    - The user can just download it at ffmpeg's website.
    
    
# section 4 lecture 37
- note using the $ffmpegPath member for windows requires getting the full path name.
- the php.ini on a mac would be inside of the xamppfiles/etc directory.
    - in this lecture we modified the execution time from the default of 30 seconds to 3000.
    - we also modified the max_filesize=2M to 128M.
    
    
# section 4 lecture 39
- Commit cf018149ab7b40cf0feb7a7108df22545dc444bb has a typo which causes the command to not even be run, I have fixed
this syntactical error since then.


# section 4 lecture 42
- Documentation for [probe](https://trac.ffmpeg.org/wiki/FFprobeTips).
    - The with the [command](https://trac.ffmpeg.org/wiki/FFprobeTips#Formatcontainerduration).
    
    
# section 4 lecture 46
- Documentation for [modals](https://getbootstrap.com/docs/4.0/components/modal/).
    - The vertical centered modal is [here](https://getbootstrap.com/docs/4.0/components/modal/#vertically-centered).
-  data-backdrop="static": prevents the user from clicking out of the modal to close the it (the dialog-like box).
-  data-keyboard="false": prevents the user from closing the modal through keyboard stroke(s).


# section 5 lecture 70.
- session_destroy(); // destroys entire session.
- unset($_SESSION["userLoggedIn"]); // only destroys this one session variable.


# section 7 lecture 98.
- one thing I struggled with while watching the video was I forgot to use the class selector when using the "likeButton.find('text')"
instead of "likeButton.find('.text')" and when debugging I should've realized quicker when my element.text() was
not returning a value but rather an empty string.


# section 8 lecture 103
- Documentation for date formatting [here](http://php.net/manual/en/function.date.php).


# section 9 lecture 128
- Reference for the function we used in this video is [here](https://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago).
- some notes for the function time_elapsed_string:
    - I struggled a lot with this but I decided to format the string to look at the diff values and realized for the 
    US/Los_Angeles timezone was giving me a negative year of nearly two years so that's what ultimately made me
    decide to swap how I was evaluating the $diff variable.
    - I must also add I'm not sure why swapping the $ago and $now variables in the $diff function call resolve my issue.
    - I tried other timezones and they did not require this "hack" solution.
    - I tried America/Denver, America/New_York, Pacific/Honolulu, and America/Phoenix as well as modifying php.ini "date.timezone" under
    the [Date] section.
    
# section 9 lecture 137
- In regards to this line of code..
    - $(button).parent().siblings("." + containerClass).append(comment);
        - What we do here is we take the "button" (which has the text "REPLY").
        - We then go to its parent which is the div with a class of "commentForm."
        - We get the siblings which have the class "repliesSection" and then adding it to the end of that container.
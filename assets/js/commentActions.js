function postComment(button, postedBy, videoId, replyTo, containerClass) {
    var textarea = $(button).siblings("textarea");
    var commentText = textarea.val();
    textarea.val(""); // clear the text.

    if(commentText) {

    } else {
        alert("You can't post an empty comment.");
    }
}
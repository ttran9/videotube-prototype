function postComment(button, postedBy, videoId, replyTo, containerClass) {
    var textarea = $(button).siblings("textarea");
    var commentText = textarea.val();
    textarea.val(""); // clear the text.

    if(commentText) {
        $.post("ajax/postComment.php", {commentText: commentText, postedBy: postedBy, videoId: videoId,
                responseTo: replyTo})
        .done(function(comment) {
            $("." + containerClass).prepend(comment);
        });
    } else {
        alert("You can't post an empty comment.");
    }
}

function toggleReply(button) {
    var parent = $(button).closest(".itemContainer");
    var commentForm = parent.find(".commentForm").first();

    commentForm.toggleClass("hidden");
}

function likeComment(commentId, button, videoId) {
    $.post("ajax/likeComment.php", {videoId: videoId, commentId: commentId})
        .done(function(data) {

            var likeButton = $(button); // create a jquery object from the javascript button (to be able to use jquery functions).
            var dislikeButton = $(button).siblings(".dislikeButton");

            likeButton.addClass("active");
            dislikeButton.removeClass("active");

            var result = JSON.parse(data);
            updateLikesValue(likeButton.find(".text"), result.likes);
            updateLikesValue(dislikeButton.find(".text"), result.dislikes);

            if(result.likes < 0) {
                likeButton.removeClass("active");
                likeButton.find("img:first").attr("src", "assets/images/icons/thumb-up.png"); // find the first image and stop.
            } else {
                likeButton.find("img:first").attr("src", "assets/images/icons/thumb-up-active.png");
            }
            dislikeButton.find("img:first").attr("src", "assets/images/icons/thumb-down.png");
        });
}

function dislikeComment(commentId, button, videoId) {

}
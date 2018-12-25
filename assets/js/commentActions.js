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
        .done(function(numToChange) {

            var likeButton = $(button); // create a jquery object from the javascript button (to be able to use jquery functions).
            var dislikeButton = $(button).siblings(".dislikeButton");

            likeButton.addClass("active");
            dislikeButton.removeClass("active");

            var likesCount = $(button).siblings(".likesCount");
            updateLikesValue(likesCount, numToChange);

            if(numToChange < 0) {
                likeButton.removeClass("active");
                likeButton.find("img:first").attr("src", "assets/images/icons/thumb-up.png"); // find the first image and stop.
            } else {
                likeButton.find("img:first").attr("src", "assets/images/icons/thumb-up-active.png");
            }
            dislikeButton.find("img:first").attr("src", "assets/images/icons/thumb-down.png");
        });
}

function dislikeComment(commentId, button, videoId) {
    $.post("ajax/dislikeComment.php", {videoId: videoId, commentId: commentId})
        .done(function(numToChange) {

            var dislikeButton = $(button); // create a jquery object from the javascript button (to be able to use jquery functions).
            var likeButton = $(button).siblings(".likeButton");

            dislikeButton.addClass("active");
            likeButton.removeClass("active");

            var likesCount = $(button).siblings(".likesCount");
            updateLikesValue(likesCount, numToChange);

            if(numToChange > 0) {
                dislikeButton.removeClass("active");
                dislikeButton.find("img:first").attr("src", "assets/images/icons/thumb-down.png"); // find the first image and stop.
            } else {
                dislikeButton.find("img:first").attr("src", "assets/images/icons/thumb-down-active.png");
            }
            likeButton.find("img:first").attr("src", "assets/images/icons/thumb-up.png");
        });
}

function updateLikesValue(element, num) {
    var likesCountVal = element.text() || 0;
    element.text(parseInt(likesCountVal) + parseInt(num));
}

function getReplies(commentId, button, videoId) {
    $.post("ajax/getCommentReplies.php", {commentId: commentId, videoId:videoId })
        .done(function(comments) {
            var replies = $("<div>").addClass("repliesSection");
            replies.append(comments);
            $(button).replaceWith(replies);
        });

}
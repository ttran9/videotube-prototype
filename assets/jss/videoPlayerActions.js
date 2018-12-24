function likeVideo(button, videoId) {
    $.post("ajax/likeVideo.php", {videoId: videoId})
    .done(function(data) {

        var likeButton = $(button); // create a jquery object from the javascript button (to be able to use jquery functions).
        var dislikeButton = $(button).siblings(".dislikeButton");

        likeButton.addClass("active");
        dislikeButton.removeClass("active");
    });
}
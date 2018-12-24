function likeVideo(button, videoId) {
    $.post("ajax/likeVideo.php", {videoId: videoId})
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

function dislikeVideo(button, videoId) {
    $.post("ajax/dislikeVideo.php", {videoId: videoId})
        .done(function(data) {

            var dislikeButton = $(button); // create a jquery object from the javascript button (to be able to use jquery functions).
            var likeButton = $(button).siblings(".likeButton");

            dislikeButton.addClass("active");
            likeButton.removeClass("active");

            var result = JSON.parse(data);
            updateLikesValue(likeButton.find(".text"), result.likes);
            updateLikesValue(dislikeButton.find(".text"), result.dislikes);

            if(result.dislikes < 0) {
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
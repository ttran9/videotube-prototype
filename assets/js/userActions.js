function subscribe(userTo, userFrom, button) {
    if(userTo == userFrom) {
        alert("You can't subscribe to yourself");
        return ;
    }
    $.post("ajax/subscribe.php", {userTo: userTo, userFrom: userFrom})
    .done(function(count) {
        if(count != null) {
            $(button).toggleClass("subscribe unsubscribe"); // adds the other class and removes the other class (inverts the classes).
            var buttonText = $(button).hasClass("subscribe") ? "SUBSCRIBE" : "SUBSCRIBED";
            $(button).text(buttonText + " " + count);
        } else {
            // null would mean there is no data returned. unlikely to be happen.....
            alert("something went wrong");
        }
    });
}
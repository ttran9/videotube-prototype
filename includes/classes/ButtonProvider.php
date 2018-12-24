<?php
class ButtonProvider {

    public static $signInFunctino = "notSignedIn()";

    public static function createLink($link) {
        return User::isLoggedIn() ? $link : ButtonProvider::$signInFunctino;
    }

    public static function createButton($text, $imageSrc, $action, $class) {
        $image = $imageSrc == null ? "" : "<img src='$imageSrc'/>";

        // change action if needed
        $action = ButtonProvider::createLink($action);

        return "<button class='$class' onclick='$action'>
                    $image
                    <span class='text'>$text</span>
                </button>";
    }
}

?>
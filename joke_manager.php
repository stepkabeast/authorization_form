<?php
class JokeManager
{
    public static function getRandomJoke()
    {
        $apiUrl = 'https://official-joke-api.appspot.com/jokes/random';
        $response = file_get_contents($apiUrl);
        return json_decode($response, true);
    }
}
?>

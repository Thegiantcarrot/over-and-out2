 <?php
// Start the session
session_start();

// Generate a random word for verification
$word = generateRandomWord();

// Store the word in the session for validation
$_SESSION['captcha_word'] = $word;

// Set the content type to display the image
header('Content-type: image/png');

// Create the image
$image = imagecreatetruecolor(200, 50);

// Set the background color
$background = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $background);

// Set the text color
$textColor = imagecolorallocate($image, 0, 0, 0);

// Draw the word on the image
imagettftext($image, 20, 0, 10, 35, $textColor, '/src/Fonts/TheBomb-7B9gw.ttf', $word);

// Output the image as PNG
imagepng($image);
imagedestroy($image);

/**
 * Generate a random word for verification
 *
 * @return string
 */
function generateRandomWord()
{
    // Define the characters to be used in the word
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    // Define the length of the word
    $length = 6;

    $word = '';
    for ($i = 0; $i < $length; $i++) {
        // Generate a random index to pick a character from the pool
        $index = mt_rand(0, strlen($characters) - 1);

        // Append the character to the word
        $word .= $characters[$index];
    }

    return $word;
}

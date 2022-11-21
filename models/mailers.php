<?php
class Mailers
{

    public static function welcome_template($message)
    {
        $value =
            `<p>
        $message
        </p>`;
        return $value;
    }

    public static function strip_zeros_from_date($marked_string = "")
    {
        // first remove the marked zeros
        $no_zeros = str_replace('*0', '', $marked_string);
        // then remove any remaining marks
        $cleaned_string = str_replace('*', '', $no_zeros);
        return $cleaned_string;
    }
    public static function isEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public static function getRandom()
    {
        mt_srand((float) microtime() * 1000000);
        $rnd = mt_rand(100, 999);
        return $rnd;
    }

    public static function getStateDigits($digit)
    {
        $dig = null;
        switch (strlen($digit)) {
            case 1:
                $dig = '000' . $digit;
                break;
            case 2:
                $dig = '00' . $digit;
                break;
            case 3:
                $dig = '0' . $digit;
                break;
            case 4:
                $dig = $digit;
                break;
            case (strlen($digit) > 4):
                $dig = substr($digit, 0, 4);
                break;
        }
        return $dig;
    }

    public static function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d->format('Y-m-d');
    }
    // Return certain number of words from a given string.
    public static function return_words($text, $length)
    {
        if (strlen($text) > $length) {
            $text = substr($text, 0, strpos($text . ' ', ' ', $length)) . "...";
        }
        return $text;
    }

    public static function isXHR()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }


    // generating unique keys for  sessions and token
    public static function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    public static function getUnique($length, $type = 'ad')
    { // d - digit, a - alphabet, ad -both
        $token = "";
        $codeAlphabet = "";
        switch ($type) {
            case 'ad':
                $codeAlphabet .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
                $codeAlphabet .= "0123456789";
                break;
            case 'a':
                $codeAlphabet .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
                break;
            case 'd':
                $codeAlphabet .= "0123456789";
                break;
        }

        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::crypto_rand_secure(0, $max - 1)];
        }

        return $token;
    }
    // utb
    public static function isYouTube($url)
    {
        $rx = '~^(?:https?://)?(?:www\.)?(?:youtube\.com|youtu\.be)/watch\?v=([^&]+)~x';
        return preg_match($rx, $url, $matches);
    }
    // time function	
    public static function moment()
    {
        $dt = time();
        $mysql_datetime = strftime("%Y-%m-%d %H:%M:%S", $dt);
        $newDateTime = date('d-M-Y h:i A', strtotime($mysql_datetime));
        return $newDateTime;
    }
    // remove extension
    public static function removeExtension($name)
    {
        return substr($name, 0, strrpos($name, "."));
    }

    // remove extension
    // filename = the file path to the file to deleteFile
    // return boolean whether file has been removed or not.
    public static function deleteFile($filename)
    {
        //try to force symlinks
        if (is_link($filename)) {
            $sym = @readlink($filename);
            if ($sym) {
                return is_writable($filename) && @unlink($filename);
            }
        }

        //try to use realpath
        if (realpath($filename) && realpath($filename !== $filename)) {
            return is_writable($filename) && @unlink(realpath($filename));
        }

        // default unlink
        return is_writable($filename) && @unlink($filename);
    }

    public static function upload_file($fn, $path = "../../media/category/", $ppath = "../../media/category/resized/")
    {
        $validate = new Validate();
        foreach ($_FILES['file']['name'] as $index => $files) {
            $temp = explode(".", $_FILES["file"]["name"][$index]);
            $fname = $fn;
            $newfilename = $fname . '.' . end($temp);
            // check path
            $path = (file_exists($path) && is_writeable($path)) ? $path : (mkdir($path, 0777, true) ? $path : "../../media/");
            // preview path
            $prevPath = (file_exists($ppath) && is_writeable($ppath)) ? $ppath : (mkdir($ppath, 0777, true) ? $ppath : "../../media/resized");
            // move and create preview
            if (move_uploaded_file($_FILES["file"]["tmp_name"][$index], $path . $newfilename) && $validate->imagePreviewSize($path . $newfilename, $prevPath, $fname, 250, 250)) {
                // && $validate->imagePreviewSize($path.$newfilename, $prevPath, $fname, 400, 400)
                $image = $newfilename;
            }
        }
        return $image;
    }
}

<?php
require_once("../core/init.php");
$user = new User();

$method = $_SERVER['REQUEST_METHOD'];

function echo_file($file)
{

    // read file object
    if (is_string($file)) $file = read_file($file);

    // something went wrong while reading the file
    if (!$file) http_response_code(500);

    // Allow to read Content Disposition (so we can read the file name on the client side)
    header('Access-Control-Expose-Headers: Content-Disposition, Content-Length, X-Content-Transfer-Id');
    header('Content-Type: ' . $file['type']);
    header('Content-Length: ' . $file['length']);
    header('Content-Disposition: inline; filename="' . $file['name'] . '"');
    echo isset($file['content']) ? $file['content'] : read_file_contents($file['tmp_name']);
}

function read_file($filename)
{
    $handle = fopen($filename, 'r');
    if (!$handle) return false;
    $content = fread($handle, filesize($filename));
    fclose($handle);
    if (!$content) return false;
    return array(
        'tmp_name' => $filename,
        'name' => basename($filename),
        'content' => $content,
        'type' => mime_content_type($filename),
        'length' => filesize($filename),
        'error' => 0
    );
}

function read_file_contents($filename)
{
    $file = read_file($filename);
    if (!$file) return false;
    return $file['content'];
}

function parseInput()
{
    $data = file_get_contents("php://input");

    if ($data == false)
        return array();

    parse_str($data, $result);

    return $result;
}

switch ($method) {
    case 'GET':
        $src = '../assets/images/menus/' . json_decode($_GET['fetch'])->source;
        echo_file($src);
        break;
    case 'POST': // Upload Post
        if (!empty($_FILES) && $_FILES['file']['error'] === 0) {
            $upload = new Upload($_FILES['file']);
            if ($upload->uploaded) {
                // Resize
                $upload->image_resize = true;
                $upload->image_ratio_x = true;
                $upload->image_y = 800;

                $upload->file_overwrite = true;
                $upload->dir_auto_create = true;
                $upload->png_compression = 5;
                $upload->jpeg_quality = 50;
                $upload->file_new_name_body = Helpers::slugify(Input::get('title'));
                $upload->file_name_body_add = '-' . $user->data()->uid . '-' . Helpers::getUnique(2, 'a');
                $upload->process("../assets/images/menus/");

                if ($upload->processed) {
                    $session_images = Session::exists('menu_images') ? Session::get('menu_images') : null;
                    if (Session::exists('menu_images')) {
                        $menu_images = Session::get('menu_images');

                        if (!in_array($upload->file_dst_name, $menu_images)) {
                            array_push($menu_images, trim($upload->file_dst_name));
                            Session::put('menu_images', $menu_images);
                        }
                    } else {
                        Session::put('menu_images', array(trim($upload->file_dst_name)));
                    }
                    // $menu_images = $session_images ? array_push($menu_images, $upload->file_dst_name) : array($upload->file_dst_name);



                    // print_r(Session::get('menu_images'));

                    print_r($upload->file_dst_name);
                } else {
                    // send error here.
                }
            }
        }
        break;
    case 'PUT':
        $_PUT = parseInput();

        echo "PUT request method\n";
        echo print_r($_PUT, true);
        break;
    case 'DELETE': // Remove Post
        $_DELETE = parseInput();

        $image = str_replace(array('_jpg', '_png', '_gif', '_webp'), array('.jpg', '.png', '.gif', '.webp'), array_keys($_DELETE)[0]);
        if (Session::exists('menu_images')) {
            $menu_images = Session::get('menu_images');
            $pos = array_search($image, $menu_images);

            unset($menu_images[$pos]);
            Session::put('menu_images', $menu_images);
        }

        $path = "../assets/images/menus/" . $image;
        Helpers::deleteFile($path);
        // print_r($image); die;
        break;
    default:
        echo "Unknown request method.";
        break;
}

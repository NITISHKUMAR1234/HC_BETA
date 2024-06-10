<?php
$pixel = $_GET['px'] ?? '';
$percent = $_GET['p'] ?? '';
$imageURL = $_GET['url'] ?? '';
$quality = $_GET['q'] ?? '';
$type = strtolower($_GET['type']);

echo showImage($imageURL, $percent, $pixel, $quality, $type);

function showImage($thumbPath, $percent, $px = "", $q = "", $type = "") {
    if ($thumbPath) {
        if (preg_match('/http(s?)\:\/\//i', $thumbPath)) {
            $thumbPath = parse_url($thumbPath, PHP_URL_PATH);
        }
        $root = $_SERVER['DOCUMENT_ROOT'] . '/';
        if (preg_match('/_resize2x/', $thumbPath)) {
            $action = 'resize2x';
        } else {
            $action = 'resize';
        }
        $lastString = strstr($thumbPath, '_' . $action);
        $string = explode('_', $lastString);
        $string = pathinfo(end($string), PATHINFO_FILENAME);
        $resize_w = explode('x', $string)[0];
        $resize_h = explode('x', $string)[1];

        $thumbPath = $root . str_replace($lastString, "", $thumbPath);
        $file = $thumbPath;
        if (is_file($file . '.jpg')) {
            $thumbPath = $file . '.jpg';
        }
        if (is_file($file . '.jpeg')) {
            $thumbPath = $file . '.jpeg';
        }
        if (is_file($file . '.png')) {
            $thumbPath = $file . '.png';
        }
        if (is_file($file . '.bmp')) {
            $thumbPath = $file . '.bmp';
        }
        $imageAllow = array("jpg", "jpeg", "png", "gif", "webp");
        if (!$type) {
            $type = 'webp';
        }
        if (!$q) {
            $q = 85;
        }
        $w = $resize_w;
        $h = $resize_h;
        $info = @getimagesize($thumbPath);
        list($width, $height) = $info;
        if ($width) {
            if (!$h && $w) {
                $newwidth = $w;
                $newheight = $w * $height / $width;
            }
            if (!$w && $h) {
                $newheight = $h;
                $newwidth = $h * $height / $width;
            }
            if ($w && $h) {
                $newwidth = $w;
                $newheight = $h;
            }

            if ($action == 'resize2x') {
                $newwidth = $width * (($newwidth * 2.1) / $width);
                $newheight = $height * (($newheight * 2.1) / $height);
            }
        }
        $ext = strtolower(pathinfo($thumbPath, PATHINFO_EXTENSION));

        $mime = $info['mime'];
        if ($ext == 'webp') {
            $mime = 'image/webp';
        }
        switch ($mime) {
            case 'image/jpeg':
                $source = @imagecreatefromjpeg($thumbPath);
                break;
            case 'image/png':
                $source = @imagecreatefrompng($thumbPath);
                break;
            case 'image/gif':
                $source = @imagecreatefromgif($thumbPath);
                break;
            case 'image/webp':
                $source = @imagecreatefromwebp($thumbPath);
                break;
        }
        $ouput_mime = "image/$type";
        if ($type == 'jpg' || $type == 'jpeg') {
            $ouput_mime = "image/jpeg";
        }
        $thumb = @imagecreatetruecolor($newwidth, $newheight);

        if ($mime == 'image/png') {
            $type = 'png';
            @imagealphablending($thumb, true);
            @imagesavealpha($thumb, true);
            $color = @imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            @imagefill($thumb, 0, 0, $color);
        }

        @imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Generate the data URI
        ob_start();
        if ($type == 'png') {
            imagepng($thumb);
        } elseif ($type == 'jpg') {
            imagejpeg($thumb);
        } elseif ($type == 'gif') {
            imagegif($thumb);
        } else {
            imagewebp($thumb, NULL, $q);
        }
        $imageData = ob_get_clean();

        // Output the data URI
        $dataURI = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
        imagedestroy($thumb);

        return $dataURI;
    }
}
?>

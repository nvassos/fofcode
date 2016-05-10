<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
include 'config.php';
include 'simple-ajax-uploader/Uploader.php';
include 'super_pdo/src/database.php';
include 'resize/resize-class.php';

$uploadType = $_GET['uploadType'];
$uploadData = isset($_POST['uploadData']) ? $_POST['uploadData'] : '';

chdir('../');
$upload_dir = 'uploads/';
$returnArray = array();
$database = new Database();
$rotation = 0;

if ($uploadType === 'canvas') {
    //replace blanks with plus, or the string might be corrupt
    $encodedData = str_replace(' ', '+', $uploadData);
    $decocedData = base64_decode($encodedData);

    $dbId = $database->insert(array(
        'image_original' => 'webcam.png'
    ), DEFAULTTABLE);

    //create image
	mkdir($upload_dir . $dbId, 0777, true);
	chmod($upload_dir . $dbId, 0777);
    file_put_contents($upload_dir . $dbId . '/webcam.png', $decocedData);
    $returnArray = array('uploadSuccess' => true, 'uploadedFile' => 'webcam.png');
} else if ($uploadType === 'file') {
    //file upload
    $valid_extensions = array('gif', 'png', 'jpeg', 'jpg');

    $upload = new FileUpload('uploadData');
    $ext = $upload->getExtension();

    $dbId = $database->insert(array(
        'image_original' => 'fileUpload.' . $ext
    ), DEFAULTTABLE);

    $upload->newFileName = 'fileUpload.' . $ext;
	mkdir($upload_dir . $dbId, 0777, true);
	chmod($upload_dir . $dbId, 0777);
    $result = $upload->handleUpload($upload_dir . $dbId, $valid_extensions);

    if (!$result) {
        //echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
        $returnArray = array('uploadSuccess' => false, 'errorMsg' => $upload->getErrorMsg());
    } else {
        //echo json_encode(array('success' => true, 'file' => $upload->getFileName()));
        $returnArray = array('uploadSuccess' => true, 'uploadedFile' => $upload->getFileName());
    }

	//rotation fix for damn apple stuff
    $exif = exif_read_data($upload_dir . $dbId . '/' . $returnArray['uploadedFile']);
    if (isset($exif['Orientation'])) {
        //if($exif['Orientation'] === 1) print 'rotated clockwise by 0 deg (nothing)';
        if($exif['Orientation'] === 8) $rotation = 90;
        if($exif['Orientation'] === 3) $rotation = 180;
        if($exif['Orientation'] === 6) $rotation = 270;

        //if($exif['Orientation'] === 2) print 'vertical flip, rotated clockwise by 0 deg';
        if($exif['Orientation'] === 7) $rotation = -90;
        if($exif['Orientation'] === 4) $rotation = -180;
        if($exif['Orientation'] === 5) $rotation = -270;
    }
} else if ($uploadType === 'facebook') {
	$dbId = $database->insert(array(
        'image_original' => 'fbphoto.jpg'
    ), DEFAULTTABLE);

    //$ch = curl_init();
    //curl_setopt($ch, CURLOPT_HEADER, true);
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/' . $uploadData . '/picture?width=378&height=378&redirect=false&access_token=683129351741237|ffc03d48d9a24ad9992e109a9b49b476');
    //$data = curl_exec($ch);
    //$data = json_decode($data);

    //$data = file_get_contents('https://graph.facebook.com/' . $uploadData . '/picture?width=378&height=378');
    //$data = file_get_contents('https://graph.facebook.com/' . $uploadData . '/picture?width=378&height=378&redirect=false&access_token=683129351741237|ffc03d48d9a24ad9992e109a9b49b476');
    //10203465954441860/picture?width=378&height=378&redirect=false&access_token=683129351741237|ffc03d48d9a24ad9992e109a9b49b476
    //https://graph.facebook.com/10203465954441860/picture?width=378&height=378&redirect=false&access_token=683129351741237|ffc03d48d9a24ad9992e109a9b49b476
    //var_dump($data);
    //$data = json_decode($data);
    //$data = file_get_contents($data->data->url);

    //curl_setopt($ch, CURLOPT_URL, $data->data->url);
    /*curl_setopt($ch, CURLOPT_URL, $uploadData);
    $data = curl_exec($ch);
    curl_close($ch);*/

    mkdir($upload_dir . $dbId, 0777, true);
	chmod($upload_dir . $dbId, 0777);
    file_put_contents($upload_dir . $dbId. '/fbphoto.jpg', file_get_contents($uploadData));

	/*mkdir($upload_dir . $dbId, 0777, true);
	chmod($upload_dir . $dbId, 0777);
    $file = fopen($upload_dir . $dbId. '/fbphoto.jpg', 'w+');
    fputs($file, $data);
    fclose($file);*/
    $returnArray = array('uploadSuccess' => true, 'uploadedFile' => 'fbphoto.jpg');
}
$returnArray['id'] = $dbId;

//resize image
$resizeObj = new resize($upload_dir . $dbId . '/' . $returnArray['uploadedFile']);
$resizeObj->resizeImage(378, 378, 'crop');
$resizeObj->saveImage($upload_dir . $dbId . '/' . $returnArray['uploadedFile'], 90);
$resizeObj->resizeImage(258, 258, 'crop');
$resizeObj->saveImage($upload_dir . $dbId . '/thumb.jpg', 90);

//rotate image
if ($rotation !== 0) {
	$source = imagecreatefromjpeg($upload_dir . $dbId . '/' . $returnArray['uploadedFile']);
	$rotate = imagerotate($source, $rotation, 0);
	imagejpeg($rotate, $upload_dir . $dbId . '/' . $returnArray['uploadedFile'], 90);
	$source = imagecreatefromjpeg($upload_dir . $dbId . '/thumb.jpg');
	$rotate = imagerotate($source, $rotation, 0);
	imagejpeg($rotate, $upload_dir . $dbId . '/thumb.jpg', 90);
	imagedestroy($source);
	imagedestroy($rotate);
}

/*if (isset($returnArray['uploadSuccess']) && $returnArray['uploadSuccess'] === false) {
    echo json_encode($returnArray);
    die();
}*/

echo json_encode($returnArray);

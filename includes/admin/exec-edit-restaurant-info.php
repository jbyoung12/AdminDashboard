<?php
include(__DIR__.'/exec-increment-version-number.php');
require '../../../vendor/autoload.php';
$sendgrid = new SendGrid();
require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();


  if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){

$paymentOptionsArray = $_POST["paymentOptions"];
$count = count($paymentOptionsArray);
$paymentOptions = '';
for ($x = 0; $x <$count; $x++) {
  $paymentOptions .= $paymentOptionsArray[$x];
  if ($x<$count-1)
    $paymentOptions .= ',';
}


DB::update('category_items', array(
  "name"=>$_POST["name"], "category_id"=>"1", "type"=>$_POST["type"], "delivery_fee"=>$_POST["deliveryFee"],
  "minimum_price"=>$_POST["minimumPrice"], "email"=>$_POST["email"],
  "payment_options"=>$paymentOptions, "phoneNumber"=>$_POST["phone"]
), "id=%d", $_POST["id"]);

$days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
$times = '';
foreach ($days as $day){
  $times .= $day.' ';
  $times .= $_POST[$day.'open'] . $_POST[$day.'ampmopen'] . '-' . $_POST[$day.'close'] . $_POST[$day.'ampmclose'];
  if ($day != 'Sunday')
    $times .= ', ';
}

if ($_POST["hoursOn"] == 1){
  DB::update('item_hours', array(
    "open_hours"=>$times
  ), "restaurant_id=%d", $_POST["id"]);
}
else{
  DB::update('item_hours', array(
    "open_hours"=>''
  ), "restaurant_id=%d", $_POST["id"]);
}

//////////IMAGE UPLOADER
if ($_FILES['image']['error']==0){
  $fileName = basename($_FILES["image"]["name"]);
  DB::update('category_items', array(
    "image"=>$fileName
  ), "id=%d", $_POST["id"]);

$target_dir = "/app/public/images/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["image"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}


$message = new SendGrid\Email();
$message->addTo('jbyoung12@gmail.com')->
          setFrom('jbyoung12@gmail.com.com')->
          setSubject('Add Image to goBringIt')->
          setText('Add this image to gobringit')->
          setHtml('<strong>'.$_POST["name"].'</strong>')->
          addAttachment($target_file, $fileName);
$response = $sendgrid->send($message);

}
header("Location: /admin/index.php");
}

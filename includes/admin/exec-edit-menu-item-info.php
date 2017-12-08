<?php
include(__DIR__.'/exec-increment-version-number.php');

require_once __DIR__.'/../all.php';
require '../../../vendor/autoload.php';
$sendgrid = new SendGrid();

$cookies = new Cookies();
$user = $cookies->user_from_cookie();


  if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){
$itemId = $_POST["id"];
DB::update('menu_items', array(
  "name"=>$_POST["name"], "desc"=>$_POST["description"], "category_id"=>$_POST["menuCategoryId"], "price"=>$_POST["price"], "num_sides"=>$_POST["numSides"], "is_featured"=>$_POST["isFeatured"]), "id=%d", $itemId);

  if (isset($_POST["sidesIds"])){
  $sidesIdsArray = $_POST["sidesIds"]; //sides that need to be linked
  $allSides = DB::queryFirstColumn("SELECT sides_id from menu_sides_item_link where item_id=$itemId"); //all sides linked to item
  $itemsToDelete = array_diff($allSides, $sidesIdsArray); //items that should not be linked
  $itemsToAdd = array_diff($sidesIdsArray, $allSides); //items that should be linked

  foreach ($itemsToDelete as $sideId){
    $linkId = DB::queryFirstField("SELECT id FROM menu_sides_item_link WHERE sides_id=%d and item_id=%d", $sideId, $itemId);
    DB::delete('menu_sides_item_link', "id=%d", $linkId);
  }


  foreach ($itemsToAdd as $sideId){
    DB::insert("menu_sides_item_link", array("sides_id"=>$sideId, "item_id"=>$itemId, "grouping"=>''));
  }
}
  //////////IMAGE UPLOADER
  if ($_FILES['image']['error']==0){
    $fileName = basename($_FILES["image"]["name"]);
    DB::update('menu_items', array(
      "featured_image"=>$fileName
    ), "id=%d", $itemId);

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




}
header("Location: /admin/index.php");

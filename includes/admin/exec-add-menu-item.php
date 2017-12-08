<?php
include(__DIR__.'/exec-increment-version-number.php');

require_once __DIR__.'/../all.php';
require '../../../vendor/autoload.php';
$sendgrid = new SendGrid();

$cookies = new Cookies();
$user = $cookies->user_from_cookie();
$vars = array("name","description","price", "serviceId", "menuCategoryId", "numSides", "isFeatured");

$featuredImage = basename($_FILES["image"]["name"]);
if (empty($featuredImage)){ #if no featured image
  $featuredImage = NULL;
}

if (set_vars($_POST, $vars)){
    error_log(var_export($_POST, true));
    error_log(var_export($user, true));
    if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){

        $result = DB::insert("menu_items", array("name"=>$_POST["name"], "desc"=>$_POST["description"],
        "price"=>$_POST["price"], "service_id"=>$_POST["serviceId"], "category_id"=>$_POST["menuCategoryId"],
        "separate_groupings"=>"0", "num_sides"=>$_POST["numSides"],
        "is_featured"=>$_POST["isFeatured"], "featured_image"=>$featuredImage));

        $menuItemId = DB::insertId();

        if (isset($_POST["sidesIds"])){
        $sidesIdsArray = $_POST["sidesIds"];
        foreach ($sidesIdsArray as $sidesId){
            DB::insert("menu_sides_item_link", array("sides_id"=>$sidesId, "item_id"=>$menuItemId, "grouping"=>''));
        }
}
        //////////IMAGE UPLOADER
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

        if (!empty($featuredImage)){ #if no featured image

        $message = new SendGrid\Email();
        $message->addTo('jbyoung12@gmail.com')->
                  setFrom('jbyoung12@gmail.com.com')->
                  setSubject('Add Image to goBringIt')->
                  setText('Add this image to gobringit')->
                  setHtml('<strong>'.$_POST["name"].'</strong>')->
                  addAttachment($target_file, $featuredImage);
        $response = $sendgrid->send($message);

}

        header("Location: /admin/index.php");
    }
    else{
        echo "-1";
    }
}
else{
    echo "-1";
}

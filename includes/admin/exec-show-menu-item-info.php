<?php
require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();
$id = $_GET['id'];
$restaurantId = $_GET['rId'];

#$num = $_GET['num'];
$menuCategories = DB::query("SELECT ID, NAME FROM menu_categories WHERE service_id='%d' ORDER BY NAME", $restaurantId);

$itemRow = DB::queryFirstRow("SELECT * FROM menu_items WHERE id='%d'", $id);

$htmlCode = '<div class="row"><div class="col-sm-3"><label>Menu Category: </label></div><div class="col-sm-9 col-md-6">
<select class="form-control" id="menuCategories" name="menuCategoryId" required>';

foreach ($menuCategories as $row) {
  $htmlCode .= '<option value="'.$row['ID'].'" '.($row['ID'] == $itemRow['category_id'] ? 'selected' : '').'>'.$row['NAME'].'</option>';
}


$htmlCode .= '</select> (menu category for which this menu item is being added)</div></div><br>';

$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Name of Menu Item: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="name" value="'.$itemRow['name'].'" required></div></div><br>';
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Description: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="description" value="'.$itemRow['desc'].'" required> (description of the menu item and its ingredients)</div></div><br>';
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Price of Menu Item: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="number" name="price" value="'.$itemRow['price'].'" min="0" step=".01" required></div></div><br>';

//add sides
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Edit Sides for Menu Item: </label></div><div class="col-sm-9 col-md-6"><select style="display:none" class="menuItemEditSides" name="sidesIds[]" multiple="multiple">';

$sidesForItem = DB::queryFirstColumn("SELECT sides_id FROM menu_sides_item_link WHERE item_id='%d'", $id);
$allMenuSides = DB::query("SELECT ID, NAME FROM menu_sides WHERE service_id='%d' ORDER BY NAME", $restaurantId);
//contains
//if ID==sides_id -> selected
foreach ($allMenuSides as $row) {
  $htmlCode .= '<option value="'.$row['ID'].'"'.(in_array($row['ID'], $sidesForItem) ? 'selected' : '').'>'.$row['NAME'].'</option>';
}

$htmlCode .= '</select></div></div><br>';
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Number of Sides: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="number" name="numSides" value="'.$itemRow['num_sides'].'" min="0" max="5" required></div></div><br>';
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Featured? </label></div><div class="col-sm-9 col-md-6"><select class="form-control" name="isFeatured" required>
  <option value="0" '.($itemRow['is_featured'] != 1 ? 'selected' : '').'>No</option>
  <option value="1" '.($itemRow['is_featured'] == 1 ? 'selected' : '').'>Yes</option>
</select> (Do you want to feature this item as one of the top items in the restaurant?)</div></div><br>';
$htmlCode .= '<input type="hidden" name="MAX_FILE_SIZE" value="512000" />
<div class="row"><div class="col-sm-3"><label>Featured Image (If featured, this is REQUIRED. Upload if you want to change it): </label></div><div class="col-sm-9 col-md-6"><input name="image" type="file" class="form-control notRequiredFormatter"/></div></div><br>';

echo $htmlCode;

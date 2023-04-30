<?php
session_start();
require_once("connection.php");
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$sql = "SELECT * FROM products WHERE code = '".$_GET["code"]."'";
			$data = mysqli_query($conn,$sql);
			$productByCode = mysqli_fetch_array($data);
			$itemArray = array($productByCode["code"]=>array('name'=>$productByCode["product_name"], 'code'=>$productByCode["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode["price"], 'image'=>$productByCode["product_image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
		header('Location:shop.php?shop_id='.$_GET['shop_id'].'&category=all');
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
		header('Location:shop.php?shop_id='.$_GET['shop_id'].'&category=all');
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
		header('Location:shop.php?shop_id='.$_GET['shop_id'].'&category=all');
	break;	
}
}
?>
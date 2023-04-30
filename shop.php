<?php include('connection.php'); session_start(); ?>

<?php

if(isset($_POST['addshop'])){

  $shop_name = $_POST['shop_name'];

  $target = "images/".basename($_FILES['shop_icon']['name']);
  $shop_icon = $_FILES['shop_icon']['name'];

  $shipping_fee = $_POST['shipping_fee'];
  $seller_id = $_POST['seller_id'];

  //check if shop already exists
  $selectuser = "SELECT * FROM shop WHERE shop_name = '".$shop_name."'";
  $datauser = mysqli_query($conn, $selectuser);

  if($datauser){

    $row = mysqli_num_rows($datauser);

    if($row > 0){
      echo "<script>alert('Shop Name already exists!')</script>";
    }
    else{
      //create shop
      $sql = "INSERT INTO shop (seller_id, shop_name, shop_icon, shipping_fee) VALUES ('$seller_id', '$shop_name', '$shop_icon', '$shipping_fee')";
      $data = mysqli_query($conn,$sql);

        //check if data save to database and file move to folder
        if ($data && move_uploaded_file($_FILES['shop_icon']['tmp_name'], $target)) {
          echo "<script>alert('Created Successfully!')</script>";
        }
        else{
          echo "<script>alert('Failed!')</script>";
        }
    }

  }
  else{
    echo "<script>alert('Error!')</script>";
  }

}


if(isset($_POST['submitorder'])){

  $ref_num = time() . rand(10*45, 100*98);
  $member_id = $_POST['member_id'];
  $shop_id = $_POST['shop_id'];
  $payment_option = $_POST['payment_option'];
  $code  = $_POST['code'];
  $quantity = $_POST['quantity'];
  $price = $_POST['price'];
  $shipping_fee = $_POST['shipping_fee'];
  $total_price = $_POST['total_price'];

  for($l=0; $l < count($code); $l++){
   $sql = "INSERT INTO orders (ref_num, member_id, shop_id, payment_option, code, quantity, price, shipping_fee, total_price) VALUES ('$ref_num', '$member_id', '$shop_id', '$payment_option', '$code[$l]', '$quantity[$l]', '$price[$l]', '$shipping_fee', '$total_price')";
   $data = mysqli_query($conn,$sql);

   $sql1 = "UPDATE products SET quantity = quantity - $quantity[$l] WHERE code = '".$code[$l]."'";
   $data1 = mysqli_query($conn,$sql1);

   //check if data save to database and file move to folder
    if ($data && $data1) {
      unset($_SESSION["cart_item"]);
      echo "<script>
            alert('Order Successfully!');
            window.location.href='orderdetails.php?ref_num=".$ref_num."';
            </script>";
    }
    else{
      echo "<script>alert('Failed!')</script>";
    }

  }


    

}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>

</head>
<body>

<?php

if (!empty($_SESSION['username']))
{

$username = $_SESSION['username'];
$sql = "SELECT * FROM member WHERE username = '".$username."'";
$result = mysqli_query($conn, $sql);
$show = mysqli_fetch_array($result);

?>

  <div class="container">
    <div class="navbar">
      <div class="logo">
        <a href="index.php"><h1>Ecommerce Website</h1></a>
      </div>
      <nav>
        <ul id="MenuItems">
          <li>Welcome, <?= $show['full_name']; ?></li>

          <?php 
          if($show['user_type'] == 1) {
            //member view
            echo '<li><a href="index.php">Shops</a></li>';
            echo '<li><a href="orderdetails.php">Order History</a></li>';
          }
          else{
            //seller view
            echo '<li><a href="index.php" style="color:red">Shop</a></li>';
            echo '<li><a href="category.php">Category</a></li>';
            echo '<li><a href="product.php">Product</a></li>';
            echo '<li><a href="orderdetails.php">Order</a></li>';
          }
          ?>

          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </div>

  <?php 
  if($show['user_type'] == 1) {

    if(isset($_GET['shop_id']) && isset($_GET['category'])){

    $shop_id = $_GET['shop_id'];
    $category_id = $_GET['category'];
  ?>

  <!-- member view -->
  <div class="small-container">

    <div class="row row-2">
      <h2>All Products</h2>
      <select onchange="location = this.value;">
        <option>Select Category</option>
        <?php 
        $sql = "SELECT * FROM category WHERE shop_id = $shop_id";
        $result = mysqli_query($conn, $sql);
        while($data = mysqli_fetch_array($result)){
        ?>
        <option value="shop.php?shop_id=<?= $shop_id; ?>&category=<?= $data['category_id']; ?>"><?= $data['category']; ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="row">

      <?php 
    
      if($category_id == 'all'){
        $sql = "SELECT * FROM products WHERE shop_id = $shop_id AND active = 1 AND quantity != 0";
      }
      else{
        $sql = "SELECT * FROM products WHERE shop_id = $shop_id AND category_id = $category_id AND active = 1 AND quantity != 0";
      }
      
      $result = mysqli_query($conn, $sql);

      while($showprod = mysqli_fetch_array($result)){
      ?>

      
        <div class="col-4">
          <form method="post" action="addtocart.php?action=add&code=<?= $showprod['code']; ?>&shop_id=<?= $shop_id; ?>">
            <img src="images/<?= $showprod['product_image']; ?>" alt="" />
            <h4><?= $showprod['product_name']; ?></h4><br>
            <p><?= $showprod['description']; ?></p>

            <?php 

            $total_rating = 0;

            $ratesql = "SELECT SUM(rating) as sum, COUNT(rating) as total FROM rating WHERE product_id = ".$showprod['product_id']."";
            $ressql = mysqli_query($conn,$ratesql);

            $row = mysqli_fetch_array($ressql);

            if($row['sum']){
              $total_rating = $row['sum']/$row['total'];

              if($total_rating < 2){
                echo '
                <div class="rating">
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                </div>
                ';
              }
              elseif($total_rating < 3){
                echo '
                <div class="rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                </div>
                ';
              }
              elseif($total_rating < 4){
                echo '
                <div class="rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                </div>
                ';
              }
              elseif($total_rating == 5){
                echo '
                <div class="rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
                ';
              }
              else{
                echo '
                <div class="rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i>
                </div>
                ';
              }

              echo '<a href="reviews.php?product_id='.$showprod['product_id'].'">View Reviews</a>';
            }
            else{
              echo 'No rating yet';
            }

            ?>
            
            
            <p>₱ <?= $showprod['price']; ?> (<?= $showprod['quantity']; ?> On stock)</p>
            <input type="text" value="1" name="quantity">
            <input type="submit" value="Add to Cart">
          </form>
        </div>

      <?php } ?>

      <form method="POST">
      <div class="col-4">

        <div id="shopping-cart">
          <h2>Cart</h2>

          <a id="btnEmpty" href="addtocart.php?action=empty&shop_id=<?= $shop_id; ?>" style="float:right">Clear Cart</a><br><br>
          <?php
          if(isset($_SESSION["cart_item"])){
              $total_quantity = 0;
              $total_price = 0;
              $delsql = "SELECT shipping_fee FROM shop WHERE shop_id = ".$shop_id."";
              $result = mysqli_query($conn,$delsql);
              $showfee = mysqli_fetch_array($result);

              $shipping_fee = $showfee['shipping_fee'];
          ?>  
          <table class="tbl-cart" cellpadding="10" cellspacing="1">
          <tbody>
          <tr>
          <th style="text-align:left;">Image</th>
          <th style="text-align:left;">Product Name</th>
          <th style="text-align:right;" width="5%">Quantity</th>
          <th style="text-align:right;" width="10%">Unit Price</th>
          <th style="text-align:right;" width="10%">Price</th>
          <th style="text-align:center;" width="5%">Action</th>
          </tr> 
          <?php   
              foreach ($_SESSION["cart_item"] as $item){
                  $item_price = $item["quantity"]*$item["price"];
              ?>
                  <tr>
                  <td><img src="images/<?php echo $item["image"]; ?>" class="cart-item-image" /></td>
                  <td><?php echo $item["name"]; ?></td>
                  <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                  <td  style="text-align:right;"><?php echo "₱ ".$item["price"]; ?></td>
                  <td  style="text-align:right;"><?php echo "₱ ". number_format($item_price,2); ?></td>
                  <td style="text-align:center;"><a href="addtocart.php?action=remove&code=<?php echo $item["code"]; ?>&shop_id=<?= $shop_id; ?>" class="btnRemoveAction">Remove</a></td>
                  </tr>
                  <input type="hidden" name="code[]" value="<?php echo $item["code"]; ?>">
                  <input type="hidden" name="quantity[]" value="<?php echo $item["quantity"]; ?>">
                  <input type="hidden" name="price[]" value="<?php echo $item_price; ?>">
                  <?php
                  $total_quantity += $item["quantity"];
                  $total_price += ($item["price"]*$item["quantity"])+$shipping_fee;
              }
              ?>
                <input type="hidden" name="shipping_fee" value="<?php echo $shipping_fee; ?>">
                <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

          <tr>
            <td colspan="2" align="right">Delivery Fee:</td>
            <td align="right"></td>
            <td align="right">₱ <?= $shipping_fee; ?></td>
          </tr>
          <tr>
          <td colspan="2" align="right">Total:</td>
          <td align="right"><?php echo $total_quantity; ?></td>
          <td align="right" colspan="2"><strong><?php echo "₱ ".number_format($total_price, 2); ?></strong></td>
          <td></td>
          </tr>
          </tbody>
          </table>    
            <?php
          } else {
          ?>
          <center><div class="no-records">Your Cart is Empty</div></center>
          <?php 
          }
          ?>
          </div>
          <br><br>

      </div>

      <br>
      <?php

      if(isset($_SESSION["cart_item"])){

      ?>
      <div class="col-4">
        <h2>Customer Details</h2><br>
        <p><b>Full Name: </b><?= $show['full_name']; ?></p><br>
        <p><b>Address: </b><?= $show['address']; ?></p><br>
        <p><b>Contact Number: </b><?= $show['contact_number']; ?></p><br>
        <br>
        <input type="hidden" name="member_id" value="<?= $show['member_id']; ?>">
        <input type="hidden" name="shop_id" value="<?= $shop_id; ?>">
        <h2>Payment Option</h2>
        <div class="choose">
          <label>BDO
            <input type="radio" name="payment_option" value="BDO" style="width: 25%;" checked>
          </label>
          <label>BPI
            <input type="radio" name="payment_option" value="BPI" style="width: 25%;">
          </label>
          <label>GCASH
            <input type="radio" name="payment_option" value="GCASH" style="width: 25%;">
          </label>
        </div>


          <input type="submit" value="Place Order" name="submitorder">
      </div>

    <?php } ?>

      </form>


      

    </div>


  </div>

  <?php
    }

  }
  else{
  ?>

  <!-- seller view -->

  <div class="container">

    <div class="row">

      <div class="column">
        <h3>Your Shop/s</h3><br>
        <table>
          <tr>
            <th style="background-color:grey">Icon</th>
            <th style="background-color:grey">Shop Name</th>
            <th style="background-color:grey">Shipping Fee</th>
            <th style="background-color:grey">Action</th>
          </tr>

          <?php 
          $sql = "SELECT * FROM shop WHERE seller_id = '".$show['member_id']."'";
          $result = mysqli_query($conn, $sql);
          while($data = mysqli_fetch_array($result)){
          ?>
          <tr>
            <td><img src="images/<?= $data['shop_icon']; ?>" width="70"></td>
            <td><?= $data['shop_name']; ?></td>
            <td><?= $data['shipping_fee']; ?></td>
            <td><a href="">Edit</a></td>
          </tr>
          <?php } ?>
        </table>
      </div>

      <?php 

      $row = mysqli_num_rows($result);

      if($row > 0){

      }
      else {
      ?>
      <div class="column">
         <h3>Create Shop</h3>
         <br>
         <form method="POST" enctype="multipart/form-data">
            Shop Icon <input type="file" name="shop_icon" accept="image/png, image/gif, image/jpeg" required>
            Shop Name <input type="text" name="shop_name" required>
            Shipping Fee <input type="text" name="shipping_fee" required>
            <input type="hidden" value="<?= $show['member_id']; ?>" name="seller_id">
            <input type="submit" name="addshop" value="Create" />
         </form>
      </div>
      <?php
      }
      ?>
      
    </div>
    

  </div>

  <?php
  }
  ?>


<?php
}
else
{

  if(isset($_GET['shop_id']) && isset($_GET['category'])){

    $shop_id = $_GET['shop_id'];
    $category_id = $_GET['category'];
?>

  <div class="container">
    <div class="navbar">
      <div class="logo">
        <a href="index.php"><h1>Ecommerce Website</h1></a>
      </div>
      <nav>
        <ul id="MenuItems">
          <li><a href="login.php">Login / Register</a></li>
        </ul>
      </nav>
    </div>
  </div>

  <div class="small-container">

    <div class="row row-2">
      <h2>All Products</h2>
      <select onchange="location = this.value;">
        <option>Select Category</option>
        <?php 
        $sql = "SELECT * FROM category WHERE shop_id = $shop_id";
        $result = mysqli_query($conn, $sql);
        while($data = mysqli_fetch_array($result)){
        ?>
        <option value="shop.php?shop_id=<?= $shop_id; ?>&category=<?= $data['category_id']; ?>"><?= $data['category']; ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="row">

      <?php 
    
      if($category_id == 'all'){
        $sql = "SELECT * FROM products WHERE shop_id = $shop_id AND active = 1 AND quantity != 0";
      }
      else{
        $sql = "SELECT * FROM products WHERE shop_id = $shop_id AND category_id = $category_id AND active = 1 AND quantity != 0";
      }
      
      $result = mysqli_query($conn, $sql);

      while($showprod = mysqli_fetch_array($result)){
      ?>

      
      <div class="col-4">
          <img src="images/<?= $showprod['product_image']; ?>" alt="" />
          <h4><?= $showprod['product_name']; ?></h4><br>
          <p><?= $showprod['description']; ?></p>
          <div class="rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="far fa-star"></i>
          </div>
          <p>₱ <?= $showprod['price']; ?> (<?= $showprod['quantity']; ?> On stock)</p>
          <button><a href="login.php">Add to Cart</a></button>
      </div>

      <?php } ?>

    </div>

  </div>

<?php

}

}

?>



</body>
</html>
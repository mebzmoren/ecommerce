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
            echo '<li><a href="index.php" style="color:red">Shops</a></li>';
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
  ?>

  <!-- member view -->
  <div class="small-container">

    <div class="row row-2">
      <h2>All Shops</h2>
    </div>

    <div class="row">

      <?php 
      $sql = "SELECT * FROM shop WHERE active = 1";
      $result = mysqli_query($conn, $sql);
      while($showshop = mysqli_fetch_array($result)){
      ?>

      
      <div class="col-4">
        <a href="shop.php?shop_id=<?= $showshop['shop_id']; ?>&category=all">
          <img src="images/<?= $showshop['shop_icon']; ?>" alt="" />
          <h4><?= $showshop['shop_name']; ?></h4>
          <!-- <div class="rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="far fa-star"></i>
          </div> -->
          <p>Delivery Fee : ₱ <?= $showshop['shipping_fee']; ?> only</p>
        </a>
      </div>

      <?php } ?>

    </div>

  </div>

  <?php
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
      <h2>All Shops</h2>
    </div>

    <div class="row">

      <?php 
      $sql = "SELECT * FROM shop WHERE active = 1";
      $result = mysqli_query($conn, $sql);
      while($showshop = mysqli_fetch_array($result)){
      ?>

      
      <div class="col-4">
        <a href="shop.php?shop_id=<?= $showshop['shop_id']; ?>&category=all">
          <img src="images/<?= $showshop['shop_icon']; ?>" alt="" />
          <h4><?= $showshop['shop_name']; ?></h4>
          <!-- <div class="rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="far fa-star"></i>
          </div> -->
          <p>Delivery Fee : ₱ <?= $showshop['shipping_fee']; ?> only</p>
        </a>
      </div>

      <?php } ?>

    </div>

  </div>

<?php
}

?>



</body>
</html>
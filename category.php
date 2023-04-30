<?php include('connection.php'); session_start(); ?>

<?php

if(isset($_POST['addcategory'])){

  $shop_id = $_POST['shop_id'];
  $category = $_POST['category'];

  //check if category already exists
  $selectuser = "SELECT * FROM category WHERE category = '".$category."' AND shop_id = '".$shop_id."'";
  $datauser = mysqli_query($conn, $selectuser);

  if($datauser){

    $row = mysqli_num_rows($datauser);

    if($row > 0){
      echo "<script>alert('Category already exists!')</script>";
    }
    else{
      //create category
      $sql = "INSERT INTO category (category, shop_id) VALUES ('$category', '$shop_id')";
      $data = mysqli_query($conn,$sql);

        //check if data save to database
        if ($data) {
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
            echo '<li><a href="index.php">Shops</a></li>';
            echo '<li><a href="orderdetails.php">Order History</a></li>';
          }
          else{
            //seller view
            echo '<li><a href="index.php">Shop</a></li>';
            echo '<li><a href="category.php" style="color:red">Category</a></li>';
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

  <?php
  }
  else{
  ?>

  <!-- seller view -->

  <div class="container">

    <div class="row">
      <div class="column">
        <h3>Categories</h3><br>
        <table>
          <tr>
            <th style="background-color:grey">Category</th>
          </tr>

          <?php 
          $sql = "SELECT * FROM category AS A LEFT JOIN shop AS B ON A.shop_id = B.shop_id WHERE B.seller_id = '".$show['member_id']."'";
          $result = mysqli_query($conn, $sql);
          while($data = mysqli_fetch_array($result)){
          ?>
          <tr>
            <td><?= $data['category']; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <div class="column">
         <h3>Create Category</h3>
         <br>
         <form method="POST">
            <?php 
            $sql = "SELECT * FROM shop WHERE seller_id = '".$show['member_id']."'";
            $result = mysqli_query($conn, $sql);
            $data1 = mysqli_fetch_array($result);
            ?>
            <input type="hidden" name="shop_id" value="<?= $data1['shop_id']; ?>">
            Cateogry <input type="text" name="category" required>
            <input type="submit" name="addcategory" value="Create" />
         </form>
      </div>
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

      <div class="col-4">
        <img src="images/sunglassesshop.png" alt="" />
        <h4>Sunny Day</h4>
        <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="far fa-star"></i>
        </div>
        <p>₹500.00</p>
      </div>

    </div>

  </div>

<?php
}

?>



</body>
</html>
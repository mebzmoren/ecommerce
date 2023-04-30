<?php include('connection.php'); session_start(); ?>

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
            echo '<li><a href="category.php">Category</a></li>';
            echo '<li><a href="product.php" style="color:red">Product</a></li>';
            echo '<li><a href="orderdetails.php">Order</a></li>';
          }
          ?>

          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </div>

  <?php 
  if($show['user_type'] == 1 || $show['user_type'] == 2) {

    if(isset($_GET['product_id'])){

    $product_id = $_GET['product_id'];
  ?>

  <!-- member view -->
  <div class="small-container">

    <div class="row row-2">
      <h2>Reviews</h2>
    </div>

    <div class="row">

      <?php 
    
      $sql = "SELECT * FROM products WHERE product_id = $product_id AND active = 1 AND quantity != 0";
      
      $result = mysqli_query($conn, $sql);

      while($showprod = mysqli_fetch_array($result)){
      ?>

      
        <div class="col-4">
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

            }
            else{
              echo 'No rating yet';
            }

            ?>
            
            
            <p>₱ <?= $showprod['price']; ?> (<?= $showprod['quantity']; ?> On stock)</p>
        </div>

      <?php } ?>


    </div>


  </div>

  <div class="small-container">
    <table>
      <tr>
        <th style="background-color:grey">Customer Name</th>
        <th style="background-color:grey">Rating</th>
        <th style="background-color:grey">Comment</th>
        <th style="background-color:grey">Date</th>
      </tr>

      <?php 
      $sql = "SELECT A.*,B.full_name FROM rating AS A LEFT JOIN member AS B ON A.member_id = B.member_id WHERE A.product_id = '".$product_id."'";
      $result = mysqli_query($conn, $sql);
      while($data = mysqli_fetch_array($result)){
      ?>
      <tr>
        <td><?= $data['full_name']; ?></td>
        <td><?= $data['rating']; ?> star</td>
        <td><?= $data['comment']; ?></td>
        <td><?= $data['rating_date']; ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>

  <?php
    }

  }
  else{
  ?>

  <!-- seller view -->

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


<?php


}

?>



</body>
</html>
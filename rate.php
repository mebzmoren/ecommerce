<?php include('connection.php'); session_start(); ?>

<?php 

if(isset($_POST['submitrate'])){

  $member_id = $_POST['member_id'];
  $product_id = $_POST['product_id'];
  $rating = $_POST['rating'];
  $comment = $_POST['comment'];

  $sql = "INSERT INTO rating (member_id, product_id, rating, comment) VALUES ('$member_id', '$product_id', '$rating', '$comment')";
  $data = mysqli_query($conn,$sql);

    //check if data save to database and file move to folder
    if ($data) {
      echo "<script>alert('Rating Submit Successfully!')</script>";
    }
    else{
      echo "<script>alert('Failed!')</script>";
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
$sql = "SELECT A.*,B.* FROM member AS A LEFT JOIN shop AS B ON A.member_id = B.seller_id WHERE A.username = '".$username."'";
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
            echo '<li><a href="orderdetails.php" style="color:red">Order History</a></li>';
          }
          else{
            //seller view
            echo '<li><a href="index.php">Shop</a></li>';
            echo '<li><a href="category.php">Category</a></li>';
            echo '<li><a href="product.php">Product</a></li>';
            echo '<li><a href="orderdetails.php" style="color:red">Order</a></li>';
          }
          ?>

          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </div>

  <?php 
  if($show['user_type'] == 1) {

    if(isset($_GET['product_id'])){

      $product_id = $_GET['product_id'];

  ?>

  <div class="small-container">
    <form method="POST">
      <input type="hidden" value="<?= $show['member_id']; ?>" name="member_id">
      <input type="hidden" value="<?= $product_id; ?>" name="product_id">
      Select Rating <select name="rating">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
      <input type="text" name="comment" placeholder="Please input your comments here...">
      <input type="submit" value="Submit Rate" name="submitrate">

    </form>
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

  <div class="small-container">

    <div class="row row-2">
      <h2>Please login</h2>
    </div>


  </div>

<?php
}

?>



</body>
</html>
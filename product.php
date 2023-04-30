<?php include('connection.php'); session_start(); ?>

<?php

if(isset($_POST['addproduct'])){

  $shop_id = $_POST['shop_id'];
  $category_id = $_POST['category_id'];

  $target = "images/".basename($_FILES['product_image']['name']);
  $product_image = $_FILES['product_image']['name'];

  $product_name = $_POST['product_name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $qty = $_POST['qty'];

  //check if product already exists
  $selectuser = "SELECT * FROM products WHERE product_name = '".$product_name."' AND category_id = '".$category_id."'";
  $datauser = mysqli_query($conn, $selectuser);

  if($datauser){

    $row = mysqli_num_rows($datauser);

    if($row > 0){
      echo "<script>alert('Product Name already exists!')</script>";
    }
    else{
      //create category
      $sql = "INSERT INTO products (category_id, shop_id, product_image, product_name, description, price, quantity) VALUES ('$category_id', '$shop_id','$product_image','$product_name', '$description', '$price', '$qty')";
      $data = mysqli_query($conn,$sql);
      $id = mysqli_insert_id($conn);

      $update = "UPDATE products SET code = 'p".$id."' WHERE product_id = ".$id."";
      $res = mysqli_query($conn,$update);

        //check if data save to database and file move to folder
        if ($res && $data && move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
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
        <h3>Products</h3><br>
        <table>
          <tr>
            <th style="background-color:grey">Category</th>
            <th style="background-color:grey">Image</th>
            <th style="background-color:grey">Product name</th>
            <th style="background-color:grey">Description</th>
            <th style="background-color:grey">Price</th>
            <th style="background-color:grey">Qty</th>
            <th style="background-color:grey">Action</th>
          </tr>

          <?php 
          $sql = "SELECT * FROM products AS A LEFT JOIN shop AS B ON A.shop_id = B.shop_id LEFT JOIN category AS C ON A.category_id = C.category_id WHERE B.seller_id = '".$show['member_id']."'";
          $result = mysqli_query($conn, $sql);
          while($data = mysqli_fetch_array($result)){
          ?>
          <tr>
            <td><?= $data['category']; ?></td>
            <td><img src="images/<?= $data['product_image']; ?>" width="70"></td>
            <td><?= $data['product_name']; ?></td>
            <td><?= $data['description']; ?></td>
            <td><?= $data['price']; ?></td>
            <td><?= $data['quantity']; ?></td>
            <td><a href="reviews.php?product_id=<?= $data['product_id']; ?>">View Review</a></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <div class="column">
         <h3>Create Product</h3>
         <br>
         <form method="POST" enctype="multipart/form-data">
          <?php 
            $sql = "SELECT * FROM shop WHERE seller_id = '".$show['member_id']."'";
            $result = mysqli_query($conn, $sql);
            $data1 = mysqli_fetch_array($result);
            ?>
            <input type="hidden" name="shop_id" value="<?= $data1['shop_id']; ?>">
            Select Category
            <br>
            <select name="category_id">
              <?php 
              $sql = "SELECT * FROM category AS A LEFT JOIN shop AS B ON A.shop_id = B.shop_id WHERE B.seller_id = '".$show['member_id']."'";
              $result = mysqli_query($conn, $sql);
              while($data = mysqli_fetch_array($result)){
              ?>
              <option value="<?= $data['category_id']; ?>"><?= $data['category']; ?></option>
              <?php } ?>
            </select>
            <br>
            Product Image <input type="file" name="product_image" accept="image/png, image/gif, image/jpeg" required>
            Product Name<input type="text" name="product_name" required>
            Description<input type="text" name="description" required>
            Price<input type="text" name="price" required>
            Quantity<input type="text" name="qty" required>
            <input type="submit" name="addproduct" value="Create" />
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
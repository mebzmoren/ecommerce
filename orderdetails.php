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

    if(isset($_GET['ref_num'])){

      $ref_num = $_GET['ref_num'];
      $total_price = 0;
  ?>

  <!-- member view -->
  <div class="small-container">

    <div class="row row-2">
      <h2>Order Details</h2>
    </div>

    <div class="row">
  
      <div class="col-6">
        <table>
          <tr>
            <th style="background-color:grey">Reference Number</th>
            <th style="background-color:grey">Shop</th>
            <th style="background-color:grey">Product Name</th>
            <th style="background-color:grey">Quantity</th>
            <th style="background-color:grey">Price</th>
            <th style="background-color:grey">Date</th>
          </tr>

          <?php 
          $sql = "SELECT A.*,B.product_name,C.shop_name FROM orders AS A LEFT JOIN products AS B ON A.code = B.code LEFT JOIN shop AS C ON A.shop_id = C.shop_id WHERE ref_num = $ref_num";
          $result = mysqli_query($conn, $sql);
          while($data = mysqli_fetch_array($result)){
          ?>
          <tr>
            <td><?= $data['ref_num']; ?></td>
            <td><?= $data['shop_name']; ?></td>
            <td><?= $data['product_name']; ?></td>
            <td><?= $data['quantity']; ?></td>
            <td>₱ <?= number_format($data['price'],2); ?></td>
            <td><?= $data['order_date']; ?></td>
          </tr>

          <?php $total_price = $data['total_price'];
          $shipping_fee = $data['shipping_fee'];

          } ?>
          <tr>
          <td colspan="2" align="right">Delivery Fee:</td>
          <td align="right" colspan="2"><strong><?php echo "₱ ".number_format($shipping_fee, 2); ?></strong></td>
          <td></td>
          </tr>
          <tr>
          <td colspan="2" align="right">Total Price:</td>
          <td align="right" colspan="2"><strong><?php echo "₱ ".number_format($total_price, 2); ?></strong></td>
          <td></td>
          </tr>
        </table>
      </div>

    </div>

  </div>


  <?php } ?>

  <div class="small-container">

    <div class="row row-2">
      <h2>Order History</h2>
    </div>

    <div class="row">
  
      <div class="col-6">
        <table>
          <tr>
            <th style="background-color:grey">Reference Number</th>
            <th style="background-color:grey">Shop</th>
            <th style="background-color:grey">Product Name</th>
            <th style="background-color:grey">Quantity</th>
            <th style="background-color:grey">Total Price</th>
             <th style="background-color:grey">Shipping Fee</th>
            <th style="background-color:grey">Date</th>
            <th style="background-color:grey">Rate</th>
          </tr>

          <?php 
          $sql = "SELECT A.*,B.product_id,B.product_name,C.shop_name FROM orders AS A LEFT JOIN products AS B ON A.code = B.code LEFT JOIN shop AS C ON A.shop_id = C.shop_id WHERE member_id = ".$show['member_id']." ORDER BY order_id DESC";
          $result = mysqli_query($conn, $sql);
          while($data = mysqli_fetch_array($result)){
          ?>
          <tr>
            <td><?= $data['ref_num']; ?></td>
            <td><?= $data['shop_name']; ?></td>
            <td><?= $data['product_name']; ?></td>
            <td><?= $data['quantity']; ?></td>
            <td>₱ <?= number_format($data['price'],2); ?></td>
            <td><?= $data['shipping_fee']; ?></td>
            <td><?= $data['order_date']; ?></td>
            <td><a href="rate.php?product_id=<?= $data['product_id']; ?>">Rate</a></td>
          </tr>
          <?php 
          } 
          ?>
        </table>
      </div>

    </div>

  </div>

  <?php
  }
  else{
  ?>

  <!-- seller view -->

  <div class="container">

    <div class="row row-2">
      <h2>Orders</h2>
    </div>

    <div class="row">
  
      <div class="col-6">
        <table>
          <tr>
            <th style="background-color:grey">Reference Number</th>
            <th style="background-color:grey">Customer Name</th>
            <th style="background-color:grey">Address</th>
            <th style="background-color:grey">Contact Number</th>
            <th style="background-color:grey">Payment</th>
            <th style="background-color:grey">Quantity</th>
            <th style="background-color:grey">Price</th>
            <th style="background-color:grey">Date</th>
          </tr>

          <?php 
          $sql = "SELECT A.*,B.full_name,B.address,B.contact_number FROM orders AS A LEFT JOIN member AS B ON A.member_id = B.member_id WHERE A.shop_id = ".$show['shop_id']." ORDER BY order_id DESC";
          $result = mysqli_query($conn, $sql);
          while($data = mysqli_fetch_array($result)){
          ?>
          <tr>
            <td><?= $data['ref_num']; ?></td>
            <td><?= $data['full_name']; ?></td>
            <td><?= $data['address']; ?></td>
            <td><?= $data['contact_number']; ?></td>
            <td><?= $data['payment_option']; ?></td>
            <td><?= $data['quantity']; ?></td>
            <td>₱ <?= number_format($data['price'],2); ?></td>
            <td><?= $data['order_date']; ?></td>
          </tr>
          <?php 
          } 
          ?>
        </table>
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
      <h2>Please login to view your order history</h2>
    </div>


  </div>

<?php
}

?>



</body>
</html>
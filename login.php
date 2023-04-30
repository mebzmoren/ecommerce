<?php 
session_start();
include('connection.php');

if (!empty($_SESSION['username'])) {
  header('Location:index.php');
}
else
{


  if(isset($_POST['register'])){

    $usertype = $_POST['usertype'];
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $contact_num = $_POST['contact_num'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $repassword = md5($_POST['repassword']);


      //check if user already exists
      $selectuser = "SELECT * FROM member WHERE username = '".$username."'";
      $datauser = mysqli_query($conn, $selectuser);

      if($datauser){

        $row = mysqli_num_rows($datauser);

        if($row > 0){
          echo "<script>alert('Username already exists!')</script>";
        }
        else{

          //check password if match
          if($password != $repassword){
            echo "<script>alert('Password does not match!')</script>";
          }
          else{

            //register member
            $sql = "INSERT INTO member (user_type, full_name, address, contact_number, username, upassword) VALUES ('$usertype', '$full_name', '$address', '$contact_num', '$username', '$password')";
            $data = mysqli_query($conn,$sql);

            //check if data save to database
            if($data){

              $_SESSION['username'] = $username;
              echo "<script>alert('Registered Successfully!')</script>";
              header('Location: index.php');

            }
            else {
              echo "<script>alert('Register Failed!')</script>";
            }

          }

        }

      }
      else{
        echo "<script>alert('Error!')</script>";
      }

  }

if(isset($_POST['login'])){
  $username = $_POST['username'];
  $password = md5($_POST['password']);

    $sql = "SELECT * FROM member WHERE username = '$username' AND upassword = '$password'";
    $data = mysqli_query($conn,$sql);

    if($data){

      $row = mysqli_num_rows($data);

      if($row > 0){
         $_SESSION['username'] = $username;
          header('Location: index.php');
      }
      else{
        echo "<script>alert('Login failed!')</script>";
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
  <link rel="stylesheet" href="loginstyle.css"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.js" integrity="sha512-6DC1eE3AWg1bgitkoaRM1lhY98PxbMIbhgYCGV107aZlyzzvaWCW1nJW2vDuYQm06hXrW0As6OGKcIaAVWnHJw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
  
  <section>
    <div class="container">
      <div class="user signinBx">
        <div class="imgBx"><img src="images/loginbg1.jpg" alt="" /></div>
        <a href="index.php" class="backbotton"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        <div class="formBx">
          <form method="POST">
            <h2>Sign In</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="Login" />
            <p class="signup">
              Don't have an account ?
              <a href="#" onclick="toggleForm();">Sign Up.</a>
            </p>
          </form>
        </div>
      </div>
      <div class="user signupBx">
        <div class="formBx">
          <form method="POST">
            <h2>Create an account</h2>
            <div class="choose">
              <label>Member
                <input type="radio" id="1" name="usertype" value="1" onclick="change(this)" style="width: 25%;" checked>
              </label>
              <label>Seller
                <input type="radio" id="2" name="usertype" value="2" onclick="change(this)" style="width: 25%;">
              </label>
            </div>
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="contact_num" placeholder="Contact Number" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <input type="password" name="repassword" placeholder="Confirm Password" required>
            <input type="submit" name="register" value="Sign Up" />
            <p class="signup">
              Already have an account ?
              <a href="#" onclick="toggleForm();">Sign in.</a>
            </p>
          </form>
        </div>
        <div class="imgBx"><img src="images/loginbg.jpg" alt="" /></div>
        <a href="index.php" class="backbotton"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
      </div>
    </div>
  </section>
</body>
</html>

<script type="text/javascript">
const toggleForm = () => {
  const container = document.querySelector('.container');
  container.classList.toggle('active');
};
</script>

<script type="text/javascript">
function change(radio) { 
  if (radio.checked && radio.id === "1") {
    $('.member').show();
    $('.seller').hide();
  } else {
    $('.seller').show();
    $('.member').hide();
  }
}
</script>

<?php } ?>
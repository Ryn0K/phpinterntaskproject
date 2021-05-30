<?php
session_start();
require_once("includes/db.inc.php");
?>
<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <!-- Site Css Sheets-->
  <link rel="stylesheet" type="text/css" href="static/semantic-ui/semantic.min.css">
  <!-- Site Properties -->
  <title>Login Page</title>
  <style type="text/css">
    body {
      background-color: #DADADA;
    }
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      max-width: 450px;
    }
  </style>

  <script src="static/jquery/jquery.slim.min.js"></script>
  <script>
  $(document)
    .ready(function() {
      $('.ui.form')
        .form({
          fields: {
            email: {
              identifier  : 'email',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your e-mail'
                },
                {
                  type   : 'email',
                  prompt : 'Please enter a valid e-mail'
                }
              ]
            },
            password: {
              identifier  : 'password',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your password'
                },
                {
                  type   : 'length[6]',
                  prompt : 'Your password must be at least 6 characters'
                }
              ]
            }
          }
        })
      ;
    })
  ;
  </script>
</head>
<body>

<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui teal image header">
      <div class="content">
        Log-in to your account
      </div>
    </h2>

<?php
    class LOGIN extends DB{
      function userExist($email,$password){
        try{
          $query = 'SELECT * from user where email=?';  
          $stmt = $this->connecttodb()->prepare($query);
          $stmt->execute([$email]);
          $data = $stmt->fetch(PDO::FETCH_ASSOC);
          $count = $stmt->rowCount();
          if($count > 0){
            //user exists
            if(password_verify($password,$data['password'])){
              $_SESSION['id'] = $data['id'];
              $_SESSION['username']= $data['username'];
              header('Location:index.php');
              exit();
            }
            else{
              print("<div id='messages' class='ui floating message red'>Invalid username or password.</div>");
            }
          }
        }
        catch(PDOException $e){
          print("<div id='messages' class='ui floating message red'>{$e}</div>");
        }
      }
    }
    if(isset($_POST["login"])){
      //validations before sending to database.
      if(isset($_POST["email"]) && isset($_POST["password"])) {
        $log= new LOGIN();
        $log->userExist($_POST['email'],$_POST['password']);
      }
      else{ 
        print("<div id='messages' class='ui floating message red'>Something missing.</div>");
      }
    }
?>
    <form method="POST" action="login.php" class="ui large form">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="email" placeholder="E-mail address">
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Password">
          </div>
        </div>
        <input type="submit" class="ui fluid large teal submit button" name="login" value="Login">
      </div>

      <div class="ui error message"></div>

    </form>

    <div class="ui message">
      New to us? <a href="signup.php">Sign Up</a>
    </div>
  </div>
</div>

<script src="static/semantic-ui/semantic.min.js"></script>
</body>

</html>


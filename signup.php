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
  <title>Signup Page</title>
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

            username: {
              identifier  : 'username',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your username'
                },
                {
                  type  : 'length[3]',
                  prompt : 'Your username must be at least 3 characters'
                }
              ]
            },
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
                  prompt : 'Your password must be at least {ruleValue} characters'
                }
              ]
            },
            cpassword: {
              identifier  : 'cpassword',
              rules: [
                {
                  type   : 'match[password]',
                  prompt : 'Confirm password and password should be equal'
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
      Register new account
      </div>
    </h2>
<?php
    class REGISTER extends DB{

      function checkifemailexist($email){
        try{
          $query = 'SELECT * from user where email=?';  
          $stmt = $this->connecttodb()->prepare($query);
          $stmt->execute([$email]);
          $count = $stmt->rowCount();
          if($count > 0){
            //email exists
            return True;
          }
          return False;
        }
        catch(PDOException $e){
          print("<div id='messages' class='ui floating message red'>{$e}</div>");
        }
      }
      function user($username,$email,$password){
        try{
          $query = 'INSERT INTO user(username,email,password) VALUES(?,?,?)';  
          $this->connecttodb()->prepare($query)->execute([$username,$email,$password]);
        }
        catch(PDOException $e){
          print("<div id='messages' class='ui floating message red'>{$e}</div>");
        }
      }
    }
    if(isset($_POST["signup"])){
      //validations before sending to database.
      if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["cpassword"])) {
         if($_POST["password"] === $_POST["cpassword"]){
            $reg= new REGISTER();
            if(!$reg->checkifemailexist($_POST['email'])){
               $reg->user($_POST["username"],$_POST["email"],password_hash($_POST["password"],PASSWORD_DEFAULT));
               print("<div id='messages' class='ui floating message green'>Registered successfully,<a href='login.php'>Now login to get started.</a></div>");
            }
            else{
              print("<div id='messages' class='ui floating message red'>Email already exists.</div>");
            }
         }
         else{
            print("<div id='messages' class='ui floating message red'>Confirm password not equal.</div>");
         }
      }
      else{ 
        print("<div id='messages' class='ui floating message red'>Something missing.</div>");
      }
    }
?>
    <form action="signup.php" method="POST" class="ui large form">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user circle outline icon"></i>
            <input type="text" name="username" placeholder="Set username">
          </div>
        </div>
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
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="cpassword" placeholder="Confirm password">
          </div>
        </div>

        <input type="submit" class="ui fluid large teal submit button" name="signup" value="signup">
      </div>

      <div class="ui error message"></div>

    </form>

    <div class="ui message">
      Already have account? <a href="login.php">Login</a>
    </div>
  </div>
</div>

<script src="static/semantic-ui/semantic.min.js"></script>
</body>

</html>


<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    session_start();
    
    require_once("db/db.php");
    require_once("model/todo-item.php");
    
    $conn = connect();
    
    $loggedIn = false;
    
    // ha be vagyunk jelentkezve:
    if (isset($_SESSION["loggedInUser"])) {
    
      $userId = $_SESSION["loggedInUser"];
      $loggedIn = true;
    
      // uj elemet akarunk beszurni?
      if (isset($_POST["todo"])) {
        $todo = $_POST["todo"];
        newItem($conn, new TodoItem(0, $todo, $userId));
      }
    
      // elemet akarunk torolni?
      if (isset($_GET["delete"])) {
        deleteItem($conn, $_GET["delete"]);
      }
    
      // ki akarunk jelentkezni?
      if (isset($_GET["action"]) && $_GET["action"] == "logout") {
        unset($_SESSION["loggedInUser"]);
        header("Location: /vizsgaremek_todo/");
        die();
      }
    
      // lekerdezzuk a bejelentkezett felhasznalo listajat
      $todoItems = getItems($conn, $userId);
    
      // Ha nem vagyunk bejelentkezve:
    } else{
      if (isset($_POST["username"])) {
        $username = $_POST["username"];
        $user = login($conn, $username);
        $_SESSION["loggedInUser"] = $user->id;
        $todoItems = getItems($conn, $user->id);
        $loggedIn = true;
      }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="To-Do list" content="Enter your description here"/>
<meta name="author" content="Berei Zsolt"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="style.css">
<title>Tadam TO-DO</title>
</head>
<body>


  <div class="row">
    <div class="col-md-6 offset-md-3">

      <?php if ($loggedIn) : ?>
      <h1>TODO List</h1>

      <a href="?action=logout">Log out</a>

      <ul>
        <?php
          foreach ($todoItems as $todoItem) {
            echo ("<li>{$todoItem->text} <a href='?delete={$todoItem->id}'> Delete</a></li>");
          }
          ?>
      </ul>

      <form method="POST">
        <label>New TODO Item</label>
        <input class="form-control mb-3" name="todo" type="text">
        <input class="btn btn-primary" type="submit" value="Add">
      </form>

      <?php else : ?>

      <h2>Please log in</h2>

      <form method="POST">
        <label>Username</label>
        <input class="form-control mb-3" name="username" type="text">
        <input class="btn btn-primary" type="submit" value="Sign in">
      </form>

      <?php endif ?>
    </div>
  </div>


    
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/js/bootstrap.min.js"></script>
</body>
</html>
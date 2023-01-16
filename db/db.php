<?php

require_once("model/user.php");

function connect()
{
    $db_username = "root";
    $db_password = "";
    $db_host = "localhost";
    $db_database = "vizsgaremek-todo";

  $conn = mysqli_connect(
    $db_host,
    $db_username,
    $db_password,
    $db_database
  );

  return $conn;
}

function getItems($conn, $userId)
{
  $sql = "SELECT * FROM `todoitem` WHERE `user` = $userId";
  
  $result = mysqli_query($conn, $sql);

  $todoItems = [];

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $todoItems[] = new TodoItem($row["id"], $row["text"], $row["user"]);
    }
  }

  return $todoItems;
}

function newItem($conn, $todoItem)
{
  $sql = "INSERT INTO `todoitem` (`text`, `user`) VALUES ('{$todoItem->text}', '{$todoItem->user}');";

  $result = mysqli_query($conn, $sql);

  return $result;
}

function deleteItem($conn, $todoItemId)
{
  $sql = "DELETE FROM todoitem WHERE `todoitem`.`id` = $todoItemId";

  $result = mysqli_query($conn, $sql);

  return $result;
}

function login($conn, $username)
{
  $sql = "SELECT * FROM `user` WHERE `username` = '$username'";

  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 0) {
    $sql = "INSERT INTO `user` (`username`) VALUES ('$username') ";
    $result = mysqli_query($conn, $sql);

    return login($conn, $username);
  } else {
    $row = mysqli_fetch_assoc($result);
   
    return new User(
      $row["id"],
      $row["username"]
    );
  }
}
?>
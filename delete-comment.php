<?php
//connection to database
require "config.php";

if(isset($_POST['delete'])) {
    $id = $_POST['id'];


    //delete a comment by the registered user using the user 'id'
    $delete = $conn->prepare("DELETE FROM comments WHERE id ='$id'");
    
    //excute the delete comment query
    $delete->execute();
}

?>

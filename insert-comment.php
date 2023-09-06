<?php 
    //connection to database
    require "config.php"; ?>

<?php

    if(isset($_POST['submit'])) {
        //check for empty vlaue and echo error message.
        if($_POST['comment'] == "") {
      
            echo "some inputs are empty"; 
        }else{
        //input comment and display values 
        $username = $_POST['username'];
        $post_id = $_POST['post_id'];
        $comment = $_POST['comment'];

        $insert = $conn->prepare("INSERT INTO comments (username, post_id, comment)
        VALUES (:username, :post_id, :comment)");
        
        //execute comment values
        $insert->execute([
            ':username' => $username,
            ':post_id' => $post_id,
            ':comment' => $comment,
        ]);
    }
}


?>
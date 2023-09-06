<?php require "includes/header.php"; ?>
<?php require "config.php"; ?>
<?php 

        if(isset($_GET['id'])) {

            $id = $_GET['id'];

            // using id to fetch and display data from mysql database
            $onePost = $conn->
            query("SELECT * FROM posts WHERE id='$id'");
            $onePost->execute();

            $posts = $onePost->fetch(PDO::FETCH_OBJ);

        }

            // using id to fetch and display comments from mysql database
            $comments = $conn->query("SELECT * FROM comments WHERE post_id='$id'");
            $comments->execute();

            $comment = $comments->fetchAll(PDO::FETCH_OBJ);

            // using post_id and user_id to fetch ratings number data from mysql database and alert rating number using jquery
            $ratings = $conn->query("SELECT * FROM rates WHERE post_id='$id' AND user_id='$_SESSION[user_id]");
            $ratings->execute();

            $rating = $ratings->fetch(PDO::FETCH_OBJ);


?>

<div class="container">
    <div class="card mt-5">
        <div class="card-body ">

            <h5 class="card-title"> <?php echo $posts->title; ?> </h5>
            <p class="card-text"><?php echo $posts->body; ?></p>
                <form id="form-data" method="POST">
                    <div class="my-rating"></div>
                    <input id="rating" type="hidden" name="rating" value="" >
                    <input id="post_id" type="hidden" name="post_id" value="<?php echo $posts->id; ?>">
                    <input id="user_id" type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                </form>

        </div>
    </div>
</div>


<div class="row">
  <form method="POST" id="comment_data">
        
    <div class="form-floating">
      <input name="username" type="hidden" value="<?php echo $_SESSION['username']; ?>" class="form-control" id="username">
    </div>

    <div class="form-floating">
      <input name="post_id" type="hidden" value="<?php echo $posts->id; ?>" class="form-control" id="post_id">
    </div>

    <div class="form-floating mt-4">
        <textarea rows="9" name="comment" placeholder="Comment" class="form-control" id="comment"></textarea>
        <label for="floatingPassword">Comment</label>
    </div>

    <button name="submit" id="submit" class="w-100 btn btn-lg btn-primary mt-4" type="submit">Create Comment</button>
    <div id="msg" class="nothing"></div>
    <div id="delete-msg" class="nothing"></div>

    </form>
</div>

<div class="container">
    <?php foreach($comment as $singleComment) : ?>
    <div class="card mt-2">
        <div class="card-body ">
            <h5 class="card-title"> <?php echo $singleComment->username; ?> </h5>
            <p class="card-text"><?php echo $singleComment->comment; ?></p>
            <?php if(isset($_SESSION['username']) AND $_SESSION['username'] == $singleComment->username) : ?>
            <button id="delete-btn" value="<?php echo $singleComment->id; ?>" class=" btn btn-danger mt-1" >Delete</button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require "includes/footer.php"; ?>

<script>
    //jquery form subbmission
     $(document).ready(function() {
         
        $(document).on('submit', function(e) {
            //alert('form submitted');
            e.preventDefault();

            var formdata = $('#comment_data').serialize()+'&submit=submit';

            //process form request with ajax
            $.ajax({
                type: 'post',
                url: 'insert-comment.php',
                data: formdata,

                //form submission success
                success: function() {
                    //alert('success');
                    $("#comment").val(null);
                    $("#username").val(null);
                    $("#post_id").val(null);

                    $("#msg").html("Comment Adding...").toggleClass("alert alert-success bg-success text-white mt-3");
                    fetch();
                }
            });
        });

        //jquery comment delete button
        $("#delete-btn").on('click', function(e) {
            //alert('form submitted');
            e.preventDefault();

            var id = $(this).val();

            //process form request with ajax
            $.ajax({
                type: 'post',
                url: 'delete-comment.php',
                data: {
                    delete: 'delete',
                    id: id
                },

                //form submission success
                success: function() {
                    //alert(id);

                    $("#delete-msg").html("Deleting...").toggleClass("alert alert-success bg-success text-white mt-3");
                    fetch();
                }
            });
        });

        //Setting Refresh Interval to 3000ms
        function fetch() {

            setInterval(function () {
                $("body").load("show.php?id=<?php echo $_GET['id']; ?>")
            }, 3000);
        }
            //rating sys plugin call
        $(".my-rating").starRating({
            starSize: 20,

            initialRating: "<?php 
            
                if(isset($rating->rating) AND isset($rating->user_id) AND $rating->user_id == $_SESSION['user_id'] ) {
                    echo $rating->rating;
                } else {
                    echo '0';
                }
            
            ?>",

            callback: function(currentRating, $el){
            // make a server call here
            $("#rating").val(currentRating);

                $(".my-rating").click(function(e) {
                    e.preventDefault();

                    var formdata = $("#form-data").serialize()+'&insert=insert';

                    $.ajax({
                        type: "POST",
                        url: 'insert-ratings.php',
                        data: formdata,

                        success: function() {
                            alert(formdata);
                        }
                    })
                })

        }
});

     });

</script>

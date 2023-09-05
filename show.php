<?php require "includes/header.php"; ?>
<?php require "config.php"; ?>
<?php 

        if(isset($_GET['id'])) {

            $id = $_GET['id'];

            $onePost = $conn->
            query("SELECT * FROM posts WHERE id='$id'");
            $onePost->execute();

            $posts = $onePost->fetch(PDO::FETCH_OBJ);

        }

            $comments = $conn->query("SELECT * FROM comments WHERE post_id='$id'");
            $comments->execute();

            $comment = $comments->fetchAll(PDO::FETCH_OBJ);


?>

<div class="container">
    <div class="card mt-5">
        <div class="card-body ">
            <h5 class="card-title"> <?php echo $posts->title; ?> </h5>
            <p class="card-text"><?php echo $posts->body; ?></p>
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

                    $("#msg").html("Added Successfully").toggleClass("alert alert-success bg-success text-white mt-3");
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

        //setting refresh interval to 3000ms
        function fetch() {

            setInterval(function () {
                $("body").load("show.php?id=<?php echo $_GET['id']; ?>")
            }, 3000);
        }

     });

</script>

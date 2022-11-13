<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blogs Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }

        table tr td:last-child {
            width: 120px;
        }
    </style>


    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="mt-5 mb-6 clearfix">
                    <h2 class="pull-left">Blog Posts</h2>
                    <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add a new blog
                        post</a>
                </div>
                <?php
                // Include config file
                use Model\BlogPost;

                require_once "db/config.php";
                require __DIR__ . '/vendor/autoload.php';


                // Attempt select query execution
                $sql = "SELECT * FROM blogs";
                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $post = new BlogPost($row);
                            echo '<div class="card">';
                            echo '<div class="card-header">' . $post->getCreatedOn() . ' by ' . $post->getAuthor() . '</div>';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $post->getTitle() . '</h5>';
                            echo '<p class="card-text">' . $post->getBody() . '</p>';
                            echo '<a href="read.php?id=' . $post->getId() . '" class="mr-3" title="View Blog" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                            echo '<a href="update.php?id=' . $post->getId() . '" class="mr-3" title="Update Blog" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                            echo '<a href="delete.php?id=' . $post->getId() . '" title="Delete Blog" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                            echo '</div>';
                            echo '</div>';
                            echo '<br>';
                            echo '<br>';
                        }

                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close connection
                mysqli_close($link);
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>

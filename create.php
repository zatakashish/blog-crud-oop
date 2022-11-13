<?php

use Model\BlogPost;

require __DIR__ . '/vendor/autoload.php';
require_once "db/config.php";

// Define variables and initialize with empty values
$author     = $title = $body = "";
$author_err = $title_err = $body_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $post = new BlogPost([]);

    // Validate author name
    $input_name = trim($_POST["author"]);
    if (empty($input_name)) {
        $author_err = "Please enter a valid author name.";
    } else {
        $author = $input_name;
        $post->setAuthor($input_name);
    }

    // Validate blog title
    $input_title = trim($_POST["title"]);
    if (empty($input_title)) {
        $title_err = "Please enter a valid blog title.";
    } else {
        $title = $input_title;
        $post->setTitle($input_title);
    }

    // Validate blog body
    $input_body = trim($_POST["body"]);
    if (empty($input_body)) {
        $body_err = "Please enter a valid blog content.";
    } else {
        $body = $input_body;
        $post->setBody($input_body);
    }

    // Check input errors before inserting in database
    if (empty($author_err) && empty($title_err) && empty($body_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO blogs (author, title, body) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            $a = mysqli_stmt_bind_param($stmt, "sss", $param_author, $param_title, $param_body);

            // Set parameters
            $param_author = $post->getAuthor();
            $param_title  = $post->getTitle();
            $param_body   = $post->getBody();

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Blog Post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5">Create a blog post</h2>
                <p>Please fill this form and submit to add a blog post to the database.</p>
                <form action="#" method="post">
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" name="author"
                               class="form-control <?php echo (!empty($author_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $author; ?>">
                        <span class="invalid-feedback"><?php echo $author_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <textarea type="text" name="title"
                                  class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>"><?php echo $title; ?></textarea>
                        <span class="invalid-feedback"><?php echo $title_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Body</label>
                        <input type="text" name="body"
                               class="form-control <?php echo (!empty($body_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $body; ?>">
                        <span class="invalid-feedback"><?php echo $body_err; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
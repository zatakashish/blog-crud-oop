<?php
// Include config file
require_once "db/config.php";

use Model\BlogPost;

require __DIR__ . '/vendor/autoload.php';

// Define variables and initialize with empty values
$author     = $title = $body = "";
$author_err = $title_err = $body_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $post = new BlogPost();

    // Get hidden input value
    $id = $_POST["id"];
    $post->setId($id);

    // Validate name
    $input_name = trim($_POST["author"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } else {
        $author = $input_name;
        $post->setAuthor($input_name);
    }

    // Validate title
    $input_title = trim($_POST["title"]);
    if (empty($input_title)) {
        $title_err = "Please enter a title.";
    } else {
        $title = $input_title;
        $post->setTitle($input_title);
    }

    // Validate body
    $input_body = trim($_POST["body"]);
    if (empty($input_body)) {
        $body_err = "Please enter blog content.";
    } else {
        $body = $input_body;
        $post->setBody($input_body);
    }

    // Check input errors before inserting in database
    if (empty($author_err) && empty($title_err) && empty($body_err)) {
        // Prepare an update statement
        $sql = "UPDATE blogs SET author=?, title=?, body=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_author, $param_title, $param_body, $param_id);

            // Set parameters
            $param_author = $post->getAuthor();
            $param_title  = $post->getTitle();
            $param_body   = $post->getBody();
            $param_id     = $post->getId();

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
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
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM blogs WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    $blogPost = new BlogPost($row);

                    // Retrieve individual field value
                    $author = $blogPost->getAuthor();
                    $title  = $blogPost->getTitle();
                    $body   = $blogPost->getBody();
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                <h2 class="mt-5">Update Record</h2>
                <p>Please edit the input values and submit to update the employee record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" name="author"
                               class="form-control <?php echo (!empty($author_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $blogPost->getAuthor(); ?>">
                        <span class="invalid-feedback"><?php echo $author_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <textarea name="title"
                                  class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>"><?php echo $blogPost->getTitle(); ?></textarea>
                        <span class="invalid-feedback"><?php echo $title_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Body</label>
                        <textarea type="text" name="body"
                                  class="form-control <?php echo (!empty($body_err)) ? 'is-invalid' : ''; ?>"
                        ><?php echo $blogPost->getBody(); ?></textarea>

                        <span class="invalid-feedback"><?php echo $body_err; ?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
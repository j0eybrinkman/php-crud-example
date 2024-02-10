<?php
    include_once('../db.php');

    if(isset($_POST['create'])) {
        create();
    }

    if(isset($_POST['update'])) {
        update();
    }

    if(isset($_POST['destroy'])) {
        destroy();
    }
    
    function create() {
        global $conn;
        $name = filter_input(
            INPUT_POST,
            'name',
            FILTER_SANITIZE_SPECIAL_CHARS
        );
        $score = filter_input(
            INPUT_POST,
            'score',
            FILTER_SANITIZE_SPECIAL_CHARS
        );
        $sql = "INSERT into `demo_table` (name, score) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $name, $score);
        if($stmt->execute()) {
            header("location: index.php");
            exit();
        } else {
            echo "Error " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }

    function read() {
        global $conn;
        $sql = "SELECT * FROM `demo_table`";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            if(isset($_POST['id']) && $row['id'] == $_POST['id']) {
                echo '<td><form class="form-inline m-2" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
                echo '<td><input type="text"  class="form-control" name="name" value="' . htmlspecialchars($row['name']) . '"></td>';
                echo '<td><input type="number" class="form-control" name="score" value="' . htmlspecialchars($row['score']) . '"></td>';
                echo '<td><button type="submit" class="btn btn-primary" name="update">Save</button></td></form></td>';
            } else {
                echo '<td><form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
                echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['score'] . "</td>";
                echo '<td><button type="submit" class="btn btn-primary">Update</button></form></td>';
            }
            echo '<td><form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="POST">';
            echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
            echo '<td><button type="submit" class="btn btn-danger" name="destroy">Destroy</button></form></td>';
            echo "</tr>";
        }
        $stmt->close();
    }

    function update() {
        global $conn;
        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_SANITIZE_SPECIAL_CHARS
        );
        $name = filter_input(
            INPUT_POST,
            'name',
            FILTER_SANITIZE_SPECIAL_CHARS
        );
        $score = filter_input(
            INPUT_POST,
            'score',
            FILTER_SANITIZE_SPECIAL_CHARS
        );
        $sql = "UPDATE `demo_table` SET name=?, score=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sii', $name, $score, $id);
        if($stmt->execute()) {
            header("location: index.php");
            exit();
        } else {
            echo "Error " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }

    function destroy() {
        global $conn;
        $id = filter_input(
            INPUT_POST, 
            'id',
            FILTER_SANITIZE_SPECIAL_CHARS
        );
        $sql = "DELETE FROM `demo_table` WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("location: index.php");
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP + MySQL CRUD Demo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <table class="table">
            <tbody>
                <?php read(); ?>
            </tbody>
        </table>
        <h1>PHP + MySQL CRUD Demo</h1>
        <p>Create, read, update, and delete records below</p>

        <form class="form-inline m-2" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

            <label for="name">Name: </label>
            <input type="text" id="name" class="form-control m-2" name="name">

            <label for="score">Score: </label>
            <input type="number" id="score" class="form-control m-2" name="score">

            <button type="submit" class="btn btn-primary" id="create" name="create">Create</button>

        </form>
    </div>
</body>
</html>
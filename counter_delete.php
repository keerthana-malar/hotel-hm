<?php


if (isset($_GET['delete_id'])) {
    $counterID = $_GET['delete_id'];

    // Delete the cuisine from the database
    $deleteSql = "DELETE FROM counter WHERE id = :id";
    $stmt = $pdo->prepare($deleteSql);
    $stmt->bindParam(':id', $counterID);

    if ($stmt->execute()) {
        header("Location: counter.php?succ=" . urlencode('Counter Successfully Deleted'));
    } else {
        header("Location: counter.php?err=" . urlencode('Something went wrong. Please try again later'));
    }
} else {
    header("Location: counter.php");
    exit();
}
?>
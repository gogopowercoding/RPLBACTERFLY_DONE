<?php
session_start();
require 'koneksi.php'; // koneksi ke database

// Ensure user is logged in

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php?logout=true");
    exit();
}

// Check if the action and id are provided for marking an instruction as done
if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] === 'mark_done') {
    $id = $_GET['id'];

    // Validate ID
    if (!is_numeric($id) || $id <= 0) {
        header("Location: manajer.php?error=" . urlencode("Invalid instruction ID."));
        exit();
    }

    try {
        // Prepare and execute the update query
        $update_query = "UPDATE instructions SET status = 'done' WHERE id = ?";
        $stmt = $pdo->prepare($update_query);
        $stmt->execute([$id]);

        // Redirect back to the instructions page after success
        header("Location: manajer.php");
        exit();
    } catch (PDOException $e) {
        // Log error and redirect with error message
        error_log("Error updating status: " . $e->getMessage());
        header("Location: manajer.php?error=" . urlencode("Error updating status."));
        exit();
    }
}

// If no valid action, redirect to the instructions page
header("Location: manajer.php");
exit();
?>
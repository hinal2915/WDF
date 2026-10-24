<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'studenthub_db');

$sql = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head><title>Event Management</title></head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Admin: Manage Events</h2>
    
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p style='color: green; font-weight: bold;'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red; font-weight: bold;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <a href="event_form.php" style="padding: 10px 15px; background: #0d6efd; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 20px;">+ Add New Event</a>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
        <tr style="background: #f8f9fa;">
            <th>Poster</th>
            <th>Title</th>
            <th>Date</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="uploads/<?php echo htmlspecialchars($row['poster_image']); ?>" width="80" height="80" style="object-fit: cover; border-radius: 4px;"></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <a href="event_form.php?id=<?php echo $row['event_id']; ?>" style="color: #0d6efd; text-decoration: none;">Edit</a> | 
                        <a href="event_action.php?action=delete&id=<?php echo $row['event_id']; ?>" 
                           onclick="return confirm('Delete this event?');" 
                           style="color: red; text-decoration: none;">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align: center;">No events found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
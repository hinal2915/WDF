<?php
$conn = new mysqli('localhost', 'root', '', 'studenthub_db');

$id = $_GET['id'] ?? '';
$title = $description = $date = $poster = '';
$is_edit = false;

if (!empty($id)) {
    $is_edit = true;
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $title = $row['title'];
        $description = $row['description'];
        $date = $row['event_date'];
        $poster = $row['poster_image'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><title><?php echo $is_edit ? 'Edit' : 'Add'; ?> Event</title></head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2><?php echo $is_edit ? 'Edit Event' : 'Create New Event'; ?></h2>
    
    <div style="max-width: 500px; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        <form method="POST" action="event_action.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $is_edit ? 'update' : 'create'; ?>">
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="existing_poster" value="<?php echo htmlspecialchars($poster); ?>">
            
            <label>Event Title:</label><br>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required style="width: 95%; padding: 8px; margin: 5px 0 15px 0;"><br>
            
            <label>Event Date:</label><br>
            <input type="date" name="event_date" value="<?php echo htmlspecialchars($date); ?>" required style="width: 95%; padding: 8px; margin: 5px 0 15px 0;"><br>
            
            <label>Description:</label><br>
            <textarea name="description" required style="width: 95%; padding: 8px; margin: 5px 0 15px 0; height: 80px;"><?php echo htmlspecialchars($description); ?></textarea><br>
            
            <label>Upload Poster Image (JPG, PNG, GIF - Max 2MB):</label><br>
            <?php if ($is_edit): ?>
                <p style="font-size: 12px; color: gray;">Leave blank to keep existing poster.</p>
            <?php endif; ?>
            <input type="file" name="poster" accept="image/png, image/jpeg, image/gif" <?php echo $is_edit ? '' : 'required'; ?> style="margin: 5px 0 20px 0;"><br>
            
            <button type="submit" style="padding: 10px 15px; background: #198754; color: white; border: none; cursor: pointer; border-radius: 4px;">
                <?php echo $is_edit ? 'Update Event' : 'Save Event'; ?>
            </button>
            <a href="manage_events.php" style="margin-left: 15px; text-decoration: none; color: #6c757d;">Cancel</a>
        </form>
    </div>
</body>
</html>
<?php
// session_start();
require_once "db_connect.php";
require_once "auth.php";

requireLogin();

$conn = connectDB();
$sql = "SELECT id, name, email, room, ext, profile_picture FROM users";
$result = mysqli_query($conn, $sql);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

try {
    if (!$result) {
        throw new Exception(mysqli_error($conn));
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>User List</h2>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Room</th>
                    <th>EXT</th>
                    <th>Profile Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['room']) ?></td>
                        <td><?= htmlspecialchars($row['ext']) ?></td>
                        <td>
                            <?php if ($row['profile_picture']): ?>
                                <img src="<?= htmlspecialchars($row['profile_picture']) ?>" 
                                     alt="Profile" class="img-thumbnail" style="width: 50px">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_user.php?id=<?= $row['id'] ?>" 
                               class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_user.php?id=<?= $row['id'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
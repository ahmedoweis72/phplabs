<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$conn = connectDB();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $room = mysqli_real_escape_string($conn, $_POST['room']);
    $ext = mysqli_real_escape_string($conn, $_POST['EXT']);
    
    $sql = "UPDATE users SET 
            name = '$name',
            email = '$email',
            room = '$room',
            ext = '$ext'
            WHERE id = $id";
            
    if (mysqli_query($conn, $sql)) {
        header('Location: tabledata.php');
        exit;
    }
}

$sql = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" 
                       value="<?php echo htmlspecialchars($user['name']); ?>">
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            <div class="mb-3">
                <label>Room</label>
                <select name="room" class="form-control">
                    <option value="Application1" <?php echo $user['room'] == 'Application1' ? 'selected' : ''; ?>>Application1</option>
                    <option value="Application2" <?php echo $user['room'] == 'Application2' ? 'selected' : ''; ?>>Application2</option>
                    <option value="cloud" <?php echo $user['room'] == 'cloud' ? 'selected' : ''; ?>>Cloud</option>
                </select>
            </div>
            <div class="mb-3">
                <label>EXT</label>
                <input type="text" name="EXT" class="form-control"
                       value="<?php echo htmlspecialchars($user['ext']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="tabledata.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
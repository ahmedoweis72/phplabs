<?php
session_start();
require_once "db_connect.php";
require_once "utils.php";
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (empty($_POST['name'])) $errors['name'] = "Name is required";
    if (empty($_POST['email'])) $errors['email'] = "Email is required";
    if (empty($_POST['password'])) $errors['password'] = "Password is required";
    if ($_POST['password'] !== $_POST['re-password']) $errors['password'] = "Passwords do not match";

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
        $uploadPath = $uploadDir . $fileName;

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['profile_picture']['type'], $allowedTypes)) {
            $errors['profile_picture'] = "Invalid file type. Only JPG, PNG and GIF allowed.";
        } else if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
            $errors['profile_picture'] = "Failed to upload image";
        }
    }

    if (empty($errors)) {
        try {
            $conn = connectDB();

            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $room = mysqli_real_escape_string($conn, $_POST['room']);
            $ext = mysqli_real_escape_string($conn, $_POST['EXT']);
            $profilePic = isset($uploadPath) ? mysqli_real_escape_string($conn, $uploadPath) : '';

            $sql = "INSERT INTO users (name, email, password, room, ext, profile_picture) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $password, $room, $ext, $profilePic);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Registration successful!";
                header('Location: login.php');
                exit;
            } else {
                throw new Exception(mysqli_error($conn));
            }
        } catch (Exception $e) {
            $errors['general'] = "Error registering user: " . $e->getMessage();
        } finally {
            if (isset($stmt)) mysqli_stmt_close($stmt);
            if (isset($conn)) mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Register</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errors['general'])): ?>
                            <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                                <?php if (isset($errors['password'])): ?>
                                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert"><span class="font-medium"><?php echo $errors['name']; ?></span></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                                <?php if (isset($errors['password'])): ?>
                                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert"><span class="font-medium"><?php echo $errors['email']; ?></span></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <?php if (isset($errors['password'])): ?>
                                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert"><span class="font-medium"><?php echo $errors['password']; ?></span></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="re-password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="re-password" name="re-password">
                            </div>

                            <div class="mb-3">
                                <label for="room" class="form-label">Room</label>
                                <select class="form-control" id="room" name="room">
                                    <option value="Application1">Application1</option>
                                    <option value="Application2">Application2</option>
                                    <option value="cloud">Cloud</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="EXT" class="form-label">EXT</label>
                                <input type="text" class="form-control" id="EXT" name="EXT">
                            </div>

                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                                <?php if (isset($errors['profile_picture'])): ?>
                                    <div class="text-danger"><?php echo $errors['profile_picture']; ?></div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
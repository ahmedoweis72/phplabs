<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>PHP Form</title>
</head>
<body class="flex justify-center items-center min-h-screen bg-gray-100">
    <?php
    session_start();
    $captcha_code = rand(1000, 9999);
    $_SESSION['captcha'] = $captcha_code;
    ?>
    
    <form action="registration.php" method="POST" class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-semibold mb-4">Registration</h2>
        
        <label class="block mb-2">First Name</label>
        <input type="text" name="first_name" class="w-full p-2 border rounded mb-3" required>

        <label class="block mb-2">Last Name</label>
        <input type="text" name="last_name" class="w-full p-2 border rounded mb-3" required>

        <label class="block mb-2">Address</label>
        <textarea name="address" class="w-full p-2 border rounded mb-3" required></textarea>

        <label class="block mb-2">Country</label>
        <select name="country" class="w-full p-2 border rounded mb-3">
            <option>Select Country</option>
            <option>USA</option>
            <option>UK</option>
            <option>India</option>
        </select>

        <label class="block mb-2">Gender</label>
        <div class="mb-3">
            <label class="mr-4"><input type="radio" name="gender" value="Mr."> Male</label>
            <label><input type="radio" name="gender" value="Miss"> Female</label>
        </div>

        <label class="block mb-2">Skills</label>
        <div class="mb-3">
            <label class="mr-4"><input type="checkbox" name="skills[]" value="PHP"> PHP</label>
            <label class="mr-4"><input type="checkbox" name="skills[]" value="MySQL"> MySQL</label>
            <label class="mr-4"><input type="checkbox" name="skills[]" value="J2EE"> J2EE</label>
            <label><input type="checkbox" name="skills[]" value="PostgreSQL"> PostgreSQL</label>
        </div>

        <label class="block mb-2">Username</label>
        <input type="text" name="username" class="w-full p-2 border rounded mb-3" required>

        <label class="block mb-2">Password</label>
        <input type="password" name="password" class="w-full p-2 border rounded mb-3" required>

        <label class="block mb-2">Department</label>
        <input type="text" name="department" class="w-full p-2 border rounded mb-3">

        <label class="block mb-2">Captcha</label>
        <div class="flex items-center mb-3">
            <span class="bg-gray-200 p-2 text-lg font-semibold mr-2"> <?php echo $_SESSION['captcha']; ?> </span>
            <input type="text" name="captcha" class="w-full p-2 border rounded" required>
        </div>

        <div class="flex justify-between">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
            <button type="reset" class="bg-gray-400 text-white px-4 py-2 rounded">Reset</button>
        </div>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <?php 
        if ($_POST['captcha'] == $_SESSION['captcha']) { ?>
            <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md mt-6">
                <h2 class="text-2xl font-semibold mb-4">Review</h2>
                <p>Thanks (<?php echo htmlspecialchars($_POST['gender'] ?? ''); ?>) <?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?> <?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?></p>
                <h3 class="font-semibold mt-4">Please Review Your Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($_POST['first_name'] ?? '') . ' ' . htmlspecialchars($_POST['last_name'] ?? ''); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($_POST['address'] ?? ''); ?></p>
                <p><strong>Your Skills:</strong> <?php echo implode(', ', $_POST['skills'] ?? []); ?></p>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($_POST['department'] ?? ''); ?></p>
            </div>
        <?php } else { ?>
            <p class="text-red-500 font-semibold mt-4">Captcha verification failed. Please try again.</p>
        <?php } ?>
    <?php endif; ?>
</body>
</html>

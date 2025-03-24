<?php
$fName =$_POST['first_name'];
$lName =$_POST['last_name'];
$address=$_POST['address'];
$skills=implode(', ', $_POST['skills'] ?? []);
$department=$_POST['department'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>PHP Form</title>
</head>
<body class="flex justify-center items-center min-h-screen bg-gray-100">
<div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md mt-6">
    <h2 class="text-2xl font-semibold mb-4">Review</h2>
    <p>Thanks <?= $fName . ' ' . $lName; ?></p>
    <h3 class="font-semibold mt-4">Please Review Your Information</h3>
    <p><strong>Name:</strong> <?= $fName . ' ' . $lName; ?></p>
    <p><strong>Address:</strong> <?php echo $address;?></p>
    <p><strong>Your Skills:</strong> <?php echo $skills; ?></p>
    <p><strong>Department:</strong> <?php echo $department; ?></p>
</div>
</body>
</html>
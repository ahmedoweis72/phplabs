<?php
'<script src="https://cdn.tailwindcss.com"></script>';
$correctCaptcha = "14QbZ";
$userCaptcha = $_POST["captcha"];

if ($userCaptcha !== $correctCaptcha) {
    header("Location: form.html?error=captcha");
    exit();
}
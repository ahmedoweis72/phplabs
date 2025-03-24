<?php
require_once 'config.php';

function drawTable($header, $tableData) {
    if (!is_array($header) || !is_array($tableData)) {
        throw new InvalidArgumentException('Invalid input data for table');
    }
    
    $html = '<div class="container">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>';
    
    foreach ($header as $value) {
        $html .= "<th>" . htmlspecialchars($value) . "</th>";
    }
    
    $html .= "</tr></thead><tbody>";

    foreach ($tableData as $row) {
        $html .= "<tr>";
        foreach ($row as $field) {
            $html .= "<td>" . htmlspecialchars($field) . "</td>";
        }
        $html .= '<td>
            <a class="btn btn-danger" href="/Lab2/delete.php?id=' . htmlspecialchars($row['id']) . '">Delete</a>
        </td></tr>';
    }

    $html .= "</tbody></table></div></div>";
    echo $html;
}

function generateID() {
    $idFile = "ids.txt";
    
    try {
        if (file_exists($idFile)) {
            $id = (int)file_get_contents($idFile);
            $id++;
        } else {
            $id = 1;
        }
        
        file_put_contents($idFile, $id);
        return $id;
        
    } catch (Exception $e) {
        error_log("Error generating ID: " . $e->getMessage());
        return false;
    }
}

function saveDataToFile($filename, $data) {
    try {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if ($jsonData === false) {
            throw new Exception("Failed to encode JSON data");
        }
        
        if (!file_put_contents($filename, $jsonData)) {
            throw new Exception("Failed to write to file");
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Error saving data: " . $e->getMessage());
        return false;
    }
}

function readDataFromFile($filename) {
    try {
        if (!file_exists($filename)) {
            return [];
        }
        
        $jsonData = file_get_contents($filename);
        if ($jsonData === false) {
            throw new Exception("Failed to read file");
        }
        
        $data = json_decode($jsonData, true);
        if ($data === null) {
            throw new Exception("Failed to decode JSON data");
        }
        
        return $data;
    } catch (Exception $e) {
        error_log("Error reading data: " . $e->getMessage());
        return [];
    }
}

function validateInput($data) {
    $errors = [];
    
    foreach ($data as $key => $value) {
        if (empty($value)) {
            $errors[$key] = ucfirst($key) . " is required";
        }
    }
    
    return $errors;
}

function displayError($error) {
    return '<div class="alert alert-danger" role="alert">
        ' . htmlspecialchars($error) . '
    </div>';
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) &&
           preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email);
}

function validatePassword($password, $confirmPassword) {
    if (strlen($password) < MIN_PASSWORD_LENGTH) {
        return false;
    }
    
    if (preg_match('/[A-Z]/', $password) || preg_match('/_/', $password)) {
        return false;
    }
    
    return $password === $confirmPassword;
}

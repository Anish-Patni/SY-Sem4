<?php
session_start();

$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $fileUploaded = false;
    $uploadedFilePath = '';
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "All fields are required.";
        exit();
    }
    
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['attachment'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
                UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form.",
                UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
                UPLOAD_ERR_NO_FILE => "No file was uploaded.",
                UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
                UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
                UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload."
            ];
            
            echo "File upload error: " . ($uploadErrors[$file['error']] ?? "Unknown error");
            exit();
        }
        
        $maxFileSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxFileSize) {
            echo "File size exceeds the maximum limit of 2MB.";
            exit();
        }
        
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);
        
        if (!in_array($fileType, $allowedTypes)) {
            echo "Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, PNG.";
            exit();
        }
        
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid() . '_' . time() . '.' . $fileExt;
        $uploadPath = $uploadDir . $newFileName;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $fileUploaded = true;
            $uploadedFilePath = $uploadPath;
        } else {
            echo "Failed to save the uploaded file.";
            exit();
        }
    }
    
    $contactRecord = [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'has_attachment' => $fileUploaded,
        'attachment_path' => $uploadedFilePath,
        'date' => date('Y-m-d H:i:s')
    ];
    
    echo "<h2>Thank you for your message!</h2>";
    echo "<p>We will get back to you soon.</p>";
    
    if ($fileUploaded) {
        echo "<p>Your file was uploaded successfully.</p>";
    }
    
    header("refresh:3;url=main.html");
    exit();
}
?>

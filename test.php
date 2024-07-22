<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileData = file_get_contents($_FILES['file']['tmp_name']);
        echo 'File size: ' . strlen($fileData);
    } else {
        echo 'File upload error: ' . $_FILES['file']['error'];
    }
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit">Upload</button>
</form>

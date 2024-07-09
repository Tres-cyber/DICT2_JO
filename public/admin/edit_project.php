<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'id' is set in POST data
    if (isset($_POST['id'])) {
        // Check if the 'project_logo' file was uploaded successfully
        if (isset($_FILES['project_logo']) && $_FILES['project_logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '/storage/'; // Set your storage directory path
            $uploadFile = $uploadDir . basename($_FILES['project_logo']['name']);

            // Move the uploaded file to the storage directory
            if (move_uploaded_file($_FILES['project_logo']['tmp_name'], $uploadFile)) {
                $project_logo_path = $uploadFile; // Set project_logo_path to the uploaded file path
            } else {
                echo "File upload failed!";
                exit();
            }
        } else {
            // Handle file upload error if needed
            echo "No file uploaded or file upload error occurred.";
            exit();
        }

        // Prepare SQL query for updating Projects table
        $sql = "
            UPDATE Projects 
            SET 
                project_name = :project_name, 
                project_code = :project_code, 
                focal_person_id = :focal_person_id,
                project_logo = :project_logo
            WHERE 
                project_id = :project_id
        ";

        // Get focal_person_id from Personnels table based on 'focal_person' name
        $focal_person_id = execute("SELECT personnel_id FROM Personnels WHERE name = :name", ['name' => $_POST['focal_person']])->fetchColumn();
        if (!$focal_person_id) {
            $focal_person_id = null; // Handle if focal person ID not found
        }

        // Bind parameters for the SQL query
        $args = [
            ':project_name' => $_POST['project_name'],
            ':project_code' => $_POST['project_code'],
            ':focal_person_id' => $focal_person_id,
            ':project_logo' => $project_logo_path, // Use the uploaded file path
            ':project_id' => $_POST['id']
        ];

        // Execute the SQL query
        execute($sql, $args);

        // Redirect to projects.php after successful update
        header('Location: /admin/projects.php');
        exit();
    } else {
        // Handle if 'id' is not set in POST data
        echo "Project ID is required!";
        exit();
    }
}

// If not a POST request, redirect to projects.php
header('Location: /admin/projects.php');
exit();
?>

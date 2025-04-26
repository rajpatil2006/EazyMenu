<?php
include "../../config.php"; // Database connection

if(isset($_POST['submit'])) {
    extract($_POST);
    
    // Format owner details
    $firstname1 = ucfirst(strtolower($fname)); 
    $lastname1 = ucfirst(strtolower($lname)); 

    // Flags to track updates
    $ownerUpdated = false;
    $messUpdated = false;

    // Update Mess Owner details
    $updateOwner = mysqli_query($con, "UPDATE messowner SET 
        mofname = '$firstname1',
        molname = '$lastname1',
        moemail = '$email',
        mophone = '$phone',
        mopassword = '$password'
        WHERE moid = $mId") or die(mysqli_error($con));

    if ($updateOwner) {
        $ownerUpdated = true;
    }

    // Handle Mess details if provided
    if (!empty($mname) || !empty($mcontact) || !empty($marea) || !empty($mlocation)) {
        
        // File upload handling
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/EazyMenu/MealProvider/profile/uploads/";
        $messImageURL = "";
        $messCardURL = "";

        // Check if mess details already exist
        $checkQuery = "SELECT * FROM messdetail WHERE messownerid = $mId";
        $result = mysqli_query($con, $checkQuery);
        $row = mysqli_fetch_assoc($result);

        // File upload logic (for both Insert & Update)
        function uploadFile($file, $targetDir) {
            if (!empty($file['name'])) {
                $fileName = time() . "_" . basename($file['name']);
                $filePath = $targetDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    return "/EazyMenu/MealProvider/profile/uploads/" . $fileName; // Return correct path
                }
            }
            return "";
        }

        // Delete previous images before uploading new ones
        if ($row) {
            if (!empty($_FILES['messimage']['name'])) {
                if (!empty($row['messimage']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $row['messimage'])) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . $row['messimage']); // Delete old image
                }
                $messImageURL = uploadFile($_FILES['messimage'], $targetDir);
            } else {
                $messImageURL = $row['messimage']; // Keep old image if no new one uploaded
            }

            if (!empty($_FILES['messcard']['name'])) {
                if (!empty($row['messcard']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $row['messcard'])) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . $row['messcard']); // Delete old card
                }
                $messCardURL = uploadFile($_FILES['messcard'], $targetDir);
            } else {
                $messCardURL = $row['messcard']; // Keep old card if no new one uploaded
            }

            // Update existing mess details
            $updateMess = "UPDATE messdetail SET 
                messname = '$mname',
                messtype = '$mtype',
                messcontact = '$mcontact',
                messlocation = '$marea',
                messgmap = '$mlocation',
                messimage = '$messImageURL',
                messcard = '$messCardURL'
                WHERE messownerid = $mId";
            
            if (mysqli_query($con, $updateMess)) {
                $messUpdated = true;
            }
        } else {
            // Insert new mess details with proper image upload
            $messImageURL = uploadFile($_FILES['messimage'], $targetDir);
            $messCardURL = uploadFile($_FILES['messcard'], $targetDir);

            $insertMess = "INSERT INTO messdetail 
                (messownerid, messname, messtype, messcontact, messlocation, messgmap, messimage, messcard) 
                VALUES ('$mId', '$mname', '$mtype', '$mcontact', '$marea', '$mlocation', '$messImageURL', '$messCardURL')";
            
            if (mysqli_query($con, $insertMess)) {
                $messUpdated = true;

                // Get the newly inserted mess ID and update the messowner table
                $newMessQuery = "SELECT messid FROM messdetail WHERE messownerid = $mId";
                $newMessResult = mysqli_query($con, $newMessQuery);
                $rowM = mysqli_fetch_assoc($newMessResult);
                $meid = $rowM['messid'];

                $updQuery = "UPDATE messowner SET messid = $meid WHERE moid = $mId";
                mysqli_query($con, $updQuery) or die(mysqli_error($con));
            }
        }
    }

    // Display appropriate alert based on what was updated
    if ($ownerUpdated && $messUpdated) {
        echo "<script>
                alert('Mess Owner and Mess Details Updated Successfully!');
                window.location.href = 'mprofile.php';
              </script>";
    } elseif ($ownerUpdated) {
        echo "<script>
                alert('Mess Owner Details Updated Successfully!');
                window.location.href = 'mprofile.php';
              </script>";
    } elseif ($messUpdated) {
        echo "<script>
                alert('Mess Details Updated Successfully!');
                window.location.href = 'mprofile.php';
              </script>";
    } else {
        echo "<script>
                alert('No changes were made.');
              </script>";
    }
}
?>

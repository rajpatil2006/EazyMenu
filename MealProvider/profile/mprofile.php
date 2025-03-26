<?php
include "../../config.php";
$mId = $_GET['mId'];

// Fetch Mess Owner Details
$selectQuery = "SELECT * FROM `messowner` WHERE moid = $mId";
$Result = mysqli_query($con, $selectQuery);
$rowM = mysqli_fetch_assoc($Result);

// Fetch Mess Details (if exists)
$messQuery = "SELECT * FROM `messdetail` WHERE messownerid = $mId";
$messResult = mysqli_query($con, $messQuery);
$messExists = mysqli_num_rows($messResult) > 0;
$messData = $messExists ? mysqli_fetch_assoc($messResult) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyMenu Mess Details Management</title>
    <link rel="stylesheet" href="mprofile.css">
</head>
<body>
    <aside class="sidebar">
        <h2 class="logo">EazyMenu</h2>
        <nav>
            <ul>
                <li><a href="../mdashboard.php?mId=<?php echo $mId ?>">📊 Dashboard</a></li>
                <li><a href="" class="active">⚙️ Mess Details</a></li>
                <li><a href="../menu/todaymenu.php?mId=<?php echo $mId ?>">🍽️ Today's Menu</a></li>
                <li><a href="hhmenu.html?mId=<?php echo $mId ?>">📜 Menu History</a></li>            
                <li><a href="../../index.html" class="logout">🔒 Logout</a></li>
            </ul>
        </nav>
    </aside>
    <main class="content">
        <header>
            <h1>Welcome, <?php echo $rowM['mofname'].' '.$rowM['molname']; ?></h1>
        </header>
        <section class="profile-form">
            <h2>Update Profile</h2>
            <form action="mprofileform.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" id="fname" name="fname" required value="<?php echo $rowM['mofname']; ?>"   pattern="^[A-Za-z ]+$" 
                    title="Only alphabets and spaces are allowed">
                </div>
                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" id="lname" name="lname" required value="<?php echo $rowM['molname']; ?>"  pattern="^[A-Za-z ]+$" 
       title="Only alphabets and spaces are allowed">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required value="<?php echo $rowM['moemail']; ?>" >
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" required value="<?php echo $rowM['mophone'] ?: 'Not Added yet..'; ?>" pattern="[0-9]{10}" title="Enter a valid 10-digit contact number">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required value="<?php echo $rowM['mopassword']; ?>"pattern="^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{4,12}$" 
                    title="Password must be 4-8 characters long, contain at least one uppercase letter and one number.">
                </div>
                <p><?php echo $messExists ? "" : "No mess added yet..."; ?></p>
                <div class="form-group">
                    <label for="mname">Mess Name:</label>
                    <input type="text" id="mname" name="mname" required value="<?php echo $messExists ? $messData['messname'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="mtype">Mess Type:</label>
                    <select name="mtype" id="mtype">
                        <option value="veg" <?php echo ($messExists && $messData['messtype'] == 'veg') ? 'selected' : ''; ?>>Veg</option>
                        <option value="nonveg" <?php echo ($messExists && $messData['messtype'] == 'nonveg') ? 'selected' : ''; ?>>Non-Veg</option>
                        <option value="both" <?php echo ($messExists && $messData['messtype'] == 'both') ? 'selected' : ''; ?>>Both</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="mcontact">Mess Contact:</label>
                    <input type="tel"  required id="mcontact" name="mcontact" value="<?php echo $messExists ? $messData['messcontact'] : ''; ?>" pattern="[0-9]{10}" title="Enter a valid 10-digit contact number">
                </div>
                <div class="form-group">
                    <label for="marea">Mess Address:</label>
                    <textarea name="marea" required id="marea"> <?php echo $messExists ? $messData['messlocation'] : ''; ?> </textarea>
                </div>
                <div class="form-group">
                    <label for="mlocation">Mess Google Map Location:</label>
                    <textarea name="mlocation" required id="mlocation"> <?php echo $messExists ? $messData['messgmap'] : ''; ?> </textarea>
                    <button type="button" onclick="openGoogleMaps()">📍 Select Location</button>
                </div>
                <div class="form-group">
                    <label for="messimage">Mess Images:</label>
                    <input type="file" id="messimage" name="messimage" accept="image/*" >
                    <?php if ($messExists && !empty($messData['messimage'])) { ?>
                        <img src="<?php echo $messData['messimage']; ?>" alt="Mess Image" width="100">
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label for="messcard">Mess Card:</label>
                    <input type="file" id="messcard" name="messcard" accept="image/*" >
                    <?php if ($messExists && !empty($messData['messcard'])) { ?>
                        <img src="<?php echo $messData['messcard']; ?>" alt="Mess Card" width="100">
                    <?php } ?>
                </div>
                <input type="hidden" name="mId" value="<?php echo $mId; ?>">
                <div class="form-group">
                    <button type="submit" name="submit">Save Changes</button>
                </div>
            </form>
        </section>
    </main>
    <script src="mprofile.js"></script>
</body>
</html>

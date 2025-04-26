<?php
include "../../config.php";
session_start();
if (!isset($_SESSION['mId'])) {
    // Redirect to login if session is not set
    header("Location: ../signup/mlogin.html");
    exit();
}
$mId = $_SESSION['mId'];  
$todayDate = date('Y-m-d');

// Fetch Mess Owner Details (including messid)
$selectQuery = "SELECT * FROM messowner WHERE moid = $mId";
$Result = mysqli_query($con, $selectQuery);
$rowM = mysqli_fetch_assoc($Result);

$messId = $rowM ? $rowM['messid'] : 0;

// Fetch Mess Details (if exists)
$messQuery = "SELECT * FROM messdetail WHERE messid = $messId";
$messResult = mysqli_query($con, $messQuery);
$messData = mysqli_fetch_assoc($messResult);
$messType = $messData ? $messData['messtype'] : "unknown";

// Fetch today's menu based on messid
$menuQuery = "SELECT * FROM menu WHERE messid = $messId AND menudate = '$todayDate'";
$menuResult = mysqli_query($con, $menuQuery);

$vegMenu = ["Breakfast" => null, "Lunch" => null, "Dinner" => null];
$nonVegMenu = ["Breakfast" => null, "Lunch" => null, "Dinner" => null];

while ($row = mysqli_fetch_assoc($menuResult)) {
    if ($row['veg_menuitemprice']) {
        $vegMenu[$row['menutype']] = $row;
    }
    if ($row['nonveg_menuitemprice']) {
        $nonVegMenu[$row['menutype']] = $row;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EazyMenu Menu Details Management</title>
    <link rel="stylesheet" href="todaymenu.css">
    <script>
        function addMenuItem() {
        const container = document.getElementById('menu-items');
        const div = document.createElement('div');
        div.classList.add('menu-item-row');
        div.innerHTML = `
            <input type="text" name="menu_item[]" placeholder="Menu Item" required>
            <input type="number" name="menu_price[]" placeholder="Price" required>
            <button type="button" onclick="this.parentElement.remove()">➖</button>
        `;
        container.appendChild(div);
    }

    function openModal(type) {
        let modal = document.getElementById('mealModal');
        let mealTypeInput = document.getElementById('mealType');
        let mealTitle = document.getElementById('mealTitle');

        if (!modal || !mealTypeInput || !mealTitle) {
            console.error("Modal elements not found!");
            return;
        }

        mealTypeInput.value = type; // Set the hidden field to Veg/Non-Veg
        mealTitle.innerText = (type === 'veg') ? "Add Veg Meal" : "Add Non-Veg Meal";

        modal.style.display = 'block'; // Show the modal
    }

    function closeModal() {
        let modal = document.getElementById('mealModal');
        if (modal) {
            modal.style.display = 'none'; // Hide the modal
        }
    }

    function openUpdateModal(menuid, menutype, menuitems, menuotime, menuctime, menudate, mealtype) {
    let modal = document.getElementById('updateMealModal');
    let mealTypeInput = document.getElementById('updateMealType');
    let menuIdInput = document.getElementById('updateMenuId');
    let menuTypeDropdown = document.getElementById('updateMenuTypeDropdown');
    let menuItemsContainer = document.getElementById('update-menu-items');
    let openingTimeInput = document.getElementById('updateMenuOtime');
    let closingTimeInput = document.getElementById('updateMenuCtime');
    let dateInput = document.getElementById('updateMenuDate');
    let mealTitle = document.getElementById('updateMealTitle');

    if (!modal || !mealTypeInput || !mealTitle) {
        console.error("Modal elements not found!");
        return;
    }

    // Assign values to inputs
    menuIdInput.value = menuid;
    mealTypeInput.value = mealtype;
    menuTypeDropdown.value = menutype;
    dateInput.value = menudate;

    // Set Opening and Closing Time values
    openingTimeInput.value = menuotime;
    closingTimeInput.value = menuctime;

    // Clear and Populate Menu Items
    menuItemsContainer.innerHTML = "";
    let items = menuitems.split(',');

    items.forEach(item => {
        let parts = item.split('-'); // Split "itemname-price"
        let menuItem = parts[0].trim(); // Extract item name
        let menuPrice = parts.length > 1 ? parts[1].trim() : ""; // Extract price (if available)

        let div = document.createElement('div');
        div.classList.add('menu-item-row');
        div.innerHTML = `
            <input type="text" name="menu_item[]" value="${menuItem}" required>
            <input type="number" name="menu_price[]" value="${menuPrice}" placeholder="Price" required>
            <button type="button" onclick="this.parentElement.remove()">➖</button>
        `;
        menuItemsContainer.appendChild(div);
    });

    modal.style.display = 'block'; // Show the modal
}


function closeUpdateModal() {
    let modal = document.getElementById('updateMealModal');
    if (modal) {
        modal.style.display = 'none'; // Hide the modal
    }
}

function addUpdateMenuItem() {
    const container = document.getElementById('update-menu-items');
    const div = document.createElement('div');
    div.classList.add('menu-item-row');
    div.innerHTML = `
        <input type="text" name="menu_item[]" placeholder="Menu Item" required>
        <input type="number" name="menu_price[]" placeholder="Price" required>
        <button type="button" onclick="this.parentElement.remove()">➖</button>
    `;
    container.appendChild(div);
}


    </script>
</head>
<body>
    <aside class="sidebar">
        <h2 class="logo">EazyMenu</h2>
        <nav>
            <ul>
                <li><a href="../mdashboard.php">📊 Dashboard</a></li>
                <li><a href="../profile/mprofile.php">⚙️ Mess Details</a></li>
                <li><a href="" class="active">🍽️ Today's Menu</a></li>
                <li><a href="hmenu.php">📜 Menu History</a></li>            
                <li><a href="../logout.php" class="logout">🔒 Logout</a></li>
            </ul>
        </nav>
    </aside>
    <main class="content">
        <header>
            <h2 style="margin-left:30px; color: #d3416a;">Welcome <b style="color: #696969;"><?php echo $rowM['mofname'].' '.$rowM['molname']; ?> !!</b></h2>
        </header>

        <section class="quick-actions">
            <h4>Quick Actions</h4>

            <?php if ($messType == 'veg' || $messType == 'both'): ?>
                <button onclick="openModal('veg')">➕ Add Veg Meal</button>
            <?php endif; ?>
            <?php if ($messType == 'nonveg' || $messType == 'both'): ?>
                <button onclick="openModal('nonveg')">➕ Add Non-Veg Meal</button>
            <?php endif; ?>

             </section>

        <!-- Display Menu Cards -->
    <!-- Veg Menu Cards -->
<div class="menu-container">
    <?php if ($messType == 'veg' || $messType == 'both'): ?>
        <?php foreach (["Breakfast", "Lunch", "Dinner"] as $meal): ?>
            <div class="menu-card">
                <h3>Veg <?php echo $meal; ?></h3>
                <?php if ($vegMenu[$meal]): ?>
                    <p><strong>Items:</strong></p>
                    <ul class="menu-list">
                        <?php 
                        $items = explode(',', $vegMenu[$meal]['veg_menuitemprice']);
                        foreach ($items as $item): 
                        ?>
                            <li><?php echo trim($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p><strong>Opening:</strong> <?php echo $vegMenu[$meal]['menuotime']; ?></p>
                    <p><strong>Closing:</strong> <?php echo $vegMenu[$meal]['menuctime']; ?></p>
                    
                    <!-- Edit and Delete Icons -->
                    <div class="menu-actions">
                        <a href="#" class="edit-icon" onclick="openUpdateModal(
                            '<?php echo $vegMenu[$meal]['menuid']; ?>',
                            '<?php echo $meal; ?>',
                            '<?php echo $vegMenu[$meal]['veg_menuitemprice']; ?>',
                            '<?php echo $vegMenu[$meal]['menuotime']; ?>',
                            '<?php echo $vegMenu[$meal]['menuctime']; ?>',
                            '<?php echo $vegMenu[$meal]['menudate']; ?>',
                            'veg'
                        )">✏️</a>
                        <a href="deletemenu.php?menuid=<?php echo $vegMenu[$meal]['menuid']; ?>&mtype=veg" onclick="return confirm('Are you sure you want to delete this menu?');" class="delete-icon">🗑️</a>
                    </div>

                <?php else: ?>
                    <p class="no-menu">Not Added</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Non-Veg Menu Cards -->
<div class="menu-container">
    <?php if ($messType == 'nonveg' || $messType == 'both'): ?>
        <?php foreach (["Breakfast", "Lunch", "Dinner"] as $meal): ?>
            <div class="menu-card">
                <h3>Non-Veg <?php echo $meal; ?></h3>
                <?php if ($nonVegMenu[$meal]): ?>
                    <p><strong>Items:</strong></p>
                    <ul class="menu-list">
                        <?php 
                        $items = explode(',', $nonVegMenu[$meal]['nonveg_menuitemprice']);
                        foreach ($items as $item): 
                        ?>
                            <li><?php echo trim($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p><strong>Opening:</strong> <?php echo $nonVegMenu[$meal]['menuotime']; ?></p>
                    <p><strong>Closing:</strong> <?php echo $nonVegMenu[$meal]['menuctime']; ?></p>
                    
                    <!-- Edit and Delete Icons -->
                    <div class="menu-actions">
                          <a href="#" class="edit-icon" onclick="openUpdateModal(
                            '<?php echo $nonVegMenu[$meal]['menuid']; ?>',
                            '<?php echo $meal; ?>',
                            '<?php echo $nonVegMenu[$meal]['nonveg_menuitemprice']; ?>',
                            '<?php echo $nonVegMenu[$meal]['menuotime']; ?>',
                            '<?php echo $nonVegMenu[$meal]['menuctime']; ?>',
                            '<?php echo $nonVegMenu[$meal]['menudate']; ?>',
                            'nonVeg'
                        )">✏️</a>
                        <a href="deletemenu.php?menuid=<?php echo $nonVegMenu[$meal]['menuid']; ?>&mtype=nonveg" onclick="return confirm('Are you sure you want to delete this menu?');" class="delete-icon">🗑️</a>
                    </div>

                <?php else: ?>
                    <p class="no-menu">Not Added</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>



    </main>
    
    <div id="mealModal" class="modal">
        <div class="modal-content">
            <span onclick="closeModal()" style="float:right; cursor:pointer;">&times;</span>
            <h2 id="mealTitle">Add Meal</h2>
            <form action="addmenu.php" method="POST">
                <input type="hidden" name="mId" value="<?php echo $mId; ?>">
                <input type="hidden" id="mealType" name="mealType" value="">
                <div class="form-group">
                    <label>Menu Type:</label>
                    <select name="menutype">
                        <option>Breakfast</option>
                        <option>Lunch</option>
                        <option>Dinner</option>
                    </select>
                </div>
                <div class="form-group">
                    <div id="menu-items">
                        <div class="menu-item-row">
                            <input type="text" name="menu_item[]" placeholder="Menu Item" required>
                            <input type="number" name="menu_price[]" placeholder="Price" required>
                            <button type="button" onclick="addMenuItem()">➕</button>
                        </div>
                    </div>
                </div>
                <div class="form-group time-group">
                    <label>Opening Time:</label>
                    <input type="time" name="menuotime" required>
                    <label>Closing Time:</label>
                    <input type="time" name="menuctime" required>
                </div>
                <div class="form-group">
                    <label>Date:</label>
                    <input type="date" name="menudate" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="updateMealModal" class="modal">
    <div class="modal-content">
        <span onclick="closeUpdateModal()" style="float:right; cursor:pointer;">&times;</span>
        <h2 id="updateMealTitle">Update Meal</h2>
        <form action="update.php" method="POST">
            <input type="hidden" name="menuId" id="updateMenuId">
            <input type="hidden" name="mId" value="<?php echo $mId; ?>">
            <input type="hidden" id="updateMealType" name="mealType">

            <div class="form-group">
                <label>Menu Type:</label>
                <select name="menutype" id="updateMenuTypeDropdown" readonly>
                    <option>Breakfast</option>
                    <option>Lunch</option>
                    <option>Dinner</option>
                </select>
            </div>

            <div class="form-group">
                <div id="update-menu-items"></div>
                <button type="button" onclick="addUpdateMenuItem()">➕ Add More</button>
            </div>

            <div class="form-group time-group">
                <label>Opening Time:</label>
                <input type="time" name="menuotime" id="updateMenuOtime" required>
                <label>Closing Time:</label>
                <input type="time" name="menuctime" id="updateMenuCtime" required>
            </div>

            <div class="form-group">
                <label>Date:</label>
                <input type="date" name="menudate" id="updateMenuDate" required readonly>
            </div>

            <div class="form-group">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>


</body>
</html>

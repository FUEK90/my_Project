<!DOCTYPE html>
<html lang="th"><!-- style="width: 750px;" -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="inventory_style.css" rel="stylesheet" type="text/css" />
    <link href="table_inv.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-...<your-integrity-hash>..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        .icon-large {
            font-size: 40px; /* ปรับขนาดตามที่ต้องการ */
        }

        .icon-large1 {
            padding: 2px;
            font-size: 20px; /* ปรับขนาดตามที่ต้องการ */
        }

        .edit-button {
            margin: 10px;
        }
        
        .navbar {
            background-color: #89a0fa;
        }

        .menu li {
        display: flex; /* Use flexbox for alignment */
        }

        .menu li i { /* Adjust the icon class if needed */
        font-size: 24px; /* Increase the icon size */
        margin-right: 10px; /* Space between icon and text */
        }
        .table :where(thead,tfoot){
            color: black;
        }
    
        .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 140px; /* ความกว้างของปุ่ม */
        height: 1.5cm;
        border: none;
        border-radius: 50px;
        background-color: rgba(255, 255, 255, 0.955);
        color: #333;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s ease;
        margin-left: 60px;
        }
        @media only screen and (max-width: 520px){
            
    .btn{
        margin: auto;
        background-color: #89a0fa;
        }
        }
    </style>

    <title>คลัง</title>
</head>

<body>
    <div class="main-container">
        <div class="button-container">
            <div>
                <button class="btn glass"><a href="index.php">
                        <i class="material-symbols-outlined icon-large mr-2">home</i>
                    </a></button>
                <div class="la"><b>หน้าหลัก</b></div>
            </div>
            <div>
                <button class="btn glass"><a href="add_inventory.php">
                        <i class="material-symbols-outlined icon-large mr-2">assignment_add</i>
                    </a></button>
                <div class="la"><b>เพิ่มคลัง</b></div>
            </div>
            <div>
                <button class="btn glass"><a href="inventory.php">
                        <i class="material-symbols-outlined icon-large mr-2">inventory_2</i>
                    </a></button>
                <div class="la"><b>คลัง</b></div>
            </div>
            <div>
                <button class="btn glass"><a href="minus.php">
                        <i class="material-symbols-outlined icon-large mr-2">monitor_weight_loss</i>
                    </a></button>
                <div class="la"><b>เบิกสินค้า</b></div>
            </div>
            <div>
                <button class="btn glass"><a href="history.php">
                        <i class="material-symbols-outlined icon-large mr-2">history</i>
                    </a></button>
                <div class="la2"><b>ประวัติการเข้า-ออก</b></div>
            </div>
        </div>
        
    </div>
<div class="main-container1">
    <div class="navbar bg-base-100">
  <div class="navbar-start">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-10 w-10"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h7" />
        </svg>
      </div>
      <div class="container" onclick="myFunction(this)">
      <ul
    tabindex="0"
    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
    <li><a href="index.php"><i class="fas fa-home"></i> หน้าหลัก</a></li><hr>
    <li><a href="add_inventory.php"><i class="fas fa-plus"></i> เพิ่มคลัง</a></li><hr>
    <li><a href="inventory.php"><i class="fas fa-box"></i> คลังสินค้า</a></li><hr>
    <li><a href="minus.php"><i class="fas fa-minus"></i> เบิกสินค้า</a></li><hr>
    <li><a href="history.php"><i class="fas fa-history"></i> ประวัติการเข้า-ออก</a></li>
</ul></div>

    </div>
  </div>
  <div class="navbar-center">
    <a class="btn btn-ghost text-4xl">คลัง</a>
  </div>
  <div class="navbar-end">
    
  </div>
</div>
</div>  
    <?php
    // เชื่อมต่อฐานข้อมูล
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "inventory_db";

    // สร้างการเชื่อมต่อ
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // รับค่า type และ search จาก dropdown และ input
    $type_filter = isset($_GET['type']) ? $_GET['type'] : '';
    $search_query = isset($_GET['search']) ? $_GET['search'] : '';

    // สร้าง SQL Query ตามค่า filter และ search
$sql = "SELECT * FROM inventory WHERE 1=1";
if ($type_filter) {
    $type_filter = $conn->real_escape_string($type_filter);
    $sql .= " AND type = '$type_filter'";
}
if ($search_query) {
    $search_query = $conn->real_escape_string($search_query);
    $sql .= " AND product_name LIKE '%$search_query%'";
}

// เพิ่มการจัดเรียงน้ำหนักจากมากไปน้อย
$sql .= " ORDER BY weight DESC"; // เพิ่มบรรทัดนี้
$result = $conn->query($sql);

    ?>

    <div class="wrapper">
        <!-- ช่องค้นหาและ Dropdown -->
        <div class="search-box">
            <form action="" method="GET">
                <input type="text" name="search" class="input input-bordered w-24 md:w-auto" placeholder="ค้นหา" value="<?php echo htmlspecialchars($search_query); ?>">
                <select name="type" class="search-select">
                    <option value="">เลือกประเภท</option>
                    <option value="ใหญ่" <?php if ($type_filter == 'ใหญ่') echo 'selected'; ?>>ใหญ่</option>
                    <option value="ตกไซด์" <?php if ($type_filter == 'ตกไซด์') echo 'selected'; ?>>ตกไซด์</option>
                    <option value="เล็ก" <?php if ($type_filter == 'เล็ก') echo 'selected'; ?>>เล็ก</option>
                </select>
                <button type="submit" class="search-button">
                    <i class="material-symbols-outlined icon-large1">search</i>
                </button>
            </form>
        </div>

        <button onclick="deleteSelected()" class="delete-button">ลบที่เลือก</button>
        <button onclick="EditSelected()" class="edit-button">แก้ไขที่เลือก</button>
    </div>
    
    <script>
        // Function to handle the delete button click
        function deleteSelected() {
            var selectedIds = [];
            var checkboxes = document.querySelectorAll("input[type='checkbox']:checked");
            checkboxes.forEach(function (checkbox) {
                selectedIds.push(checkbox.value);
            });
            if (selectedIds.length > 0) {
                if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบรายการที่เลือก?")) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'delete_inventory.php';

                    selectedIds.forEach(function (id) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'delete_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            } else {
                alert("กรุณาเลือกสินค้าเพื่อทำการลบก่อน.");
            }
        }
    </script>

<script>
    // Function to handle the edit button click
    function EditSelected() {
        var selectedIds = [];
        var checkboxes = document.querySelectorAll("input[type='checkbox']:checked");
        checkboxes.forEach(function (checkbox) {
            selectedIds.push(checkbox.value);
        });
        if (selectedIds.length > 0) {
            // สร้างฟอร์มเพื่อส่งข้อมูลไปยังหน้าที่ใช้ในการแก้ไข
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'edit_inventory.php'; // เปลี่ยนไปยังหน้าที่ต้องการ

            selectedIds.forEach(function (id) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'edit_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            // เพิ่มข้อมูลราคากับน้ำหนักที่ต้องการแก้ไข
            var priceInput = document.createElement('input');
            priceInput.type = 'hidden';
            priceInput.name = 'price'; // เปลี่ยนตามที่คุณต้องการ
            priceInput.value = prompt("กรุณากรอกราคาใหม่:");

            var weightInput = document.createElement('input');
            weightInput.type = 'hidden';
            weightInput.name = 'weight'; // เปลี่ยนตามที่คุณต้องการ
            weightInput.value = prompt("กรุณากรอกน้ำหนักใหม่:");

            form.appendChild(priceInput);
            form.appendChild(weightInput);

            document.body.appendChild(form);
            form.submit();
        } else {
            alert("กรุณาเลือกสินค้าเพื่อทำการแก้ไขก่อน.");
        }
    }
</script>


<script>
    function highlightSearchTerm() {
        const searchQuery = new URLSearchParams(window.location.search).get('search');
        if (searchQuery) {
            const regex = new RegExp(searchQuery, 'gi');
            const elements = document.querySelectorAll('td:nth-child(3)'); // เลือกเฉพาะคอลัมน์ชื่อสินค้า

            elements.forEach(el => {
                if (el.textContent.match(regex)) {
                    el.innerHTML = el.textContent.replace(regex, match => `<span class="highlight">${match}</span>`);
                }
            });
        }
    }

    // Call the function when the page loads
    document.addEventListener('DOMContentLoaded', highlightSearchTerm);
</script>


    <div class="te">
        <table class="table">
            <!-- head -->
            <thead>
                <tr style="font-size:20px;">
                    <th><center>เลือก</center></th> <!-- เพิ่มคอลัมน์สำหรับ Checkbox -->
                    <th><center>ลำดับ</center></th>
                    <th>ชื่อสินค้า</th>
                    <th>ประเภท</th>
                    <th>น้ำหนัก</th>
                    <th>หน่วย</th>
                    <th>ราคา</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // ตรวจสอบว่ามีข้อมูลหรือไม่
                if ($result->num_rows > 0) {
                    // แสดงข้อมูล
                    $row_number = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr style='font-size:16px;'>";
                        echo "<td><center><input type='checkbox' name='delete_ids[]' value='" . $row['id'] . "'></center></td>"; // เพิ่ม Checkbox
                        echo "<th style='text-align: center;'>" . $row_number . "</th>";
                        echo "<td>" . $row['product_name'] . "</td>";
                        echo "<td>" . $row['type'] . "</td>";
                        echo "<td>" . $row['weight'] . "</td>";
                        echo "<td>" . $row['unit'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "</tr>";
                        $row_number++;
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align: center;'>ไม่มีข้อมูล</td></tr>";
                }

                // ปิดการเชื่อมต่อฐานข้อมูล
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

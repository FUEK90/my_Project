<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="minus.css" rel="stylesheet" type="text/css" />
    <link href="dropdown_inventory.css" rel="stylesheet" type="text/css" />
    <link href="table_inv.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-...<your-integrity-hash>..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .icon-large {
            font-size: 50px;
        }

        .icon-large1 {
            padding: 2px;
            font-size: 20px;
        }

        .deletedata-button {
            margin-left: 20px;
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        

        @media only screen and (min-width: 520px) {
            .fa {
                display: none;
            }
            
        
        }


        .column {
            display: flex; /* ใช้ Flexbox */
            flex-direction: column; /* ตั้งค่าให้เป็นแนวตั้ง */
            margin-top: 5px; /* เพิ่มระยะห่างด้านบน */
        }

        .highlight {
            background-color: yellow;
        }
    </style>

    <title>เบิกสินค้า</title>
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
// เพิ่มการกรองน้ำหนักที่ไม่เป็น 0
$sql .= " AND weight > 0";
// เพิ่มการเรียงลำดับน้ำหนักจากมากไปน้อย
$sql .= " ORDER BY weight DESC";

$result = $conn->query($sql);
?>

        <div class="column1">
        <div class="wrapper">
        <!-- ช่องค้นหาและ Dropdown -->
        <div class="search-box">
            <form action="" method="GET">
                <input type="text" name="search" class="search-input" placeholder="ค้นหาชื่อสินค้า..." value="<?php echo htmlspecialchars($search_query); ?>">
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

        
        <form id="deduction-form" action="show_deduction_form.php" method="POST">
        <button type="submit" class="deletedata-button">เบิกที่เลือก</button>
        
    </div>
        <div class="wrapper">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr style="font-size:20px;">
                        <th><center>เลือก</center></th>
                        <th><center>ลำดับ</center></th>
                        <th>ชื่อสินค้า</th>
                        <!-- <th>วันที่</th> -->
                        <th>ประเภท</th>
                        <th>น้ำหนัก</th>
                        <th>หน่วย</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $row_number = 1;
                        while ($row = $result->fetch_assoc()) {
                            $date_time = new DateTime($row['date_time']);
                            $formatted_date = $date_time->format('d/m/Y');
                            echo "<tr>";
                            echo "<td><center><input type='checkbox' name='selected_ids[]' value='" . $row['id'] . "'></center></td>";
                            echo "<th style='text-align: center;'>" . $row_number . "</th>";
                            echo "<td>" . $row['product_name'] . "</td>";
                            // echo "<td>" . $formatted_date . "</td>";
                            echo "<td>" . $row['type'] . "</td>";
                            echo "<td>" . $row['weight'] . "</td>";
                            echo "<td>" . $row['unit'] . "</td>";
                            echo "</tr>";
                            $row_number++;
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align: center;'>ไม่มีข้อมูล</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        </form>
        </div>

    <div class="box">
        <div class="icon-bar">
            <a href="index.php"><i class="fa fa-home"></i></a>
            <a href="add_inventory.php"><i class="fa fa-add"></i></a>
            <a href="inventory.php"><i class="fa fa-box"></i></a>
            <a class="active" href="minus.php"><i class="fa fa-minus"></i></a>
            <a href="history.php"><i class="fa fa-history"></i></a>
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

        // สร้าง SQL Query
        $sql = "SELECT * FROM inventory WHERE 1=1";
        if ($type_filter) {
            $type_filter = $conn->real_escape_string($type_filter);
            $sql .= " AND type = '$type_filter'";
        }
        if ($search_query) {
            $search_query = $conn->real_escape_string($search_query);
            $sql .= " AND product_name LIKE '%$search_query%'";
        }
        $sql .= " AND weight > 0"; // กรองน้ำหนักที่ไม่เป็น 0
        $sql .= " ORDER BY weight DESC"; // เรียงลำดับน้ำหนักจากมากไปน้อย

        $result = $conn->query($sql);
        ?>

        <div class="column">
        <div class="wrapper">
        <!-- ช่องค้นหาและ Dropdown -->
        <div class="search-box">
            <form action="" method="GET">
                <input type="text" name="search" class="search-input" placeholder="ค้นหาชื่อสินค้า..." value="<?php echo htmlspecialchars($search_query); ?>">
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

        
        <form id="deduction-form" action="show_deduction_form.php" method="POST">
        <button type="submit" class="deletedata-button">เบิกที่เลือก</button>
        
    </div>
        <div class="wrapper">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr style="font-size:20px;">
                        <th><center>เลือก</center></th>
                        <th><center>ลำดับ</center></th>
                        <th>ชื่อสินค้า</th>
                        <!-- <th>วันที่</th> -->
                        <th>ประเภท</th>
                        <th>น้ำหนัก</th>
                        <th>หน่วย</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $row_number = 1;
                        while ($row = $result->fetch_assoc()) {
                            $date_time = new DateTime($row['date_time']);
                            $formatted_date = $date_time->format('d/m/Y');
                            echo "<tr>";
                            echo "<td><center><input type='checkbox' name='selected_ids[]' value='" . $row['id'] . "'></center></td>";
                            echo "<th style='text-align: center;'>" . $row_number . "</th>";
                            echo "<td>" . $row['product_name'] . "</td>";
                            // echo "<td>" . $formatted_date . "</td>";
                            echo "<td>" . $row['type'] . "</td>";
                            echo "<td>" . $row['weight'] . "</td>";
                            echo "<td>" . $row['unit'] . "</td>";
                            echo "</tr>";
                            $row_number++;
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align: center;'>ไม่มีข้อมูล</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        </form>
        </div>
    </div>

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

        // เรียกฟังก์ชันเมื่อโหลดหน้าเว็บ
        document.addEventListener('DOMContentLoaded', highlightSearchTerm);
    </script>

</body>
</html>

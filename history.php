<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="his_style.css" rel="stylesheet" type="text/css" />
    <link href="dropdown_style.css" rel="stylesheet" type="text/css" />
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
        
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        
        .dropdown-content a:hover {background-color: #f1f1f1}
        
        .show {display: block;}
        
        .status-in {color: green;}
        .status-out {color: red;}
    </style>
    <title>ประวัติการเข้า-ออก</title>
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

<div class="column1"><div class="wrapper1">
    <!-- <div class="real_time" id="currentDateTime">วันที่: </div> -->
    <div class="dropdown">
        <button onclick="toggleDropdown()" class="dropbtn"> เลือกสถานะ
            <i class="material-symbols-outlined">arrow_drop_down</i>
        </button>
        <div id="myDropdown" class="dropdown-content">
            <a href="?type=addition">เข้า</a>
            <hr>
            <a href="?type=0">ออก</a>
            <hr>
            <a href="history.php">ทั้งหมด</a>
        </div>
    </div>

    <div class="search-date">
        <label for="searchDate">ค้นหาตามวันที่:</label>
        <input type="date" id="searchDate" name="searchDate">
        <button class="btn1" type="button" onclick="searchByDate()">ค้นหา</button>
    </div>

    <button type="button" class="bill-button" onclick="submitForm()">สร้างใบเสร็จ</button>
</div>

<form id="receiptForm" action="generate_receipt.php" method="POST">
    <input type="hidden" id="items" name="items">

    <div class="wrapper">
        <table>
            <thead>
                <tr>
                    <th><center>เลือก</center></th>
                    <th>ลำดับ</th>
                    <th>ชื่อสินค้า</th>
                    <th>น้ำหนัก</th>
                    <th>สถานะ</th>
                    <th>ประเภท</th>
                    <th>วันที่เวลา</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "inventory_db";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("การเชื่อมต่อผิดพลาด: " . $conn->connect_error);
                }

                $type_filter = isset($_GET['type']) ? $_GET['type'] : '';
                $date_filter = isset($_GET['date']) ? $_GET['date'] : '';

                $sql = "SELECT * FROM inventory_history";
                $where_clauses = [];

                if ($type_filter !== '' && $type_filter !== 'ทั้งหมด') {
                    $type_filter = $conn->real_escape_string($type_filter);
                    $where_clauses[] = "change_type = '$type_filter'";
                }

                if ($date_filter !== '') {
                    $date_filter = $conn->real_escape_string($date_filter);
                    $where_clauses[] = "DATE(change_date) = '$date_filter'";
                }

                if (count($where_clauses) > 0) {
                    $sql .= " WHERE " . implode(" AND ", $where_clauses);
                }
                $sql .= " ORDER BY change_date DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row_number = 1;
                    while ($row = $result->fetch_assoc()) {
                        $change_date = new DateTime($row['change_date']);
                        $formatted_date = $change_date->format('d/m/Y H:i:s');
                        $change_type = htmlspecialchars($row['change_type']);
                        $type = htmlspecialchars($row['type']);

                        if ($change_type == '0') {
                            $change_type_display = 'ออก';
                            $status_class = 'status-out';
                        } elseif ($change_type == 'addition') {
                            $change_type_display = 'เข้า';
                            $status_class = 'status-in';
                        } else {
                            $change_type_display = 'ไม่ทราบ';
                            $status_class = '';
                        }

                        echo "<tr>";
                        echo "<td><center><input type='checkbox' name='selected_ids[]' value='" . $row['id'] . "' data-status='" . $change_type . "'></center></td>";
                        echo "<td>" . $row_number . "</td>";
                        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['change_amount']) . "</td>";
                        echo "<td class='" . $status_class . "'>" . $change_type_display . "</td>";
                        echo "<td>" . $type . "</td>";
                        echo "<td>" . $formatted_date . "</td>";
                        echo "</tr>";
                        $row_number++;
                    }
                } else {
                    echo "<tr><td colspan='7'>ไม่มีข้อมูล</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</form></div>

<div class="box">
        <div class="icon-bar">
            <a href="index.php"><i class="fa fa-home"></i></a>
            <a  href="add_inventory.php"><i class="fa fa-add"></i></a>
            <a  href="inventory.php"><i class="fa fa-box"></i></a>
            <a  href="minus.php"><i class="fa fa-minus"></i></a>
            <a class="active" href="history.php"><i class="fa fa-history"></i></a>
        </div>
<div class="column">
<div class="wrapper1">
    <!-- <div class="real_time" id="currentDateTime">วันที่: </div> -->
    <div class="dropdown">
        <button onclick="toggleDropdown()" class="dropbtn"> เลือกสถานะ
            <i class="material-symbols-outlined">arrow_drop_down</i>
        </button>
        <div id="myDropdown" class="dropdown-content">
            <a href="?type=addition">เข้า</a>
            <hr>
            <a href="?type=0">ออก</a>
            <hr>
            <a href="history.php">ทั้งหมด</a>
        </div>
    </div>

    <div class="search-date">
        <label for="searchDate">ค้นหาตามวันที่:</label>
        <input type="date" id="searchDate" name="searchDate">
        <button class="btn1" type="button" onclick="searchByDate()">ค้นหา</button>
    </div>

    <button type="button" class="bill-button" onclick="submitForm()">สร้างใบเสร็จ</button>
</div>

<form id="receiptForm" action="generate_receipt.php" method="POST">
    <input type="hidden" id="items" name="items">

    <div class="wrapper">
        <table>
            <thead>
                <tr>
                    <th><center>เลือก</center></th>
                    <th>ลำดับ</th>
                    <th>ชื่อสินค้า</th>
                    <th>น้ำหนัก</th>
                    <th>สถานะ</th>
                    <th>ประเภท</th>
                    <th>วันที่เวลา</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "inventory_db";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("การเชื่อมต่อผิดพลาด: " . $conn->connect_error);
                }

                $type_filter = isset($_GET['type']) ? $_GET['type'] : '';
                $date_filter = isset($_GET['date']) ? $_GET['date'] : '';

                $sql = "SELECT * FROM inventory_history";
                $where_clauses = [];

                if ($type_filter !== '' && $type_filter !== 'ทั้งหมด') {
                    $type_filter = $conn->real_escape_string($type_filter);
                    $where_clauses[] = "change_type = '$type_filter'";
                }

                if ($date_filter !== '') {
                    $date_filter = $conn->real_escape_string($date_filter);
                    $where_clauses[] = "DATE(change_date) = '$date_filter'";
                }

                if (count($where_clauses) > 0) {
                    $sql .= " WHERE " . implode(" AND ", $where_clauses);
                }
                $sql .= " ORDER BY change_date DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row_number = 1;
                    while ($row = $result->fetch_assoc()) {
                        $change_date = new DateTime($row['change_date']);
                        $formatted_date = $change_date->format('d/m/Y H:i:s');
                        $change_type = htmlspecialchars($row['change_type']);
                        $type = htmlspecialchars($row['type']);

                        if ($change_type == '0') {
                            $change_type_display = 'ออก';
                            $status_class = 'status-out';
                        } elseif ($change_type == 'addition') {
                            $change_type_display = 'เข้า';
                            $status_class = 'status-in';
                        } else {
                            $change_type_display = 'ไม่ทราบ';
                            $status_class = '';
                        }

                        echo "<tr>";
                        echo "<td><center><input type='checkbox' name='selected_ids[]' value='" . $row['id'] . "' data-status='" . $change_type . "'></center></td>";
                        echo "<td>" . $row_number . "</td>";
                        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['change_amount']) . "</td>";
                        echo "<td class='" . $status_class . "'>" . $change_type_display . "</td>";
                        echo "<td>" . $type . "</td>";
                        echo "<td>" . $formatted_date . "</td>";
                        echo "</tr>";
                        $row_number++;
                    }
                } else {
                    echo "<tr><td colspan='7'>ไม่มีข้อมูล</td></tr>";
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
    function updateDateTime() {
        const now = new Date();
        const formattedDate = now.toLocaleDateString('th-TH') + ' ' + now.toLocaleTimeString('th-TH');
        document.getElementById('currentDateTime').innerText = 'วันที่: ' + formattedDate;
    }

    function toggleDropdown() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    function submitForm() {
        const selectedItems = [];
        const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]:checked');
        let hasOutStatus = false;
        let hasInStatus = false;

        checkboxes.forEach((checkbox) => {
            const row = checkbox.closest('tr');
            const status = checkbox.getAttribute('data-status');
            
            if (status === '0') {
                hasOutStatus = true;
                const item = {
                    id: checkbox.value,
                    productName: row.cells[2].textContent,
                    weight: row.cells[3].textContent,
                    status: row.cells[4].textContent,
                    date: row.cells[6].textContent
                };
                selectedItems.push(item);
            } else if (status === 'addition') {
                hasInStatus = true;
            }
        });

        if (hasInStatus && hasOutStatus) {
            alert('กรุณาเลือกเฉพาะรายการที่มีสถานะ "ออก" เท่านั้น');
            return;
        }

        if (!hasOutStatus) {
            alert('กรุณาเลือกอย่างน้อยหนึ่งรายการที่มีสถานะ "ออก"');
            return;
        }

        document.getElementById('items').value = JSON.stringify(selectedItems);
        document.getElementById("receiptForm").submit();
    }

    function searchByDate() {
        const searchDate = document.getElementById('searchDate').value;
        if (searchDate) {
            window.location.href = `history.php?date=${searchDate}`;
        } else {
            alert('กรุณาเลือกวันที่เพื่อค้นหา');
        }
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>

</body>
</html>

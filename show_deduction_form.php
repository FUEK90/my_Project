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

// รับค่า id ที่ถูกเลือก
$selected_ids = isset($_POST['selected_ids']) ? $_POST['selected_ids'] : array();

if (empty($selected_ids)) {
    echo "กรุณาเลือกสินค้าก่อนที่จะทำการเบิก";
    exit;
}

// สร้าง SQL Query เพื่อนำข้อมูลของสินค้าที่ถูกเลือกมาแสดง
$ids_placeholder = implode(',', array_fill(0, count($selected_ids), '?'));
$sql = "SELECT * FROM inventory WHERE id IN ($ids_placeholder)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('i', count($selected_ids)), ...$selected_ids);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dropdown_inventory.css" rel="stylesheet" type="text/css" />
    <link href="table_inventory.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"integrity="sha512-...<your-integrity-hash>..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        .icon-large {
            font-size: 50px;
        }

        .icon-large1 {
            padding: 2px;
            font-size: 20px;
        }

        .deduct-button {
            margin: 20px;
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-button {
            margin-left: 20px;
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .large-text {
            font-size: 20px;
            text-align: center; /* จัดกลางข้อความ */
            padding: 20px;
        }

        .large-text1 {
            font-size: 20px;
            padding-bottom: 20px;
        }
        
        .input-field {
            margin-bottom: 20px;
            padding: 10px;
            width: 50%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .remove-button {
            padding: 5px 10px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center; /* จัดกลางในทุกคอลัมน์ */
        }

        .deductions1{
            width: 100px;
            border: solid;
            border-radius: 5px;
            border-width: 1px;
            text-align: center;
        }

        /*คำสั่งควบคุมระยะรูป*/
        .image-container {
            margin-bottom: 10px;
            padding: 15px;
        }

        /*คำสั่งรูป*/
        .image-container img {
            width: 120px;
            height: auto;
            margin: 0;
            border-radius: 8px;
            border: 4px solid #101b815e;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
    </style>

    <title>กรอกน้ำหนักที่ต้องการตัดออก</title>
</head>

<body>
    <h1 class="large-text">กรอกน้ำหนักที่ต้องการตัดออก</h1><br>

    <form action="deduct_process.php" method="POST">
    <div class="image-container">
                <img src="f2.png" alt="logo">
                <h2 class="text-2xl font-semibold text-gray-700 mt-3">แพปลาจุ้ยเน้ย</h2>
            </div>

        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อสินค้า</th>
                    <th>น้ำหนักที่มี</th>
                    <th>ประเภท</th>
                    <th>หน่วย</th>
                    <th>ราคา</th>
                    <th>น้ำหนักที่ต้องการตัดออก</th>
                    <th>ยกเลิก</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 1; // เริ่มต้นที่ 1
                while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $index++; ?></td> <!-- แสดงหมายเลขลำดับ -->
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['weight']); ?></td>
                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                    <td><?php echo htmlspecialchars($row['unit']); ?> (หน่วย)</td>
                    <td><?php echo htmlspecialchars(number_format($row['price'], 2)); ?> บาท</td> <!-- แสดงราคาสินค้า -->
                    <td><input type="number" class="deductions1" name="deductions[<?php echo $row['id']; ?>]" min="0" max="<?php echo htmlspecialchars($row['weight']); ?>" required></td>
                    <td><button type="button" class="remove-button" onclick="removeRow(this)">ยกเลิก</button></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" class="deduct-button">ทำการเบิก</button>
        <a href="minus.php" class="cancel-button">ยกเลิก</a>
    </form>

    <script>
    function removeRow(button) {
        // หาตาราง row ที่ปุ่มนี้อยู่
        var row = button.closest('tr');
        // ลบ row
        row.remove();
        // อัพเดตหมายเลขลำดับใหม่
        updateRowNumbers();
    }

    function updateRowNumbers() {
        var rows = document.querySelectorAll('table tbody tr');
        rows.forEach(function(row, index) {
            row.querySelector('td:first-child').textContent = index + 1;
        });
    }
</script>

</body>

</html>

<?php
// ปิดการเชื่อมต่อ
$conn->close();
?>

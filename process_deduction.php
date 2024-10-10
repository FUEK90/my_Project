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
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
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
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กรอกน้ำหนักที่ต้องการตัดออก</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        input[type="number"] {
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h1>กรอกน้ำหนักที่ต้องการตัดออก</h1>
    <form action="minus.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>ชื่อสินค้า</th>
                    <th>น้ำหนักที่มี</th>
                    <th>น้ำหนักที่ต้องการตัดออก</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['weight']); ?> (<?php echo htmlspecialchars($row['unit']); ?>)</td>
                    <td><input type="number" name="deductions[<?php echo $row['id']; ?>]" min="0" max="<?php echo htmlspecialchars($row['weight']); ?>" step="0.01" required></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <input type="submit" value="ทำการเบิก">
    </form>
</body>
</html>

<?php
// ปิดการเชื่อมต่อ
$conn->close();
?>

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

// ตรวจสอบว่าได้รับข้อมูล POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $selected_ids = isset($_POST['selected_ids']) ? $_POST['selected_ids'] : [];
    $weights = isset($_POST['weights']) ? $_POST['weights'] : [];

    foreach ($selected_ids as $id) {
        // ตรวจสอบว่าไอดีที่เลือกมีน้ำหนักที่กรอกไว้หรือไม่
        if (isset($weights[$id]) && $weights[$id] > 0) {
            $weight = $conn->real_escape_string($weights[$id]);

            // ค้นหาข้อมูลน้ำหนักปัจจุบันของสินค้านั้น
            $sql = "SELECT weight FROM inventory WHERE id = $id";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if ($row) {
                $current_weight = $row['weight'];

                // คำนวณน้ำหนักที่เหลือหลังการเบิก
                $new_weight = $current_weight - $weight;

                // อัพเดตน้ำหนักในฐานข้อมูล
                $update_sql = "UPDATE inventory SET weight = $new_weight WHERE id = $id";
                $conn->query($update_sql);

                // เพิ่มข้อมูลการเบิกสินค้าไปยังตารางประวัติ
                $insert_sql = "INSERT INTO history (product_id, weight_deducted, date_time) VALUES ($id, $weight, NOW())";
                $conn->query($insert_sql);
            }
        }
    }

    // เปลี่ยนเส้นทางไปยังหน้าประวัติ
    header("Location: history.php");
    exit();
}

// ปิดการเชื่อมต่อ
$conn->close();
?>

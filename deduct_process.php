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

// รับข้อมูลจากฟอร์ม
$deductions = isset($_POST['deductions']) ? $_POST['deductions'] : [];

// กำหนดประเภทการเปลี่ยนแปลง
$change_type = 'ออก'; // บันทึกการออก

foreach ($deductions as $id => $deduction) {
    $deduction = floatval($deduction); // แปลงค่าเป็นตัวเลข

    if ($deduction > 0) {
        // ค้นหาข้อมูลสินค้าจาก ID
        $sql = "SELECT * FROM inventory WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $product_name = $row['product_name'];
            $current_amount = $row['weight'];
            $type = $row['type'];

            // คำนวณน้ำหนักใหม่
            $new_amount = $current_amount - $deduction;

            // ลดจำนวนสินค้าในตาราง inventory
            if ($new_amount < 0) {
                $new_amount = 0;
            }
            
            $sql_update_product = "UPDATE inventory SET weight = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update_product);
            $stmt->bind_param('di', $new_amount, $id);
            $stmt->execute();

            // บันทึกการออกในตาราง inventory_history
            $sql_insert_history = "INSERT INTO inventory_history (product_name, change_amount, change_type, type, change_date) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql_insert_history);
            $stmt->bind_param('sdis', $product_name, $deduction, $change_type, $type);
            $stmt->execute();
        }
    }
}

// ปิดการเชื่อมต่อ
$conn->close();

// รีไดเรกต์ไปยังหน้าประวัติ
header('Location: history.php');
exit();
?>

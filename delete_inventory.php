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

// ตรวจสอบว่ามีการส่งข้อมูล delete_ids หรือไม่
if (isset($_POST['delete_ids'])) {
    // รับค่าจาก checkbox
    $delete_ids = $_POST['delete_ids'];

    // สร้าง SQL Query สำหรับลบข้อมูล
    $ids = implode(',', array_map('intval', $delete_ids)); // สร้างลิสต์ของ id
    $sql = "DELETE FROM inventory WHERE id IN ($ids)";

    if ($conn->query($sql) === TRUE) {
        echo "ลบข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . $conn->error;
    }
} else {
    echo "ไม่มีรายการที่เลือกสำหรับการลบ.";
}

// ปิดการเชื่อมต่อ
$conn->close();

// เปลี่ยนเส้นทางกลับไปที่หน้า inventory
header("Location: inventory.php");
exit();
?>

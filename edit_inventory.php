<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $edit_ids = $_POST['edit_ids']; // รับข้อมูล ID ที่เลือก
    $new_price = $_POST['price']; // รับราคาใหม่
    $new_weight = $_POST['weight']; // รับน้ำหนักใหม่

    foreach ($edit_ids as $id) {
        $id = $conn->real_escape_string($id);
        $new_price = $conn->real_escape_string($new_price);
        $new_weight = $conn->real_escape_string($new_weight);
        
        // สร้างคำสั่ง SQL เพื่ออัปเดตข้อมูล
        $sql = "UPDATE inventory SET price = '$new_price', weight = '$new_weight' WHERE id = '$id'";
        $conn->query($sql);
    }

    // เปลี่ยนเส้นทางไปยังหน้าคลังหลังจากการแก้ไขเสร็จ
    header("Location: inventory.php");
    exit();
}

$conn->close();
?>

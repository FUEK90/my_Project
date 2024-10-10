<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost"; // หรือที่อยู่ IP ของฐานข้อมูล
$username = "root"; // ชื่อผู้ใช้ของฐานข้อมูล
$password = ""; // รหัสผ่านของฐานข้อมูล
$dbname = "inventory_db"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจาก POST
$data = isset($_POST['data']) ? $_POST['data'] : '';

// แปลง JSON เป็น Array
$dataArray = json_decode($data, true);

// เตรียมการบันทึกข้อมูล
foreach ($dataArray as $item) {
    $index = $item['index'];
    $productName = trim($conn->real_escape_string($item['productName']));
    
    if (empty($productName)) {
        echo "ผิดพลาด : กรุณากรอกชื่อสินค้า.";
        continue; // ข้ามไปยังรายการถัดไป
    }

    $type = $conn->real_escape_string($item['type']);
    $weight = floatval($item['weight']);
    $unit = $conn->real_escape_string($item['unit']);
    $price = floatval($item['price']);
    
    // ตรวจสอบว่ามีข้อมูลสินค้านี้ในฐานข้อมูลหรือไม่
    $sql = "SELECT id, price FROM inventory WHERE product_name = '$productName' AND type = '$type' AND unit = '$unit'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // ข้อมูลมีอยู่แล้ว
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $existingPrice = $row['price'];

        // กรณีที่มีการกรอกราคาใหม่
        if ($price > 0) {
            // อัพเดทน้ำหนักและราคา
            $sql = "UPDATE inventory SET weight = weight + $weight, price = $price WHERE id = $id";
        } else {
            // ใช้ราคาเดิม แต่ต้องอัพเดทน้ำหนัก
            $sql = "UPDATE inventory SET weight = weight + $weight WHERE id = $id";
        }

        if ($conn->query($sql) === TRUE) {
            // บันทึกประวัติการเพิ่มสินค้า
            $sql = "INSERT INTO inventory_history (product_name, type, change_amount, change_type) VALUES ('$productName', '$type', $weight, 'addition')";
            if ($conn->query($sql) !== TRUE) {
                echo "Error logging history: " . $conn->error;
            }
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        // ข้อมูลไม่มี ให้เพิ่มข้อมูลใหม่
        // หากไม่ระบุราคาหรือราคาน้อยกว่า 0 ให้ใช้ค่า 0
        if ($price <= 0) {
            $price = 0;
        }
        $sql = "INSERT INTO inventory (product_name, type, weight, unit, price) VALUES ('$productName', '$type', $weight, '$unit', $price)";
        if ($conn->query($sql) === TRUE) {
            $productId = $conn->insert_id;
            
            // บันทึกประวัติการเพิ่มสินค้า
            $sql = "INSERT INTO inventory_history (product_name, type, change_amount, change_type) VALUES ('$productName', '$type', $weight, 'addition')";
            if ($conn->query($sql) !== TRUE) {
                echo "Error logging history: " . $conn->error;
            }
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// ปิดการเชื่อมต่อ
$conn->close();
?>

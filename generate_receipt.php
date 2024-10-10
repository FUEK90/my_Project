<?php
// ฟังก์ชันแปลงตัวเลขเป็นภาษาไทย
function convertNumberToThai($number) {
    $thaiNumbers = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
    $units = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];

    if ($number == 0) return 'ศูนย์';

    $numberString = '';
    $count = 0;

    while ($number > 0) {
        $remainder = $number % 10;
        if ($remainder > 0) {
            $numberString = $thaiNumbers[$remainder] . ($count > 0 ? $units[$count] : '') . $numberString;
        }
        $number = intval($number / 10);
        $count++;
    }

    return $numberString;
}

// รับข้อมูลรายการที่เลือก
if (isset($_POST['items']) && is_array($_POST['items'])) {
    $items = array_map('json_decode', $_POST['items']);
} else {
    $items = [];
}

$totalAmount = 0;
foreach ($items as $item) {
    $totalAmount += $item->price * $item->weight; // ใช้สูตรคำนวณที่เหมาะสม
}

$date = date('d/m/Y');
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบเสร็จ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .receipt-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
        }
        .payment-methods {
            display: flex;
            gap: 10px;
        }
        .payment-methods label {
            display: flex;
            align-items: center;
        }
        .payment-methods input {
            margin-right: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        @media print {
            .actions {
                display: none;
            }
            .input-field {
                border: none;
                background: none;
            }
            .footer button {
                display: none;
            }
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .signature-col {
            flex: 1;
            padding: 10px;
        }
        .signature-pad {
            border: 1px solid #d3d3d3;
            width: 100%;
            height: 150px;
            background-color: #fff;
        }
        .signature-col button {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="header">
        <h2>บิลออกตั๋วเก็บเงิน</h2>
        <div class="payment-methods">
            <label>
                <input type="checkbox" name="payment_method" value="credit"> เงินเชื่อ
            </label>
            <label>
                <input type="checkbox" name="payment_method" value="cash"> เงินสด
            </label>
        </div>
    </div>

    <h3>ชื่อเรือ</h3>
    <input type="text" name="ship_name" class="input-field" placeholder="กรุณากรอกชื่อเรือ" required><br><br>

    <h3>ชื่อแม่ค้า</h3>
    <input type="text" name="merchant_name" class="input-field" placeholder="กรุณากรอกชื่อแม่ค้า" required><br><br>

    <p>วันที่: <?php echo $date; ?></p>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // ตรวจสอบว่ามีการเลือก checkbox หรือไม่
        if (isset($_POST['selected_ids']) && !empty($_POST['selected_ids'])) {
            $selected_ids = $_POST['selected_ids'];

            // เชื่อมต่อฐานข้อมูล
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "inventory_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("การเชื่อมต่อผิดพลาด: " . $conn->connect_error);
            }

            // สร้าง placeholder สำหรับ ID
            $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));

            // เตรียม SQL statement
            $stmt = $conn->prepare("
                SELECT ih.*, i.price, i.unit FROM inventory_history ih 
                JOIN inventory i ON ih.product_name = i.product_name AND ih.type = i.type
                WHERE ih.id IN ($placeholders)
            ");
            $stmt->bind_param(str_repeat('i', count($selected_ids)), ...$selected_ids);

            $stmt->execute();
            $result = $stmt->get_result();

            // ใช้ array เพื่อเก็บข้อมูลที่ไม่ซ้ำ
            $unique_items = [];
            while ($row = $result->fetch_assoc()) {
                $key = $row['product_name'] . '|' . $row['type']; // ใช้คีย์รวมชื่อสินค้าและประเภท
                if (!isset($unique_items[$key])) {
                    $unique_items[$key] = $row;
                } else {
                    $unique_items[$key]['change_amount'] += $row['change_amount']; // เพิ่มน้ำหนัก
                }
            }

            $stmt->close();
            $conn->close();

            // แสดงรายการที่เลือก
if (!empty($unique_items)) {
    echo "<h1>รายการที่เลือก</h1>";
    echo "<table>";
    echo "<thead>
            <tr>
                <th>ลำดับ</th>
                <th>ชื่อสินค้า</th>
                <th>น้ำหนัก</th>
                <th>ราคา</th>
                <th>ประเภท</th>
                <th>รวม</th>
            </tr>
          </thead>
          <tbody>";

    $row_number = 1;
    foreach ($unique_items as $item) {
        $weight = htmlspecialchars($item['change_amount']);
        $price = htmlspecialchars($item['price']); // ใช้ราคาจากตาราง inventory
        $total = $weight * $price;
        $unit = htmlspecialchars($item['unit']);
        $type = htmlspecialchars($item['type']);
        $totalAmount += $total;

        echo "<tr>";
        echo "<td>" . $row_number . "</td>";
        echo "<td>" . htmlspecialchars($item['product_name']) . "</td>";
        echo "<td>" . number_format($weight, 2) . " $unit" . "</td>";
        echo "<td>" . number_format($price, 2) . " บาท</td>";
        echo "<td>" . $type . "</td>";
        echo "<td>" . number_format($total, 2) . " บาท</td>";
        echo "</tr>";
        $row_number++;
    }
    echo "<tr>
    <td colspan='5' style='text-align: center;'><strong>ยอดรวมทั้งหมด: " . convertNumberToThai($totalAmount) . "บาทถ้วน</strong></td>
    <td><strong>" . number_format($totalAmount, 2) . " บาท</strong></td>
    </tr>";
    echo "</tbody></table>";
} else {
                echo "ไม่มีข้อมูล";
            }

        } else {
            echo "ไม่พบรายการที่เลือก";
        }
    } else {
        echo "วิธีการร้องขอไม่ถูกต้อง";
    }
    ?>

    <!-- ลายเซ็นต์ -->
    <div class="footer">
        <div class="signature-section">
            <div class="signature-col">
                <h3>ลายเซ็นต์ผู้ขาย</h3>
                <canvas id="seller-signature-pad" class="signature-pad"></canvas><br>
                <button onclick="clearSignature('seller')">ล้างลายเซ็นต์ผู้ขาย</button>
                <!-- <button onclick="saveSignature('seller')">บันทึกลายเซ็นต์ผู้ขาย</button> -->
            </div>
            <div class="signature-col">
                <h3>ลายเซ็นต์ผู้ซื้อ</h3>
                <canvas id="buyer-signature-pad" class="signature-pad"></canvas><br>
                <button onclick="clearSignature('buyer')">ล้างลายเซ็นต์ผู้ซื้อ</button>
                <!-- <button onclick="saveSignature('buyer')">บันทึกลายเซ็นต์ผู้ซื้อ</button> -->
            </div>
        </div>
    </div>

    <div class="actions"><br><br><br>
        <button onclick="window.print()">พิมพ์</button>
        <button onclick="window.location.href='history.php'">ยกเลิก</button>
    </div>
</div>

<script>
    // ฟังก์ชันสำหรับล้างลายเซ็น
    function clearSignature(type) {
        var canvas = document.getElementById(type + '-signature-pad');
        var context = canvas.getContext('2d');
        context.clearRect(0, 0, canvas.width, canvas.height);
    }

    // ฟังก์ชันสำหรับบันทึกลายเซ็น (ยังไม่ได้เปิดใช้งาน)
    function saveSignature(type) {
        var canvas = document.getElementById(type + '-signature-pad');
        var dataURL = canvas.toDataURL('image/png');
        var link = document.createElement('a');
        link.href = dataURL;
        link.download = type + '-signature.png';
        link.click();
    }

    // ฟังก์ชันการตั้งค่า signature pad
    function setupSignaturePad(canvasId) {
        var canvas = document.getElementById(canvasId);
        var context = canvas.getContext('2d');
        var drawing = false;

        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;

        canvas.addEventListener('mousedown', function(e) {
            drawing = true;
            context.beginPath();
            context.moveTo(e.offsetX, e.offsetY);
        });

        canvas.addEventListener('mousemove', function(e) {
            if (drawing) {
                context.lineTo(e.offsetX, e.offsetY);
                context.stroke();
            }
        });

        canvas.addEventListener('mouseup', function() {
            drawing = false;
        });

        // ตั้งค่าลักษณะของลายเซ็น
        context.strokeStyle = '#000';
        context.lineWidth = 2;
        context.lineCap = 'round';
    }

    // ตั้งค่า signature pads
    setupSignaturePad('seller-signature-pad');
    setupSignaturePad('buyer-signature-pad');
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="add_in.css" rel="stylesheet" type="text/css" />
    <link href="table_addinv.css" rel="stylesheet" type="text/css" />
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


    </style>
    
    <title>เพิ่มสินค้า</title>
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
                <div class="la"><b>เพิ่มสินค้า</b></div>
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
            <!-- <div class="nav-wrapper">
                <div class="logo"></div>
                <ul class="menu" id="myMenu">
                    <li><a href="index.php"><div class="la">หน้าหลัก</div></a></li>
                    <li><a href="add_inventory.php"><div class="la">เพิ่มสินค้า</div></a></li>
                    <li><a href="inventory.php"><div class="la">คลัง</div></a></li>
                    <li><a href="minus.php"><div class="la">เบิกสินค้า</div></a></li>
                    <li><a href="history.php"><div class="la">ประวัติ</div></a></li>
                </ul> 
                <div class="ham-menu" onclick="toggleHam(this)">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>
        </div> -->
        <div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="index.php">หน้าหลัก</a>
  <a href="add_inventory.php">เพิ่มสินค้า</a>
  <a href="inventory.php">คลัง</a>
  <a href="minus.php">เบิกสินค้า</a>
  <a href="history.php">ประวัติ</a>
</div>


<span style="font-size:30px;cursor:pointer" onclick="openNav()" class="mu">&#9776; เพิ่มคลัง</span>

    </div> 


       
 

    <div class="wrapper">
        <!-- ช่องค้นหา -->
        <!-- <div class="search-box">
            <input type="text" class="search-input" placeholder="ค้นหา...">
            <button class="search-button">
                <i class="material-symbols-outlined icon-large1">search</i>
            </button>
        </div> -->

        <!-- เวลา -->
        <div class="real_time" id="currentDateTime">วันที่: </div>

        <!-- ปุ่มเพิ่มคลัง -->
        <a href="javascript:void(0);" class="btn1 glass" id="addProductBtn">เพิ่มสินค้า</a>
    </div>
    <div class="te">
    <table class="table" id="inventoryTable">
        <!-- head -->
        <thead>
          <tr style="font-size:20px;">
            <th style="text-align: center;">ลำดับ</th>
            <th>ชื่อสินค้า</th>
            <th>ประเภท</th>
            <th>น้ำหนัก</th>
            <th>หน่วย</th>
            <th>ราคา</th>
          </tr>
        </thead>
        <tbody>
          <!-- row 1 -->
          <tr>
            <th style="text-align: center;">1</th>
            <td><input type="text" class="name_product" name="firstname" value=""></td>
            <td><div class="dropdown">
                                <select>
                                    <option value="ใหญ่">ใหญ่</option>
                                    <option value="ตกไซด์">ตกไซด์</option>
                                    <option value="เล็ก">เล็ก</option>
                                </select>
                            </div></td>
            <td><input type="number" class="number-input" value="0" step="0.1"></td>
            <td><div class="dropdown">
                                <select>
                                    <option value="ขีด">ขีด</option>
                                    <option value="กก.">กก.</option>
                                </select>
                            </div></td>
            <td><input type="number" class="number-input" value="0" step="0.1"></td>
          </tr>
          
        </tbody>
    </table>
    </div>

    <!-- ปุ่มยืนยัน -->
    <button class="confirm-btn" id="confirmBtn"><a href = "inventory.php">ยืนยัน</a></button>

    <script>
        
        function updateDateTime() {
            const now = new Date();
            const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
            const options1 = {hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const formattedDate = now.toLocaleString('th-TH', options); // ใช้รูปแบบวันที่และเวลาตามที่ต้องการ
            const formattedDate1 = now.toLocaleString('th-TH', options1); // ใช้รูปแบบวันที่และเวลาตามที่ต้องการ
            document.getElementById('currentDateTime').textContent = 'วันที่: ' + formattedDate + '\n' + 'เวลา: ' + formattedDate1;
        }

        // เรียกใช้ฟังก์ชันเพื่ออัพเดตวันที่ทันทีที่โหลดหน้า
        updateDateTime();

        // อัพเดตวันที่ทุกวินาที
        setInterval(updateDateTime, 1000);

        // ฟังก์ชันเพิ่มแถวใหม่
        document.getElementById('addProductBtn').addEventListener('click', function() {
            const table = document.getElementById('inventoryTable').getElementsByTagName('tbody')[0];
            const rowCount = table.rows.length + 1;
            const newRow = table.insertRow();
            
            newRow.innerHTML = `
                <th style="text-align: center;">${rowCount}</th>
                <td><input type="text" name="firstname" value=""></td>
                <td><div class="dropdown">
                    <select>
                        <option value="ใหญ่">ใหญ่</option>
                        <option value="ตกไซด์">ตกไซด์</option>
                        <option value="เล็ก">เล็ก</option>
                    </select>
                </div></td>
                <td><input type="number" class="number-input" value="0" step="0.1"></td>
                <td><div class="dropdown">
                    <select>
                        <option value="ขีด">ขีด</option>
                        <option value="กก.">กก.</option>
                    </select>
                </div></td>
                <td><input type="number" class="number-input" value="0" step="0.1"></td>
            `;
        });

        // ฟังก์ชันยืนยัน
        document.getElementById('confirmBtn').addEventListener('click', function() {
    const table = document.getElementById('inventoryTable');
    const rows = table.getElementsByTagName('tbody')[0].rows;
    let data = [];

    for (let i = 0; i < rows.length; i++) {
        let row = rows[i];
        let rowData = {
            index: row.cells[0].textContent,
            productName: row.cells[1].getElementsByTagName('input')[0].value,
            type: row.cells[2].getElementsByTagName('select')[0].value,
            weight: row.cells[3].getElementsByTagName('input')[0].value,
            unit: row.cells[4].getElementsByTagName('select')[0].value,
            price: row.cells[5].getElementsByTagName('input')[0].value
        };
        data.push(rowData);
    }

    // ส่งข้อมูลไปยังเซิร์ฟเวอร์
    fetch('save_inventory.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'data': JSON.stringify(data)
        })
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
        alert(result);
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

    </script>
    <!-- <script>
        function toggleHam(x) {
            x.classList.toggle("change");
            let myMenu =document.getElementById('myMenu');
            if(myMenu.className === 'menu'){
                myMenu.className += 'menu-active'
            }else{
                myMenu.className = 'menu'
            }
        }
    </script> -->
    <script>
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
</script>
</body>
</html>

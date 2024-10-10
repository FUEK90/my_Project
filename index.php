<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="bg1.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-...<your-integrity-hash>..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <style>
       .icon-large {
            font-size: 30px; /* ปรับขนาดตามที่ต้องการ */
        }
        
    </style>
    
    <title>หน้าหลัก</title>
</head>
<body>
    <header>
        <p><span id="current-date"></span> | เวลา: <span id="current-time"></span></p>
    </header>
    
    <div class="bg">
        <div class="main-container">
            <div class="image-container">
                <img src="f2.png" alt="logo">
                <h2 class="text-2xl font-semibold text-gray-700 mt-3">แพปลาจุ้ยเน้ย</h2>
            </div>
            <div class="button-container">
                <div>
                    <button class="btn"><a href="index.php">
                        <i class="material-symbols-outlined icon-large mr-2">home</i>
                    </a></button>
                    <div class="la"><b>หน้าหลัก</b></div>
                </div>
                <div>
                    <button class="btn"><a href="add_inventory.php">
                        <i class="material-symbols-outlined icon-large mr-2">assignment_add</i>
                    </a></button>
                    <div class="la"><b>เพิ่มคลัง</b></div>
                </div>
                <div>
                    <button class="btn"><a href="inventory.php">
                        <i class="material-symbols-outlined icon-large mr-2">inventory_2</i>
                    </a></button>
                    <div class="la"><b>คลัง</b></div>
                </div>
                <div>
                    <button class="btn"><a href="minus.php">
                        <i class="material-symbols-outlined icon-large mr-2">monitor_weight_loss</i>
                    </a></button>
                    <div class="la"><b>เบิกสินค้า</b></div>
                </div>
                <div>
                    <button class="btn"><a href="history.php">
                        <i class="material-symbols-outlined icon-large mr-2">history</i>
                    </a></button>
                    <div class="la2"><b>ประวัติการเข้า-ออก</b></div>
                </div>
            </div> 
        </div> 
    </div> 
    
    <footer>
        <p>&copy; 2024 - แพปลาจุ้ยเน้ย</p>
    </footer>

    <script>
        function updateTime() {
            const now = new Date();
            const dateString = now.toLocaleDateString('th-TH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                weekday: 'long'
            });
            const timeString = now.toLocaleTimeString();
            document.getElementById('current-date').textContent = dateString;
            document.getElementById('current-time').textContent = timeString;
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>
</html>

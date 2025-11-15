<?php
// File: admin/index.php
include 'session_check.php';

// Ambil statistik
$query_total = "SELECT COUNT(*) as total FROM db_hotel";
$result_total = mysqli_query($conn, $query_total);
$total_hotel = mysqli_fetch_assoc($result_total)['total'];

$query_bintang5 = "SELECT COUNT(*) as total FROM db_hotel WHERE star = 5";
$result_bintang5 = mysqli_query($conn, $query_bintang5);
$total_bintang5 = mysqli_fetch_assoc($result_bintang5)['total'];

$query_avg_rating = "SELECT AVG(rating) as avg_rating FROM db_hotel";
$result_avg = mysqli_query($conn, $query_avg_rating);
$avg_rating = round(mysqli_fetch_assoc($result_avg)['avg_rating'], 1);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Hotel Malang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar h1 {
            font-size: 24px;
        }
        
        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border: 2px solid white;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-logout:hover {
            background: white;
            color: #667eea;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .stat-icon {
            font-size: 48px;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }
        
        .stat-icon.blue { background: #e3f2fd; }
        .stat-icon.green { background: #e8f5e9; }
        .stat-icon.orange { background: #fff3e0; }
        
        .stat-info h3 {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .stat-info .number {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        
        .actions {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .actions h2 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .action-btn.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .action-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .action-btn.secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .action-btn.secondary:hover {
            background: #e0e0e0;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>🏨 Dashboard Admin</h1>
        <div class="user-info">
            <span>Halo, <strong><?php echo $_SESSION['admin_nama']; ?></strong></span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">🏨</div>
                <div class="stat-info">
                    <h3>Total Hotel</h3>
                    <div class="number"><?php echo $total_hotel; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon green">⭐</div>
                <div class="stat-info">
                    <h3>Hotel Bintang 5</h3>
                    <div class="number"><?php echo $total_bintang5; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange">📊</div>
                <div class="stat-info">
                    <h3>Rata-rata Rating</h3>
                    <div class="number"><?php echo $avg_rating; ?></div>
                </div>
            </div>
        </div>
        
        <div class="actions">
            <h2>🎯 Menu Utama</h2>
            <div class="action-buttons">
                <a href="hotel_add.php" class="action-btn primary">
                    ➕ Tambah Hotel Baru
                </a>
                <a href="hotel_list.php" class="action-btn primary">
                    📋 Kelola Data Hotel
                </a>
                <a href="../index.php" target="_blank" class="action-btn secondary">
                    🌐 Lihat Website
                </a>
            </div>
        </div>
    </div>
</body>
</html>
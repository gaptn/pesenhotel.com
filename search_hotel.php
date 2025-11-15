<?php
// File: search_hotel.php
include 'config/database.php';
include 'functions/binary_search.php';
include 'functions/helpers.php';

// Ambil semua data hotel
$query = "SELECT * FROM db_hotel";
$result = mysqli_query($conn, $query);
$hotels = mysqli_fetch_all($result, MYSQLI_ASSOC);

$search_result = null;
$search_type = '';
$search_query = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['q'])) {
    $search_query = clean_input($_GET['q']);
    $search_type = isset($_GET['type']) ? clean_input($_GET['type']) : 'name';
    
    if ($search_type == 'id' && is_numeric($search_query)) {
        // Pencarian by ID menggunakan Binary Search
        $search_result = binarySearchById($hotels, (int)$search_query);
    } else {
        // Pencarian by Nama menggunakan Binary Search
        $search_result = binarySearchByName($hotels, $search_query);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Hotel - Binary Search</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
        }
        
        .navbar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .search-section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .search-section h1 {
            margin-bottom: 10px;
            color: #333;
        }
        
        .search-section p {
            color: #666;
            margin-bottom: 30px;
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 150px 1fr auto;
            gap: 15px;
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-search {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .result-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .search-stats {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-label {
            color: #666;
            font-size: 13px;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }
        
        .hotel-found {
            background: #d4edda;
            border-left: 4px solid #10b981;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .hotel-found h3 {
            color: #10b981;
            margin-bottom: 15px;
        }
        
        .hotel-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }
        
        .hotel-details h2 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .detail-label {
            color: #666;
        }
        
        .detail-value {
            font-weight: 600;
            color: #333;
        }
        
        .hotel-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-secondary {
            background: #10b981;
            color: white;
        }
        
        .not-found {
            background: #f8d7da;
            border-left: 4px solid #ef4444;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
        }
        
        .not-found h3 {
            color: #ef4444;
            margin-bottom: 10px;
        }
        
        .steps-visualization {
            margin-top: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .steps-visualization h4 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .step {
            padding: 15px;
            background: white;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .step-header {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .step-info {
            font-size: 14px;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="container">
            <a href="index.php" style="font-size: 24px; font-weight: bold;">🏨 Hotel Malang</a>
            <div>
                <a href="index.php" style="margin-right: 20px;">Home</a>
                <a href="demo_algorithm.php">Demo Algoritma</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="search-section">
            <h1>🔍 Pencarian Hotel dengan Binary Search</h1>
            <p>Cari hotel berdasarkan ID atau Nama menggunakan algoritma Binary Search yang efisien</p>
            
            <form method="GET" class="search-form">
                <select name="type" class="form-control">
                    <option value="name" <?php echo $search_type == 'name' ? 'selected' : ''; ?>>
                        Nama Hotel
                    </option>
                    <option value="id" <?php echo $search_type == 'id' ? 'selected' : ''; ?>>
                        ID Hotel
                    </option>
                </select>
                
                <input type="text" 
                       name="q" 
                       class="form-control" 
                       placeholder="Masukkan kata kunci..." 
                       value="<?php echo htmlspecialchars($search_query); ?>"
                       required>
                
                <button type="submit" class="btn-search">
                    🔍 Cari
                </button>
            </form>
        </div>
        
        <?php if ($search_result): ?>
            <div class="result-box">
                <!-- SEARCH STATISTICS -->
                <div class="search-stats">
                    <div class="stat-item">
                        <div class="stat-label">Algoritma</div>
                        <div class="stat-value" style="font-size: 16px;">Binary Search</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Kompleksitas</div>
                        <div class="stat-value" style="font-size: 20px;">O(log n)</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Jumlah Iterasi</div>
                        <div class="stat-value"><?php echo $search_result['iterations']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Status</div>
                        <div class="stat-value" style="font-size: 20px; color: <?php echo $search_result['found'] ? '#10b981' : '#ef4444'; ?>">
                            <?php echo $search_result['found'] ? '✓' : '✗'; ?>
                        </div>
                    </div>
                </div>
                
                <?php if ($search_result['found']): ?>
                    <!-- HOTEL FOUND -->
                    <div class="hotel-found">
                        <h3>✅ Hotel Ditemukan!</h3>
                        <div class="hotel-details">
                            <h2><?php echo $search_result['data']['hotel']; ?></h2>
                            
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="detail-label">ID Hotel</span>
                                    <span class="detail-value">#<?php echo $search_result['data']['id']; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Bintang</span>
                                    <span class="detail-value">
                                        <?php echo displayStars($search_result['data']['star']); ?>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Rating</span>
                                    <span class="detail-value">⭐ <?php echo $search_result['data']['rating']; ?>/5</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Lokasi</span>
                                    <span class="detail-value">📍 <?php echo $search_result['data']['location']; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Harga</span>
                                    <span class="detail-value" style="color: #10b981;">
                                        <?php echo formatRupiah($search_result['data']['price']); ?>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Status</span>
                                    <span class="detail-value">
                                        <?php echo $search_result['data']['booked'] ? '❌ Penuh' : '✅ Tersedia'; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="hotel-actions">
                                <a href="detail.php?id=<?php echo $search_result['data']['id']; ?>" 
                                   class="btn btn-primary">
                                    📄 Lihat Detail Lengkap
                                </a>
                                <a href="booking.php?id=<?php echo $search_result['data']['id']; ?>" 
                                   class="btn btn-secondary">
                                    🛏️ Booking Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- NOT FOUND -->
                    <div class="not-found">
                        <h3>❌ Hotel Tidak Ditemukan</h3>
                        <p style="color: #666;">
                            Tidak ada hotel dengan <?php echo $search_type == 'id' ? 'ID' : 'nama'; ?> 
                            "<strong><?php echo htmlspecialchars($search_query); ?></strong>"
                        </p>
                        <a href="search_hotel.php" class="btn btn-primary" style="margin-top: 15px;">
                            Cari Lagi
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- VISUALIZATION OF SEARCH STEPS -->
                <div class="steps-visualization">
                    <h4>📊 Visualisasi Langkah Pencarian:</h4>
                    <?php foreach ($search_result['steps'] as $step): ?>
                        <div class="step">
                            <div class="step-header">
                                Iterasi #<?php echo $step['iteration']; ?>
                            </div>
                            <div class="step-info">
                                <?php if ($search_type == 'id'): ?>
                                    Left: <?php echo $step['left']; ?> | 
                                    Mid: <strong><?php echo $step['mid']; ?></strong> (ID: <?php echo $step['mid_id']; ?>) | 
                                    Right: <?php echo $step['right']; ?>
                                <?php else: ?>
                                    Left: <?php echo $step['left']; ?> | 
                                    Mid: <strong><?php echo $step['mid']; ?></strong> (<?php echo $step['mid_name']; ?>) | 
                                    Right: <?php echo $step['right']; ?>
                                <?php endif; ?>
                                
                                <?php
                                if ($step['action'] == 'FOUND') {
                                    echo ' → <strong style="color: #10b981;">✓ DITEMUKAN!</strong>';
                                } elseif ($step['action'] == 'GO_LEFT') {
                                    echo ' → <span style="color: #3b82f6;">← Cari di sebelah KIRI</span>';
                                } else {
                                    echo ' → <span style="color: #f59e0b;">Cari di sebelah KANAN →</span>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
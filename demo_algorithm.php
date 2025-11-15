<?php
// File: demo_algorithm.php
include 'config/db.php';
include 'functions/binary_search.php';
include 'functions/bubble_sort.php';
include 'functions/helpers.php';

// Ambil semua data hotel
$query = "SELECT * FROM db_hotel";
$result = mysqli_query($conn, $query);
$hotels = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Demo Binary Search
$search_id = isset($_GET['search_id']) ? clean_input($_GET['search_id']) : 0;
$binary_result = null;
$linear_result = null;

if ($search_id > 0) {
    $binary_result = binarySearchById($hotels, $search_id);
    $linear_result = linearSearchById($hotels, $search_id);
}

// Demo Bubble Sort
$sort_by = isset($_GET['sort_by']) ? clean_input($_GET['sort_by']) : 'rating';
$bubble_result = bubbleSortHotels($hotels, $sort_by, 'DESC');
$comparison = compareSort($hotels, $sort_by, 'DESC');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Algoritma - Binary Search & Bubble Sort</title>
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar a {
            color: white;
            text-decoration: none;
        }
        
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .section h2 {
            margin-bottom: 20px;
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        
        .demo-form {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .form-control {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .result-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .result-box h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }
        
        .info-card .label {
            color: #666;
            font-size: 13px;
            margin-bottom: 5px;
        }
        
        .info-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        
        .steps-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .steps-table th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        .steps-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .steps-table tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-found {
            background: #10b981;
            color: white;
        }
        
        .badge-left {
            background: #3b82f6;
            color: white;
        }
        
        .badge-right {
            background: #f59e0b;
            color: white;
        }
        
        .comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        
        .algo-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
        }
        
        .algo-card h4 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .algo-stat {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .hotel-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .hotel-mini-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .hotel-mini-card .name {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .hotel-mini-card .info {
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="container">
            <h1>🔬 Demo Algoritma</h1>
            <a href="index.php">← Kembali ke Beranda</a>
        </div>
    </div>
    
    <div class="container">
        <!-- BINARY SEARCH DEMO -->
        <div class="section">
            <h2>🔍 Demo Binary Search vs Linear Search</h2>
            <p style="color: #666; margin-bottom: 20px;">
                Binary Search mencari data dengan membagi array menjadi dua bagian secara berulang. 
                Kompleksitas: <strong>O(log n)</strong> - jauh lebih cepat dari Linear Search O(n).
            </p>
            
            <form method="GET" class="demo-form">
                <select name="search_id" class="form-control" required>
                    <option value="">Pilih Hotel untuk Dicari...</option>
                    <?php foreach ($hotels as $hotel): ?>
                        <option value="<?php echo $hotel['id']; ?>" 
                                <?php echo $search_id == $hotel['id'] ? 'selected' : ''; ?>>
                            ID: <?php echo $hotel['id']; ?> - <?php echo $hotel['hotel']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">🔍 Cari Hotel</button>
            </form>
            
            <?php if ($binary_result): ?>
                <div class="comparison">
                    <!-- BINARY SEARCH RESULT -->
                    <div class="algo-card" style="border-color: #10b981;">
                        <h4>📊 Binary Search</h4>
                        <div class="algo-stat">
                            <span>Status:</span>
                            <strong style="color: <?php echo $binary_result['found'] ? '#10b981' : '#ef4444'; ?>">
                                <?php echo $binary_result['found'] ? '✅ Ditemukan' : '❌ Tidak Ditemukan'; ?>
                            </strong>
                        </div>
                        <div class="algo-stat">
                            <span>Jumlah Iterasi:</span>
                            <strong><?php echo $binary_result['iterations']; ?>x</strong>
                        </div>
                        <div class="algo-stat">
                            <span>Kompleksitas:</span>
                            <strong>O(log n)</strong>
                        </div>
                        <div class="algo-stat">
                            <span>Waktu Eksekusi:</span>
                            <strong>~0.001ms</strong>
                        </div>
                    </div>
                    
                    <!-- LINEAR SEARCH RESULT -->
                    <div class="algo-card" style="border-color: #ef4444;">
                        <h4>📊 Linear Search (Pembanding)</h4>
                        <div class="algo-stat">
                            <span>Status:</span>
                            <strong style="color: <?php echo $linear_result['found'] ? '#10b981' : '#ef4444'; ?>">
                                <?php echo $linear_result['found'] ? '✅ Ditemukan' : '❌ Tidak Ditemukan'; ?>
                            </strong>
                        </div>
                        <div class="algo-stat">
                            <span>Jumlah Iterasi:</span>
                            <strong><?php echo $linear_result['iterations']; ?>x</strong>
                        </div>
                        <div class="algo-stat">
                            <span>Kompleksitas:</span>
                            <strong>O(n)</strong>
                        </div>
                        <div class="algo-stat">
                            <span>Perbandingan:</span>
                            <strong style="color: #10b981;">
                                <?php echo round(($linear_result['iterations'] / $binary_result['iterations']), 2); ?>x lebih lambat
                            </strong>
                        </div>
                    </div>
                </div>
                
                <!-- STEPS TABLE -->
                <?php if ($binary_result['found']): ?>
                    <div class="result-box" style="margin-top: 20px;">
                        <h3>🎯 Data Hotel Ditemukan:</h3>
                        <div style="padding: 15px; background: white; border-radius: 8px;">
                            <strong style="font-size: 18px;"><?php echo $binary_result['data']['hotel']; ?></strong><br>
                            <span style="color: #666;">
                                <?php echo displayStars($binary_result['data']['star']); ?> | 
                                Rating: <?php echo $binary_result['data']['rating']; ?>/5 | 
                                Harga: <?php echo formatRupiah($binary_result['data']['price']); ?>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <h3 style="margin-top: 30px; margin-bottom: 15px;">📝 Langkah-langkah Binary Search:</h3>
                <table class="steps-table">
                    <thead>
                        <tr>
                            <th>Iterasi</th>
                            <th>Left Index</th>
                            <th>Right Index</th>
                            <th>Mid Index</th>
                            <th>Mid ID</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($binary_result['steps'] as $step): ?>
                            <tr>
                                <td><?php echo $step['iteration']; ?></td>
                                <td><?php echo $step['left']; ?></td>
                                <td><?php echo $step['right']; ?></td>
                                <td><strong><?php echo $step['mid']; ?></strong></td>
                                <td><?php echo $step['mid_id']; ?></td>
                                <td>
                                    <?php
                                    if ($step['action'] == 'FOUND') {
                                        echo '<span class="badge badge-found">✓ DITEMUKAN</span>';
                                    } elseif ($step['action'] == 'GO_LEFT') {
                                        echo '<span class="badge badge-left">← Cari Kiri</span>';
                                    } else {
                                        echo '<span class="badge badge-right">Cari Kanan →</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- BUBBLE SORT DEMO -->
        <div class="section">
            <h2>📊 Demo Bubble Sort</h2>
            <p style="color: #666; margin-bottom: 20px;">
                Bubble Sort mengurutkan data dengan membandingkan elemen bersebelahan dan menukarnya jika urutannya salah.
                Kompleksitas: <strong>O(n²)</strong>
            </p>
            
            <form method="GET" class="demo-form">
                <?php if (isset($_GET['search_id'])): ?>
                    <input type="hidden" name="search_id" value="<?php echo $_GET['search_id']; ?>">
                <?php endif; ?>
                <select name="sort_by" class="form-control">
                    <option value="rating" <?php echo $sort_by == 'rating' ? 'selected' : ''; ?>>
                        Sort by Rating (Tertinggi)
                    </option>
                    <option value="price" <?php echo $sort_by == 'price' ? 'selected' : ''; ?>>
                        Sort by Harga (Termurah)
                    </option>
                    <option value="star" <?php echo $sort_by == 'star' ? 'selected' : ''; ?>>
                        Sort by Bintang (Tertinggi)
                    </option>
                </select>
                <button type="submit" class="btn btn-primary">🔄 Sort Data</button>
            </form>
            
            <div class="info-grid">
                <div class="info-card">
                    <div class="label">Total Hotel</div>
                    <div class="value"><?php echo count($hotels); ?></div>
                </div>
                <div class="info-card">
                    <div class="label">Jumlah Swap</div>
                    <div class="value"><?php echo $bubble_result['swap_count']; ?></div>
                </div>
                <div class="info-card">
                    <div class="label">Jumlah Perbandingan</div>
                    <div class="value"><?php echo $bubble_result['comparisons']; ?></div>
                </div>
                <div class="info-card">
                    <div class="label">Jumlah Pass</div>
                    <div class="value"><?php echo $bubble_result['passes']; ?></div>
                </div>
            </div>
            
            <div class="comparison">
                <div class="algo-card">
                    <h4>⏱️ Bubble Sort (Digunakan)</h4>
                    <div class="algo-stat">
                        <span>Kompleksitas:</span>
                        <strong>O(n²)</strong>
                    </div>
                    <div class="algo-stat">
                        <span>Waktu Eksekusi:</span>
                        <strong><?php echo number_format($comparison['bubble_sort']['time'] * 1000, 4); ?> ms</strong>
                    </div>
                    <div class="algo-stat">
                        <span>Jumlah Swap:</span>
                        <strong><?php echo $comparison['bubble_sort']['swaps']; ?></strong>
                    </div>
                </div>
                
                <div class="algo-card">
                    <h4>🚀 Quick Sort (Pembanding)</h4>
                    <div class="algo-stat">
                        <span>Kompleksitas:</span>
                        <strong>O(n log n)</strong>
                    </div>
                    <div class="algo-stat">
                        <span>Waktu Eksekusi:</span>
                        <strong><?php echo number_format($comparison['quick_sort']['time'] * 1000, 4); ?> ms</strong>
                    </div>
                    <div class="algo-stat">
                        <span>Lebih Cepat:</span>
                        <strong style="color: #10b981;"><?php echo $comparison['faster']; ?></strong>
                    </div>
                </div>
            </div>
            
            <h3 style="margin-top: 30px; margin-bottom: 15px;">📋 Hasil Sorting (Top 10):</h3>
            <div class="hotel-preview">
                <?php 
                $top_hotels = array_slice($bubble_result['data'], 0, 10);
                foreach ($top_hotels as $index => $hotel): 
                ?>
                    <div class="hotel-mini-card">
                        <div class="name">#<?php echo $index + 1; ?>. <?php echo $hotel['hotel']; ?></div>
                        <div class="info">
                            <?php echo displayStars($hotel['star']); ?><br>
                            Rating: <?php echo $hotel['rating']; ?>/5<br>
                            Harga: <?php echo formatRupiah($hotel['price']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
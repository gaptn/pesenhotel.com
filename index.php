<?php
// File: index.php
include 'config/db.php';
include 'functions/bubble_sort.php';

// Ambil filter dari user
$star_filter = isset($_GET['star']) ? clean_input($_GET['star']) : '';
$sort_by = isset($_GET['sort']) ? clean_input($_GET['sort']) : 'rating';
$sort_order = isset($_GET['order']) ? clean_input($_GET['order']) : 'DESC';
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';

// Query database
$query = "SELECT * FROM db_hotel WHERE 1=1";

if ($star_filter != '') {
    $query .= " AND star = '$star_filter'";
}

if ($search != '') {
    $query .= " AND hotel LIKE '%$search%'";
}

$result = mysqli_query($conn, $query);
$hotels = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Tentukan order berdasarkan pilihan sort
if ($sort_by == 'price') {
    $sort_order = 'ASC'; // Harga termurah ke termahal
} else {
    $sort_order = 'DESC'; // Rating tertinggi ke terendah
}

// Sorting menggunakan Bubble Sort
$sorted_result = bubbleSortHotels($hotels, $sort_by, $sort_order);
$hotels = $sorted_result['data'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pesenhotel.com - Temukan Hotel Terbaik di Kota Malang</title>
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
        
        /* NAVBAR */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar .logo {
            font-size: 28px;
            font-weight: bold;
        }
        
        .navbar nav a {
            color: white;
            text-decoration: none;
            margin-left: 30px;
            font-weight: 500;
            transition: opacity 0.3s;
        }
        
        .navbar nav a:hover {
            opacity: 0.8;
        }
        
        /* HERO SECTION */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.9;
        }
        
        /* SEARCH & FILTER */
        .search-section {
            background: white;
            max-width: 1000px;
            margin: -30px auto 30px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 15px;
            align-items: center;
        }
        
        .search-form .form-control {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            height: 44px;
        }
        
        .search-form .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .filter-group {
            display: flex;
            gap: 10px;
        }
        
        .filter-group select {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            height: 44px;
            min-width: 200px;
        }
        
        .filter-group select:hover {
            border-color: #667eea;
        }
        
        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-search {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1px 90px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            height: 44px;
        
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
        }
        
        /* CONTAINER */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* RESULTS INFO */
        .results-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0 20px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .results-info h2 {
            color: #333;
        }
        
       .sort-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
        }
        
        .sort-label {
            color: #666;
            font-size: 14px;
        }
        
        .sort-stats {
            color: #667eea;
            font-weight: 600;
            font-size: 13px;
        }
        
        /* SORTING INDICATOR */
        .sort-indicator {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .sort-indicator .icon {
            font-size: 24px;
            margin-right: 10px;
        }
        
    

        
        /* HOTEL GRID */
        .hotel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }
        
        .hotel-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
        }
        
        .hotel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        }
        
        .hotel-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            position: relative;
        }
        
        .hotel-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: white;
            color: #333;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .hotel-content {
            padding: 20px;
        }
        
        .hotel-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }
        
        .hotel-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .hotel-stars {
            font-size: 14px;
        }
        
        .hotel-rating {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .hotel-location {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .hotel-facilities {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .facility-tag {
            background: #f0f0f0;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #666;
        }
        
        .hotel-price {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 15px;
        }
        
        .hotel-price span {
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }
        
        .hotel-price.highlight {
            color: #667eea;
            background: #f0f4ff;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }
        
        .hotel-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            opacity: 0.9;
        }
        
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }
        
        /* NO RESULTS */
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            margin-bottom: 50px;
        }
        
        .no-results h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .no-results p {
            color: #666;
        }
        
        /* FOOTER */
        .footer {
            background: #2d3748;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .footer p {
            margin-bottom: 10px;
        }
        
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .filter-group {
                display: flex;;
                grid-template-columns: 1fr 1fr;
                width: 100%;
                padding: 10px 10px
            }
            
            .hero h1 {
                font-size: 32px;
            }
            
            .hotel-grid {
                grid-template-columns: 1fr;
            }
            
            .results-info {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .sort-info {
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <div class="navbar">
        <div class="container">
            <div class="logo">🏨 pesenhotel.com</div>
            <nav>
                <a href="index.php">Home</a>
                <a href="search_hotel.php">Search</a>
                <a href="demo_algorithm.php">Demo</a>
                <a href="admin/login.php">Admin</a>
            </nav>
        </div>
    </div>
    
    <!-- HERO SECTION -->
    <div class="hero">
        <h1>Temukan Hotel Terbaik di Malang</h1>
        <p>Cari dan booking hotel impian Anda dengan mudah</p>
    </div>
    
    <!-- SEARCH & FILTER -->
    <div class="container">
        <div class="search-section">
            <form method="GET" action="" class="search-form">
                <input type="text" name="search" class="form-control" 
                       placeholder="🔍 Cari nama hotel..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                
                <div class="filter-group">
                    <select name="sort" class="form-control">
                        <option value="rating" <?php echo $sort_by == 'rating' ? 'selected' : ''; ?>>⭐ Rating Tertinggi</option>
                        <option value="price" <?php echo $sort_by == 'price' ? 'selected' : ''; ?>>💰 Harga Terendah</option>
                        <option value="star" <?php echo $sort_by == 'star' ? 'selected' : ''; ?>>🌟 Kelas Bintang</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-search">Cari</button>
            </form>
        </div>
    </div>
    
    <!-- SORTING INDICATOR -->
    <?php if (count($hotels) > 0): ?>
        <div class="container">
            <div class="sort-indicator">
                <?php if ($sort_by == 'price'): ?>
                    <span class="icon">💰</span>
                    Diurutkan dari <strong>Harga Termurah</strong> 
                <?php elseif ($sort_by == 'star'): ?>
                    <span class="icon">🌟</span>
                    Diurutkan dari <strong>Bintang Terbanyak</strong>
                <?php else: ?>
                    <span class="icon">⭐</span>
                    Diurutkan dari <strong>Rating Tertinggi</strong> 
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- RESULTS INFO -->
    <div class="container">
        <div class="results-info">
            <h2 id="hotels">Daftar Hotel (<?php echo count($hotels); ?> hasil)</h2>
            <div class="sort-info">
                <div class="sort-label">
                    Bubble Sort Algorithm
                </div>
                <div class="sort-stats">
                    <?php echo $sorted_result['swap_count']; ?> swaps • 
                    <?php echo $sorted_result['comparisons']; ?> comparisons • 
                    <?php echo $sorted_result['passes']; ?> passes
                </div>
            </div>
        </div>
    </div>
    
    <!-- HOTEL GRID -->
    <div class="container">
        <?php if (count($hotels) > 0): ?>
            <div class="hotel-grid">
                <?php foreach ($hotels as $index => $hotel): ?>
                    <div class="hotel-card">
                        <div class="hotel-image">
                            🏨
                            <span class="hotel-badge"><?php echo str_repeat('⭐', $hotel['star']); ?></span>
                        </div>
                        
                        <div class="hotel-content">
                            <div class="hotel-header">
                                <div>
                                    <div class="hotel-name"><?php echo $hotel['hotel']; ?></div>
                                    <div class="hotel-location">
                                        📍 <?php echo $hotel['location']; ?>
                                    </div>
                                </div>
                                <div class="hotel-rating">
                                    ⭐ <?php echo $hotel['rating']; ?>
                                </div>
                            </div>
                            
                            <?php if ($hotel['facilities']): ?>
                                <div class="hotel-facilities">
                                    <?php 
                                    $facilities = explode(',', $hotel['facilities']);
                                    $show_facilities = array_slice($facilities, 0, 3);
                                    foreach ($show_facilities as $facility): 
                                    ?>
                                        <span class="facility-tag"><?php echo trim($facility); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="hotel-price <?php echo ($sort_by == 'price' && $index < 3) ? 'highlight' : ''; ?>">
                                <?php if ($sort_by == 'price' && $index < 3): ?>
                                    💰 
                                <?php endif; ?>
                                Rp <?php echo number_format($hotel['price'], 0, ',', '.'); ?>
                                <span>/ malam</span>
                            </div>
                            
                            <div class="hotel-actions">
                                <a href="detail.php?id=<?php echo $hotel['id']; ?>" class="btn btn-primary">
                                    Lihat Detail
                                </a>
                                <a href="booking.php?id=<?php echo $hotel['id']; ?>" class="btn btn-secondary">
                                    Booking
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <h3>😔 Tidak ada hotel ditemukan</h3>
                <p>Coba ubah filter pencarian Anda</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- FOOTER -->
    <div class="footer">
        <p><strong>pesenhotel.com</strong> - Website Pencarian Hotel di Kota Malang</p>
        <p>Tugas Akhir Semester 1 Informatika | SMA Kelas 11 D</p>
        <p>Menggunakan algoritma <strong>Binary Search</strong> dan <strong>Bubble Sort</strong></p>
        <p>serta Integrasi <strong>Google Maps</strong></p>
    </div>
</body>
</html>

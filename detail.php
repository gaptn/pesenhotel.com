<?php
// File: detail.php
include 'config/db.php';

$hotel_id = isset($_GET['id']) ? clean_input($_GET['id']) : 0;

// Ambil data hotel
$query = "SELECT * FROM db_hotel WHERE id = $hotel_id";
$result = mysqli_query($conn, $query);
$hotel = mysqli_fetch_assoc($result);

if (!$hotel) {
    header('Location: index.php');
    exit;
}

// Parse fasilitas
$facilities = $hotel['facilities'] ? explode(',', $hotel['facilities']) : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $hotel['hotel']; ?> - pesenhotel.com</title>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        .main-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .hotel-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .hotel-title {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .hotel-meta {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .hotel-stars {
            font-size: 20px;
        }
        
        .hotel-rating {
            background: #10b981;
            color: white;
            padding: 5px 15px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .hotel-location {
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .hotel-image-large {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 120px;
            margin-bottom: 30px;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .facility-item {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .description {
            line-height: 1.8;
            color: #555;
        }
        
        .contact-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            color: #555;
        }
        
        .contact-item:last-child {
            margin-bottom: 0;
        }
        
        /* MAP SECTION */
        #map {
            width: 100%;
            height: 400px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        
        .map-link {
            display: block;
            text-align: center;
            background: #10b981;
            color: white;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .map-link:hover {
            background: #059669;
        }
        
        /* SIDEBAR */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .price-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .price-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .price-amount {
            font-size: 36px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 5px;
        }
        
        .price-unit {
            color: #999;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .btn-booking {
            display: block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
        }
        
        .btn-booking:hover {
            transform: translateY(-2px);
        }
        
        .quick-info {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .quick-info h3 {
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #666;
        }
        
        .info-value {
            font-weight: 600;
            color: #333;
        }
        
        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
            
            .facilities-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="container">
            <a href="index.php" style="font-size: 24px; font-weight: bold;">🏨 pesenhotel.com</a>
            <a href="index.php">← Kembali</a>
        </div>
    </div>
    
    <div class="container">
        <div class="detail-grid">
            <!-- MAIN CONTENT -->
            <div class="main-content">
                <div class="hotel-header">
                    <h1 class="hotel-title"><?php echo $hotel['hotel']; ?></h1>
                    <div class="hotel-meta">
                        <div class="hotel-stars">
                            <?php echo str_repeat('⭐', $hotel['star']); ?>
                        </div>
                        <div class="hotel-rating">
                            ⭐ <?php echo $hotel['rating']; ?> / 5
                        </div>
                        <div class="hotel-location">
                            📍 <?php echo $hotel['location']; ?>
                        </div>
                    </div>
                </div>
                
                <div class="hotel-image-large">
                    🏨
                </div>
                
                <?php if ($hotel['description']): ?>
                    <div class="section">
                        <h3>📝 Deskripsi Hotel</h3>
                        <p class="description"><?php echo nl2br($hotel['description']); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (count($facilities) > 0): ?>
                    <div class="section">
                        <h3>🎯 Fasilitas</h3>
                        <div class="facilities-grid">
                            <?php foreach ($facilities as $facility): ?>
                                <div class="facility-item">
                                    ✓ <?php echo trim($facility); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="section">
                    <h3>📞 Informasi Kontak</h3>
                    <div class="contact-info">
                        <div class="contact-item">
                            <strong>📫 Alamat:</strong> <?php echo $hotel['address']; ?>
                        </div>
                        <?php if ($hotel['phone']): ?>
                            <div class="contact-item">
                                <strong>📞 Telepon:</strong> <?php echo $hotel['phone']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- GOOGLE MAP SECTION -->
                <div class="section">
                    <h3>🗺️ Lokasi Hotel</h3>
                    
                    <?php if ($hotel['latitude'] && $hotel['longitude']): ?>
                        <div id="map"></div>
                        
                        <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $hotel['latitude']; ?>,<?php echo $hotel['longitude']; ?>" 
                           target="_blank" 
                           class="map-link">
                            🚗 Buka di Google Maps (Dapatkan Arah)
                        </a>
                    <?php else: ?>
                        <p style="color: #999; text-align: center; padding: 40px;">
                            Koordinat GPS belum tersedia untuk hotel ini
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- SIDEBAR -->
            <div class="sidebar">
                <div class="price-card">
                    <div class="price-label">Harga Mulai Dari</div>
                    <div class="price-amount">
                        Rp <?php echo number_format($hotel['price'], 0, ',', '.'); ?>
                    </div>
                    <div class="price-unit">per malam</div>
                    <a href="booking.php?id=<?php echo $hotel['id']; ?>" class="btn-booking">
                        🛏️ Booking Sekarang
                    </a>
                </div>
                
                <div class="quick-info">
                    <h3>ℹ️ Info Cepat</h3>
                    <div class="info-item">
                        <span class="info-label">Kategori</span>
                        <span class="info-value">Hotel Bintang <?php echo $hotel['star']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Rating</span>
                        <span class="info-value"><?php echo $hotel['rating']; ?> / 5.0</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Area</span>
                        <span class="info-value"><?php echo $hotel['location']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Harga</span>
                        <span class="info-value" style="color: #10b981;">
                            Rp <?php echo number_format($hotel['price'], 0, ',', '.'); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($hotel['latitude'] && $hotel['longitude']): ?>
    <!-- LOAD GOOGLE MAPS API -->
    <script>
        function initMap() {
            // Koordinat hotel
            const hotelLocation = {
                lat: <?php echo $hotel['latitude']; ?>,
                lng: <?php echo $hotel['longitude']; ?>
            };
            
            // Buat peta
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 16,
                center: hotelLocation,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true,
            });
            
            // Marker untuk hotel
            const marker = new google.maps.Marker({
                position: hotelLocation,
                map: map,
                title: "<?php echo addslashes($hotel['hotel']); ?>",
                animation: google.maps.Animation.DROP,
                icon: {
                    url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
                }
            });
            
            // Info window
            const infowindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 10px; max-width: 250px;">
                        <h3 style="margin: 0 0 10px 0; color: #333;">
                            <?php echo addslashes($hotel['hotel']); ?>
                        </h3>
                        <p style="margin: 5px 0; color: #666;">
                            ${<?php echo str_repeat("'⭐'", $hotel['star']); ?>}
                        </p>
                        <p style="margin: 5px 0; color: #666;">
                            Rating: <?php echo $hotel['rating']; ?>/5
                        </p>
                        <p style="margin: 5px 0; color: #666; font-size: 13px;">
                            📍 <?php echo addslashes($hotel['address']); ?>
                        </p>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $hotel['latitude']; ?>,<?php echo $hotel['longitude']; ?>" 
                           target="_blank"
                           style="display: inline-block; margin-top: 10px; background: #10b981; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-size: 13px;">
                            Dapatkan Arah
                        </a>
                    </div>
                `
            });
            
            // Tampilkan info window saat marker diklik
            marker.addListener("click", () => {
                infowindow.open(map, marker);
            });
            
            // Auto open info window
            infowindow.open(map, marker);
        }
    </script>
    
    <!-- Replace YOUR_API_KEY dengan API Key Google Maps kamu -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
    <?php endif; ?>
</body>
</html>
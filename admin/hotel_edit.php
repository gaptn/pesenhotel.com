<?php
// File: admin/hotel_edit.php
include 'session_check.php';

$success = '';
$error = '';

// Ambil ID hotel dari URL
$hotel_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Ambil data hotel
$query = "SELECT * FROM db_hotel WHERE id = $hotel_id";
$result = mysqli_query($conn, $query);
$hotel = mysqli_fetch_assoc($result);

if (!$hotel) {
    header('Location: hotel_list.php');
    exit;
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotel_name = clean_input($_POST['hotel']);
    $price = clean_input($_POST['price']);
    $rating = clean_input($_POST['rating']);
    $star = clean_input($_POST['star']);
    $location = clean_input($_POST['location']);
    $latitude = clean_input($_POST['latitude']);
    $longitude = clean_input($_POST['longitude']);
    $address = clean_input($_POST['address']);
    $phone = clean_input($_POST['phone']);
    $facilities = clean_input($_POST['facilities']);
    $description = clean_input($_POST['description']);
    
    $query = "UPDATE db_hotel SET 
              hotel = '$hotel_name',
              price = '$price',
              rating = '$rating',
              star = '$star',
              location = '$location',
              latitude = '$latitude',
              longitude = '$longitude',
              address = '$address',
              phone = '$phone',
              facilities = '$facilities',
              description = '$description'
              WHERE id = $hotel_id";
    
    if (mysqli_query($conn, $query)) {
        $success = "Data hotel berhasil diupdate!";
        // Refresh data
        $result = mysqli_query($conn, "SELECT * FROM db_hotel WHERE id = $hotel_id");
        $hotel = mysqli_fetch_assoc($result);
    } else {
        $error = "Gagal update data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hotel - Admin Panel</title>
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
        }
        
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
        }
        
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-card h2 {
            margin-bottom: 25px;
            color: #333;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert.success {
            background: #d4edda;
            color: #155724;
        }
        
        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .helper-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>✏️ Edit Hotel</h1>
        <a href="hotel_list.php">← Kembali ke Daftar</a>
    </nav>
    
    <div class="container">
        <div class="form-card">
            <h2>Edit Data: <?php echo $hotel['hotel']; ?></h2>
            
            <?php if ($success): ?>
                <div class="alert success">✅ <?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert error">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="hotel">🏨 Nama Hotel *</label>
                        <input type="text" id="hotel" name="hotel" value="<?php echo $hotel['hotel']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">💰 Harga per Malam (Rp) *</label>
                        <input type="number" id="price" name="price" value="<?php echo $hotel['price']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="rating">⭐ Rating (1-5) *</label>
                        <input type="number" id="rating" name="rating" step="0.1" min="1" max="5" value="<?php echo $hotel['rating']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="star">🌟 Bintang Hotel *</label>
                        <select id="star" name="star" required>
                            <option value="1" <?php echo $hotel['star'] == 1 ? 'selected' : ''; ?>>⭐ Bintang 1</option>
                            <option value="2" <?php echo $hotel['star'] == 2 ? 'selected' : ''; ?>>⭐⭐ Bintang 2</option>
                            <option value="3" <?php echo $hotel['star'] == 3 ? 'selected' : ''; ?>>⭐⭐⭐ Bintang 3</option>
                            <option value="4" <?php echo $hotel['star'] == 4 ? 'selected' : ''; ?>>⭐⭐⭐⭐ Bintang 4</option>
                            <option value="5" <?php echo $hotel['star'] == 5 ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ Bintang 5</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">📍 Lokasi/Area *</label>
                        <input type="text" id="location" name="location" value="<?php echo $hotel['location']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="latitude">🗺️ Latitude (GPS)</label>
                        <input type="text" id="latitude" name="latitude" value="<?php echo $hotel['latitude']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="longitude">🗺️ Longitude (GPS)</label>
                        <input type="text" id="longitude" name="longitude" value="<?php echo $hotel['longitude']; ?>">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="address">📫 Alamat Lengkap *</label>
                        <textarea id="address" name="address" required><?php echo $hotel['address']; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">📞 No. Telepon</label>
                        <input type="text" id="phone" name="phone" value="<?php echo $hotel['phone']; ?>">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="facilities">🎯 Fasilitas</label>
                        <textarea id="facilities" name="facilities"><?php echo $hotel['facilities']; ?></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description">📝 Deskripsi</label>
                        <textarea id="description" name="description"><?php echo $hotel['description']; ?></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <button type="submit" class="btn-submit">
                            ✅ Update Data Hotel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
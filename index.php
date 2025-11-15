<?php
// File: test.php
include 'config/database.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Test Database</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f0f0f0;
        }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
    </style>
</head>
<body>
    <h1>🧪 Test Koneksi Database</h1>
    
    <?php
    if ($conn) {
        echo "<p class='success'>✅ Koneksi database BERHASIL!</p>";
        
        // Hitung total hotel
        $query = "SELECT COUNT(*) as total FROM db_hotel";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);
        
        echo "<p>📊 Total hotel dalam database: <strong>" . $data['total'] . "</strong></p>";
        
        // Tampilkan semua hotel
        echo "<h2>📋 Daftar Hotel:</h2>";
        $query = "SELECT id, hotel, star, rating, price, location FROM db_hotel ORDER BY rating DESC";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Nama Hotel</th>
                    <th>Bintang</th>
                    <th>Rating</th>
                    <th>Harga</th>
                    <th>Lokasi</th>
                  </tr>";
            
            while ($row = mysqli_fetch_assoc($result)) {
                $stars = str_repeat('⭐', $row['star']);
                $price = "Rp " . number_format($row['price'], 0, ',', '.');
                
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td><strong>{$row['hotel']}</strong></td>
                        <td>{$stars}</td>
                        <td>{$row['rating']}/5</td>
                        <td>{$price}</td>
                        <td>{$row['location']}</td>
                      </tr>";
            }
            
            echo "</table>";
        } else {
            echo "<p class='error'>❌ Tidak ada data hotel!</p>";
        }
        
    } else {
        echo "<p class='error'>❌ Koneksi database GAGAL!</p>";
        echo "<p>Error: " . mysqli_connect_error() . "</p>";
    }
    ?>
</body>
</html>
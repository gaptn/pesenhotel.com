<?php
// File: admin/hotel_list.php
include 'session_check.php';

// Ambil semua data hotel
$query = "SELECT * FROM db_hotel ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Hotel - Admin Panel</title>
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
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .btn-add {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        
        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        tbody tr:hover {
            background: #f9f9f9;
        }
        
        .btn-action {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            display: inline-block;
            margin-right: 5px;
        }
        
        .btn-edit {
            background: #3b82f6;
            color: white;
        }
        
        .btn-delete {
            background: #ef4444;
            color: white;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }
        
        .badge-star {
            background: #fef3c7;
            color: #92400e;
        }
        
        .price {
            font-weight: 600;
            color: #10b981;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>📋 Kelola Data Hotel</h1>
        <a href="indexadm.php">← Kembali ke Dashboard</a>
    </nav>
    
    <div class="container">
        <div class="header-actions">
            <h2>Daftar Semua Hotel</h2>
            <a href="hotel_add.php" class="btn-add">➕ Tambah Hotel Baru</a>
        </div>
        
        <div class="table-card">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Hotel</th>
                            <th>Bintang</th>
                            <th>Rating</th>
                            <th>Harga</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><strong><?php echo $row['hotel']; ?></strong></td>
                                <td>
                                    <span class="badge badge-star">
                                        <?php echo str_repeat('⭐', $row['star']); ?>
                                    </span>
                                </td>
                                <td><?php echo $row['rating']; ?>/5</td>
                                <td class="price">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['location']; ?></td>
                                <td>
                                    <a href="hotel_edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">
                                        ✏️ Edit
                                    </a>
                                    <a href="hotel_delete.php?id=<?php echo $row['id']; ?>" 
                                       class="btn-action btn-delete" 
                                       onclick="return confirm('Yakin ingin menghapus hotel ini?')">
                                        🗑️ Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <h3>Belum ada data hotel</h3>
                    <p>Klik "Tambah Hotel Baru" untuk menambahkan data</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
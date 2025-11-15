<?php
// File: booking.php
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

$success = false;
$error = '';
$booking_code = '';
$booking_data = [];

// Proses booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = clean_input($_POST['nama']);
    $email = clean_input($_POST['email']);
    $phone = clean_input($_POST['phone']);
    $checkin = clean_input($_POST['checkin']);
    $checkout = clean_input($_POST['checkout']);
    $jumlah_kamar = clean_input($_POST['jumlah_kamar']);
    $catatan = clean_input($_POST['catatan']);
    
    // Hitung jumlah malam
    $date1 = new DateTime($checkin);
    $date2 = new DateTime($checkout);
    $diff = $date1->diff($date2);
    $jumlah_malam = $diff->days;
    
    // Hitung total harga
    $total_harga = $hotel['price'] * $jumlah_malam * $jumlah_kamar;
    
    // Validasi
    if ($jumlah_malam < 1) {
        $error = "Tanggal checkout harus setelah tanggal checkin!";
    } else {
        // Generate booking code
        $booking_code = 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
        
        // Insert ke database
        $insert_query = "INSERT INTO bookings (
            booking_code, hotel_id, customer_name, customer_email, customer_phone,
            checkin_date, checkout_date, jumlah_malam, jumlah_kamar,
            harga_per_malam, total_harga, catatan, status, payment_status
        ) VALUES (
            '$booking_code',
            $hotel_id,
            '$nama',
            '$email',
            '$phone',
            '$checkin',
            '$checkout',
            $jumlah_malam,
            $jumlah_kamar,
            {$hotel['price']},
            $total_harga,
            '$catatan',
            'pending',
            'unpaid'
        )";
        
        if (mysqli_query($conn, $insert_query)) {
            $success = true;
            $booking_data = [
                'booking_code' => $booking_code,
                'nama' => $nama,
                'email' => $email,
                'phone' => $phone,
                'checkin' => $checkin,
                'checkout' => $checkout,
                'jumlah_malam' => $jumlah_malam,
                'jumlah_kamar' => $jumlah_kamar,
                'total_harga' => $total_harga
            ];
        } else {
            $error = "Gagal menyimpan booking: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking <?php echo $hotel['hotel']; ?> - pesenhotel.com</title>
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
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .booking-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        .form-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .form-card h2 {
            margin-bottom: 25px;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
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
            min-height: 80px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .btn-submit:hover {
            opacity: 0.9;
        }
        
        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .summary-card h3 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .hotel-info {
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 20px;
        }
        
        .hotel-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .hotel-stars {
            margin-bottom: 5px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .summary-item:last-child {
            border-bottom: none;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 2px solid #f0f0f0;
            font-weight: bold;
            font-size: 18px;
        }
        
        .summary-label {
            color: #666;
        }
        
        .summary-value {
            font-weight: 600;
            color: #333;
        }
        
        .total {
            color: #10b981;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .confirmation {
            background: white;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .confirmation h2 {
            color: #10b981;
            font-size: 32px;
            margin-bottom: 20px;
        }
        
        .confirmation p {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .booking-code-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            border: 2px dashed #667eea;
        }
        
        .booking-code {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 2px;
        }
        
        .confirmation-details {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            text-align: left;
        }
        
        .confirmation-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .confirmation-row:last-child {
            border-bottom: none;
        }
        
        .btn-back {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .booking-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .summary-card {
                position: static;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="container">
            <a href="index.php" style="font-size: 24px; font-weight: bold;">🏨 pesenhotel.com</a>
            <a href="detail.php?id=<?php echo $hotel_id; ?>">← Kembali</a>
        </div>
    </div>
    
    <div class="container">
        <?php if (!$success): ?>
            <div class="booking-grid">
                <!-- FORM BOOKING -->
                <div class="form-card">
                    <h2>🛏️ Form Booking</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert error">❌ <?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap *</label>
                            <input type="text" id="nama" name="nama" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">No. Telepon *</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="checkin">Check-in *</label>
                                <input type="date" id="checkin" name="checkin" 
                                       min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="checkout">Check-out *</label>
                                <input type="date" id="checkout" name="checkout" 
                                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="jumlah_kamar">Jumlah Kamar *</label>
                            <input type="number" id="jumlah_kamar" name="jumlah_kamar" 
                                   min="1" value="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="catatan">Catatan Tambahan</label>
                            <textarea id="catatan" name="catatan" 
                                      placeholder="Contoh: Kamar non-smoking, lantai atas, dll."></textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            ✅ Konfirmasi Booking
                        </button>
                    </form>
                </div>
                
                <!-- SUMMARY -->
                <div class="summary-card">
                    <h3>📋 Ringkasan Booking</h3>
                    
                    <div class="hotel-info">
                        <div class="hotel-name"><?php echo $hotel['hotel']; ?></div>
                        <div class="hotel-stars">
                            <?php echo str_repeat('⭐', $hotel['star']); ?>
                        </div>
                        <div style="color: #666; font-size: 14px;">
                            📍 <?php echo $hotel['location']; ?>
                        </div>
                    </div>
                    
                    <div class="summary-item">
                        <span class="summary-label">Harga per Malam</span>
                        <span class="summary-value">
                            Rp <?php echo number_format($hotel['price'], 0, ',', '.'); ?>
                        </span>
                    </div>
                    
                    <div class="summary-item">
                        <span class="summary-label">Jumlah Malam</span>
                        <span class="summary-value" id="jumlah-malam">-</span>
                    </div>
                    
                    <div class="summary-item">
                        <span class="summary-label">Jumlah Kamar</span>
                        <span class="summary-value" id="jumlah-kamar-display">1</span>
                    </div>
                    
                    <div class="summary-item">
                        <span class="summary-label">Total Harga</span>
                        <span class="summary-value total" id="total-harga">
                            Rp <?php echo number_format($hotel['price'], 0, ',', '.'); ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- KONFIRMASI BOOKING -->
            <div class="confirmation">
                <h2>✅ Booking Berhasil!</h2>
                <p>Terima kasih atas booking Anda. Kami telah menerima permintaan booking Anda.</p>
                <p>Detail booking akan dikirimkan ke email Anda.</p>
                
                <div class="booking-code-box">
                    <p style="margin-bottom: 10px; color: #666; font-size: 14px;">Kode Booking Anda:</p>
                    <div class="booking-code"><?php echo $booking_data['booking_code']; ?></div>
                    <p style="margin-top: 10px; color: #666; font-size: 13px;">
                        Simpan kode ini untuk konfirmasi check-in
                    </p>
                </div>
                
                <div class="confirmation-details">
                    <div class="confirmation-row">
                        <strong>Hotel:</strong>
                        <span><?php echo $hotel['hotel']; ?></span>
                    </div>
                    <div class="confirmation-row">
                        <strong>Nama:</strong>
                        <span><?php echo $booking_data['nama']; ?></span>
                    </div>
                    <div class="confirmation-row">
                        <strong>Email:</strong>
                        <span><?php echo $booking_data['email']; ?></span>
                    </div>
                    <div class="confirmation-row">
                        <strong>Telepon:</strong>
                        <span><?php echo $booking_data['phone']; ?></span>
                    </div>
                    <div class="confirmation-row">
                        <strong>Check-in:</strong>
                        <span><?php echo date('d F Y', strtotime($booking_data['checkin'])); ?></span>
                    </div>
                    <div class="confirmation-row">
                        <strong>Check-out:</strong>
                        <span><?php echo date('d F Y', strtotime($booking_data['checkout'])); ?></span>
                    </div>
                    <div class="confirmation-row">
                        <strong>Jumlah Malam:</strong>
                        <span><?php echo $booking_data['jumlah_malam']; ?> malam</span>
                    </div>
                    <div class="confirmation-row">
                        <strong>Jumlah Kamar:</strong>
                        <span><?php echo $booking_data['jumlah_kamar']; ?> kamar</span>
                    </div>
                    <div class="confirmation-row" style="font-size: 18px; color: #10b981;">
                        <strong>Total Harga:</strong>
                        <strong>Rp <?php echo number_format($booking_data['total_harga'], 0, ',', '.'); ?></strong>
                    </div>
                </div>
                
                <a href="index.php" class="btn-back">🏠 Kembali ke Beranda</a>
                <a href="detail.php?id=<?php echo $hotel_id; ?>" class="btn-back" style="background: #6c757d;">
                    📄 Lihat Detail Hotel
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Auto calculate total price
        const pricePerNight = <?php echo $hotel['price']; ?>;
        const checkinInput = document.getElementById('checkin');
        const checkoutInput = document.getElementById('checkout');
        const jumlahKamarInput = document.getElementById('jumlah_kamar');
        
        function calculateTotal() {
            const checkin = new Date(checkinInput.value);
            const checkout = new Date(checkoutInput.value);
            const jumlahKamar = parseInt(jumlahKamarInput.value) || 1;
            
            if (checkin && checkout && checkout > checkin) {
                const diffTime = Math.abs(checkout - checkin);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                const total = pricePerNight * diffDays * jumlahKamar;
                
                document.getElementById('jumlah-malam').textContent = diffDays + ' malam';
                document.getElementById('jumlah-kamar-display').textContent = jumlahKamar + ' kamar';
                document.getElementById('total-harga').textContent = 
                    'Rp ' + total.toLocaleString('id-ID');
            }
        }
        
        checkinInput.addEventListener('change', calculateTotal);
        checkoutInput.addEventListener('change', calculateTotal);
        jumlahKamarInput.addEventListener('input', calculateTotal);
    </script>
</body>
</html>
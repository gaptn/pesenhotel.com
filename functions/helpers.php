<?php
// File: functions/helpers.php

/**
 * Format harga ke format Rupiah
 * 
 * @param int $angka - Nominal harga
 * @param bool $with_prefix - Tampilkan "Rp" atau tidak
 * @return string - Harga terformat
 */
function formatRupiah($angka, $with_prefix = true) {
    $prefix = $with_prefix ? "Rp " : "";
    return $prefix . number_format($angka, 0, ',', '.');
}

/**
 * Tampilkan bintang hotel dalam emoji
 * 
 * @param int $jumlah - Jumlah bintang (1-5)
 * @return string - String emoji bintang
 */
function displayStars($jumlah) {
    $stars = '';
    for ($i = 0; $i < $jumlah; $i++) {
        $stars .= '⭐';
    }
    return $stars;
}

/**
 * Generate warna berdasarkan rating
 * 
 * @param float $rating - Rating hotel (1-5)
 * @return string - Hex color code
 */
function getRatingColor($rating) {
    if ($rating >= 4.5) return '#10b981'; // Hijau (Excellent)
    if ($rating >= 4.0) return '#3b82f6'; // Biru (Very Good)
    if ($rating >= 3.5) return '#f59e0b'; // Orange (Good)
    if ($rating >= 3.0) return '#ef4444'; // Merah (Fair)
    return '#9ca3af'; // Abu-abu (Poor)
}

/**
 * Get rating label
 * 
 * @param float $rating - Rating hotel
 * @return string - Label rating
 */
function getRatingLabel($rating) {
    if ($rating >= 4.5) return 'Sangat Baik';
    if ($rating >= 4.0) return 'Baik';
    if ($rating >= 3.5) return 'Cukup Baik';
    if ($rating >= 3.0) return 'Sedang';
    return 'Kurang';
}

/**
 * Truncate text dengan elipsis
 * 
 * @param string $text - Text yang akan dipotong
 * @param int $length - Panjang maksimal
 * @param string $suffix - Suffix (default '...')
 * @return string - Text terpotong
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . $suffix;
    }
    return $text;
}

/**
 * Upload gambar hotel
 * 
 * @param array $file - File dari $_FILES
 * @param string $folder - Folder tujuan
 * @return array - Status upload dan nama file
 */
function uploadImage($file, $folder = 'assets/images/hotels/') {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $filename = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    // Validasi ekstensi
    if (!in_array($fileExt, $allowed)) {
        return [
            'status' => false, 
            'message' => 'Format file tidak diizinkan. Gunakan: ' . implode(', ', $allowed)
        ];
    }
    
    // Validasi ukuran (max 5MB)
    if ($fileSize > 5000000) {
        return [
            'status' => false, 
            'message' => 'Ukuran file terlalu besar (max 5MB)'
        ];
    }
    
    // Generate nama file unik
    $newFilename = uniqid('hotel_', true) . '.' . $fileExt;
    $destination = $folder . $newFilename;
    
    // Buat folder jika belum ada
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }
    
    // Upload file
    if (move_uploaded_file($fileTmp, $destination)) {
        return [
            'status' => true, 
            'filename' => $newFilename,
            'path' => $destination
        ];
    }
    
    return [
        'status' => false, 
        'message' => 'Gagal upload file'
    ];
}

/**
 * Format tanggal ke Bahasa Indonesia
 * 
 * @param string $date - Tanggal (Y-m-d)
 * @return string - Tanggal terformat
 */
function formatTanggal($date) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

/**
 * Hitung jumlah hari antara 2 tanggal
 * 
 * @param string $date1 - Tanggal mulai
 * @param string $date2 - Tanggal selesai
 * @return int - Jumlah hari
 */
function hitungHari($date1, $date2) {
    $datetime1 = new DateTime($date1);
    $datetime2 = new DateTime($date2);
    $interval = $datetime1->diff($datetime2);
    return $interval->days;
}

/**
 * Generate booking code
 * 
 * @return string - Kode booking unik
 */
function generateBookingCode() {
    return 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
}

/**
 * Validate email
 * 
 * @param string $email
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Indonesia)
 * 
 * @param string $phone
 * @return bool
 */
function isValidPhone($phone) {
    // Format: 08xx-xxxx-xxxx atau 0xxx-xxx-xxx
    $pattern = '/^(08|02|03|04|05|06|07|09)\d{7,11}$/';
    $clean_phone = preg_replace('/[^0-9]/', '', $phone);
    return preg_match($pattern, $clean_phone);
}

/**
 * Sanitize string untuk mencegah XSS
 * 
 * @param string $string
 * @return string
 */
function sanitize($string) {
    return htmlspecialchars(strip_tags($string), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate random color (untuk placeholder image)
 * 
 * @return string - Hex color
 */
function randomColor() {
    $colors = [
        '#667eea', '#764ba2', '#f093fb', '#4facfe',
        '#43e97b', '#fa709a', '#fee140', '#30cfd0'
    ];
    return $colors[array_rand($colors)];
}

/**
 * Get hotel status badge
 * 
 * @param int $booked - Status booking (0 atau 1)
 * @return string - HTML badge
 */
function getHotelStatusBadge($booked) {
    if ($booked == 1) {
        return '<span style="background: #ef4444; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px;">Penuh</span>';
    }
    return '<span style="background: #10b981; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px;">Tersedia</span>';
}

/**
 * Calculate distance between two GPS coordinates (Haversine formula)
 * 
 * @param float $lat1 - Latitude point 1
 * @param float $lon1 - Longitude point 1
 * @param float $lat2 - Latitude point 2
 * @param float $lon2 - Longitude point 2
 * @return float - Distance in kilometers
 */
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // Radius bumi dalam kilometer
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $earth_radius * $c;
    
    return round($distance, 2);
}

/**
 * Get facilities as array
 * 
 * @param string $facilities - String facilities separated by comma
 * @return array - Array of facilities
 */
function getFacilitiesArray($facilities) {
    if (empty($facilities)) {
        return [];
    }
    return array_map('trim', explode(',', $facilities));
}

/**
 * Generate slug from string
 * 
 * @param string $string
 * @return string - URL-friendly slug
 */
function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

/**
 * Debug helper - print array dengan format bagus
 * 
 * @param mixed $data
 * @param bool $exit - Exit setelah print
 */
function dd($data, $exit = true) {
    echo '<pre style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 2px solid #dee2e6;">';
    print_r($data);
    echo '</pre>';
    if ($exit) {
        exit;
    }
}

/**
 * Get client IP address
 * 
 * @return string - IP address
 */
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Log activity (simple file logging)
 * 
 * @param string $message
 * @param string $type - info, error, warning
 */
function logActivity($message, $type = 'info') {
    $log_file = 'logs/activity.log';
    $log_dir = dirname($log_file);
    
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0777, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = getClientIP();
    $log_message = "[$timestamp] [$type] [IP: $ip] $message" . PHP_EOL;
    
    file_put_contents($log_file, $log_message, FILE_APPEND);
}
?>
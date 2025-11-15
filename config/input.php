<?php
// File: insert_hotels_simple.php
// Script sederhana untuk insert 3 hotel tanpa tampilan

include 'config/database.php';

// Array data hotel
$hotels = [
    [
        'hotel' => 'Hotel Niagara Malang',
        'price' => 250000,
        'rating' => 4.0,
        'star' => 1,
        'location' => 'Lawang',
        'latitude' => -7.835833,
        'longitude' => 112.694722,
        'address' => 'Jl. Dr. Sutomo No.63, Krajan, Turirejo, Kec. Lawang, Kabupaten Malang, Jawa Timur 65215',
        'phone' => '0341-426222',
        'image' => 'niagara.jpg',
        'facilities' => 'WiFi Gratis, Parkir Gratis, AC, Restaurant, Lift Antik, Pemandangan Gunung, Arsitektur Kolonial Belanda',
        'description' => 'Hotel bersejarah yang dibangun tahun 1918 dengan arsitektur kolonial Belanda yang unik. Menawarkan pengalaman menginap vintage dengan pemandangan Gunung Arjuno. Dilengkapi lift kayu antik Asea dari Swedia yang masih berfungsi. Hotel ini cocok untuk pecinta sejarah dan arsitektur klasik. Lokasinya strategis di Lawang dengan suasana sejuk dan tenang.',
        'booked' => 0
    ],
    [
        'hotel' => 'Sunrise Hotel Malang',
        'price' => 350000,
        'rating' => 3.8,
        'star' => 3,
        'location' => 'Klojen',
        'latitude' => -7.979167,
        'longitude' => 112.630556,
        'address' => 'Jl. Dr. Cipto No. 5, Rampal Celaket, Kec. Klojen, Kota Malang, Jawa Timur 65111',
        'phone' => '0341-362888',
        'image' => 'sunrise.jpg',
        'facilities' => 'WiFi Gratis, Kolam Renang, Gym, Restaurant, Parkir Luas, Lift, Meeting Room, Laundry, 24-Hour Front Desk',
        'description' => 'Hotel bintang 3 yang nyaman dengan lokasi strategis di pusat Kota Malang. Dekat dengan Alun-Alun Kota Malang dan Stasiun Malang. Menawarkan kamar-kamar modern dengan fasilitas lengkap. Cocok untuk wisatawan bisnis dan keluarga. Memiliki kolam renang outdoor dan restaurant dengan menu beragam. Hanya 5 menit berkendara dari Malang Train Station.',
        'booked' => 0
    ],
    [
        'hotel' => 'Grand Mercure Malang Mirama',
        'price' => 1200000,
        'rating' => 4.7,
        'star' => 5,
        'location' => 'Blimbing',
        'latitude' => -7.946667,
        'longitude' => 112.654167,
        'address' => 'Jl. Raden Panji Suroso No. 7, Purwodadi, Kec. Blimbing, Kota Malang, Jawa Timur 65126',
        'phone' => '0341-4890888',
        'image' => 'mercure.jpg',
        'facilities' => 'WiFi Gratis, 2 Kolam Renang, Gym, Spa, 3 Restaurant, Coffee Cafe, Sky Lounge, Meeting Room, Ballroom, Kids Pool, Waterslide, Jacuzzi, Sauna, Art Gallery, Valet Parking, Airport Shuttle, Minibar Gratis',
        'description' => 'Hotel premium bintang 5 dengan 264 kamar dan suite mewah bergaya Batik Indonesia. Dilengkapi 3 restaurant (Trimurti, Lan Hua Chinese, Ebisu Japanese), grand ballroom untuk 2200 tamu, dan fasilitas meeting lengkap. Menawarkan pemandangan pegunungan yang indah. Lokasi strategis dekat Plaza Araya, Hawai Water Park, dan kawasan bisnis Malang. Cocok untuk acara bisnis, konvensi, dan liburan keluarga. Hanya 10 menit dari exit tol Singosari dan 15 menit dari bandara.',
        'booked' => 0
    ]
];

echo "Starting insert process...\n\n";

$success = 0;
$failed = 0;

// Insert setiap hotel
foreach ($hotels as $hotel) {
    // Escape string
    $hotel_name = mysqli_real_escape_string($conn, $hotel['hotel']);
    $address = mysqli_real_escape_string($conn, $hotel['address']);
    $phone = mysqli_real_escape_string($conn, $hotel['phone']);
    $facilities = mysqli_real_escape_string($conn, $hotel['facilities']);
    $description = mysqli_real_escape_string($conn, $hotel['description']);
    $location = mysqli_real_escape_string($conn, $hotel['location']);
    
    // Query insert
    $query = "INSERT INTO db_hotel (hotel, price, rating, star, location, latitude, longitude, address, phone, image, facilities, description, booked) 
              VALUES (
                  '$hotel_name',
                  {$hotel['price']},
                  {$hotel['rating']},
                  {$hotel['star']},
                  '$location',
                  {$hotel['latitude']},
                  {$hotel['longitude']},
                  '$address',
                  '$phone',
                  '{$hotel['image']}',
                  '$facilities',
                  '$description',
                  {$hotel['booked']}
              )";
    
    if (mysqli_query($conn, $query)) {
        $inserted_id = mysqli_insert_id($conn);
        echo "✓ SUCCESS: {$hotel['hotel']} (ID: $inserted_id)\n";
        $success++;
    } else {
        echo "✗ FAILED: {$hotel['hotel']} - " . mysqli_error($conn) . "\n";
        $failed++;
    }
}

echo "\n=========================\n";
echo "SUMMARY:\n";
echo "Success: $success\n";
echo "Failed: $failed\n";
echo "=========================\n";

// Cek total hotel di database
$count_query = "SELECT COUNT(*) as total FROM db_hotel";
$count_result = mysqli_query($conn, $count_query);
$count_data = mysqli_fetch_assoc($count_result);

echo "\nTotal hotel di database: {$count_data['total']}\n";

mysqli_close($conn);
?>
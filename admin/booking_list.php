<?php
include 'session_check.php';

$query = "SELECT * FROM v_booking_report ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Booking - Admin</title>
    <!-- Copy style dari hotel_list.php -->
</head>
<body>
    <h1>Daftar Booking</h1>
    <table>
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Customer</th>
                <th>Hotel</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['booking_code']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['hotel_name']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['checkin_date'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['checkout_date'])); ?></td>
                    <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
```

---

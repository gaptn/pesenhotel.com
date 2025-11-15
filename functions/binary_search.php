<?php
// File: functions/binary_search.php

/**
 * Binary Search untuk mencari hotel berdasarkan ID
 * Kompleksitas: O(log n)
 * 
 * @param array $hotels - Array hotel yang sudah terurut
 * @param int $searchId - ID hotel yang dicari
 * @return array|null - Data hotel dan info pencarian atau null
 */
function binarySearchById($hotels, $searchId) {
    // Urutkan array berdasarkan ID terlebih dahulu
    usort($hotels, function($a, $b) {
        return $a['id'] - $b['id'];
    });
    
    $left = 0;
    $right = count($hotels) - 1;
    $iterations = 0;
    $steps = []; // Menyimpan langkah pencarian untuk demo
    
    while ($left <= $right) {
        $iterations++;
        $mid = floor(($left + $right) / 2);
        
        // Simpan langkah untuk visualisasi
        $steps[] = [
            'iteration' => $iterations,
            'left' => $left,
            'right' => $right,
            'mid' => $mid,
            'mid_id' => $hotels[$mid]['id'],
            'action' => ''
        ];
        
        // Jika ID ditemukan
        if ($hotels[$mid]['id'] == $searchId) {
            $steps[count($steps) - 1]['action'] = 'FOUND';
            
            return [
                'found' => true,
                'data' => $hotels[$mid],
                'iterations' => $iterations,
                'steps' => $steps,
                'algorithm' => 'Binary Search',
                'complexity' => 'O(log n)'
            ];
        }
        
        // Jika ID yang dicari lebih besar, cari di sebelah kanan
        if ($hotels[$mid]['id'] < $searchId) {
            $steps[count($steps) - 1]['action'] = 'GO_RIGHT';
            $left = $mid + 1;
        } 
        // Jika ID yang dicari lebih kecil, cari di sebelah kiri
        else {
            $steps[count($steps) - 1]['action'] = 'GO_LEFT';
            $right = $mid - 1;
        }
    }
    
    // Hotel tidak ditemukan
    return [
        'found' => false,
        'data' => null,
        'iterations' => $iterations,
        'steps' => $steps,
        'algorithm' => 'Binary Search',
        'complexity' => 'O(log n)'
    ];
}

/**
 * Binary Search untuk mencari hotel berdasarkan nama
 * 
 * @param array $hotels - Array hotel yang sudah terurut
 * @param string $searchName - Nama hotel yang dicari
 * @return array|null - Data hotel dan info pencarian atau null
 */
function binarySearchByName($hotels, $searchName) {
    // Urutkan array berdasarkan nama
    usort($hotels, function($a, $b) {
        return strcmp(strtolower($a['hotel']), strtolower($b['hotel']));
    });
    
    $left = 0;
    $right = count($hotels) - 1;
    $iterations = 0;
    $steps = [];
    
    while ($left <= $right) {
        $iterations++;
        $mid = floor(($left + $right) / 2);
        
        // Case-insensitive comparison
        $comparison = strcasecmp($hotels[$mid]['hotel'], $searchName);
        
        $steps[] = [
            'iteration' => $iterations,
            'left' => $left,
            'right' => $right,
            'mid' => $mid,
            'mid_name' => $hotels[$mid]['hotel'],
            'action' => ''
        ];
        
        if ($comparison == 0) {
            $steps[count($steps) - 1]['action'] = 'FOUND';
            
            return [
                'found' => true,
                'data' => $hotels[$mid],
                'iterations' => $iterations,
                'steps' => $steps,
                'algorithm' => 'Binary Search',
                'complexity' => 'O(log n)'
            ];
        }
        
        if ($comparison < 0) {
            $steps[count($steps) - 1]['action'] = 'GO_RIGHT';
            $left = $mid + 1;
        } else {
            $steps[count($steps) - 1]['action'] = 'GO_LEFT';
            $right = $mid - 1;
        }
    }
    
    return [
        'found' => false,
        'data' => null,
        'iterations' => $iterations,
        'steps' => $steps,
        'algorithm' => 'Binary Search',
        'complexity' => 'O(log n)'
    ];
}

/**
 * Linear Search (untuk perbandingan dengan Binary Search)
 * Kompleksitas: O(n)
 * 
 * @param array $hotels - Array hotel
 * @param int $searchId - ID hotel yang dicari
 * @return array - Data hotel dan info pencarian
 */
function linearSearchById($hotels, $searchId) {
    $iterations = 0;
    $steps = [];
    
    foreach ($hotels as $index => $hotel) {
        $iterations++;
        
        $steps[] = [
            'iteration' => $iterations,
            'index' => $index,
            'hotel_id' => $hotel['id'],
            'action' => $hotel['id'] == $searchId ? 'FOUND' : 'CONTINUE'
        ];
        
        if ($hotel['id'] == $searchId) {
            return [
                'found' => true,
                'data' => $hotel,
                'iterations' => $iterations,
                'steps' => $steps,
                'algorithm' => 'Linear Search',
                'complexity' => 'O(n)'
            ];
        }
    }
    
    return [
        'found' => false,
        'data' => null,
        'iterations' => $iterations,
        'steps' => $steps,
        'algorithm' => 'Linear Search',
        'complexity' => 'O(n)'
    ];
}

/**
 * Fungsi untuk mencari hotel dengan multiple criteria
 * Menggunakan Binary Search jika mencari by ID, Linear Search untuk criteria lain
 * 
 * @param array $hotels - Array hotel
 * @param array $criteria - Kriteria pencarian ['field' => value]
 * @return array - Hasil pencarian
 */
function searchHotel($hotels, $criteria) {
    $results = [];
    $search_info = [];
    
    // Jika mencari by ID, gunakan Binary Search
    if (isset($criteria['id'])) {
        $result = binarySearchById($hotels, $criteria['id']);
        if ($result['found']) {
            $results[] = $result['data'];
        }
        $search_info = $result;
    }
    // Jika mencari by nama, gunakan Binary Search
    elseif (isset($criteria['hotel'])) {
        $result = binarySearchByName($hotels, $criteria['hotel']);
        if ($result['found']) {
            $results[] = $result['data'];
        }
        $search_info = $result;
    }
    // Untuk criteria lain, gunakan filter biasa
    else {
        foreach ($hotels as $hotel) {
            $match = true;
            foreach ($criteria as $field => $value) {
                if (isset($hotel[$field]) && $hotel[$field] != $value) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                $results[] = $hotel;
            }
        }
        $search_info = [
            'found' => count($results) > 0,
            'algorithm' => 'Filter',
            'results_count' => count($results)
        ];
    }
    
    return [
        'results' => $results,
        'search_info' => $search_info
    ];
}
?>
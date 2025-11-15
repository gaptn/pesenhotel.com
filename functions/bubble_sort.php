<?php
// File: functions/bubble_sort.php

/**
 * Bubble Sort untuk mengurutkan hotel
 * Kompleksitas: O(n²)
 * 
 * @param array $hotels - Array hotel
 * @param string $sortBy - Kolom yang dijadikan acuan (rating, price, hotel)
 * @param string $order - ASC atau DESC
 * @return array - Array hotel yang sudah terurut + info sorting
 */
function bubbleSortHotels($hotels, $sortBy = 'rating', $order = 'DESC') {
    $n = count($hotels);
    $swapCount = 0;
    $comparisons = 0;
    $passes = 0;
    
    // Jika array kosong atau hanya 1 elemen
    if ($n <= 1) {
        return [
            'data' => $hotels,
            'swap_count' => 0,
            'comparisons' => 0,
            'passes' => 0,
            'algorithm' => 'Bubble Sort',
            'complexity' => 'O(n²)'
        ];
    }
    
    for ($i = 0; $i < $n - 1; $i++) {
        $swapped = false;
        $passes++;
        
        for ($j = 0; $j < $n - $i - 1; $j++) {
            $comparisons++;
            $condition = false;
            
            // Tentukan kondisi sorting berdasarkan order
            if ($order == 'DESC') {
                // Descending: nilai besar ke kecil
                $condition = $hotels[$j][$sortBy] < $hotels[$j + 1][$sortBy];
            } else {
                // Ascending: nilai kecil ke besar
                $condition = $hotels[$j][$sortBy] > $hotels[$j + 1][$sortBy];
            }
            
            // Lakukan swap jika kondisi terpenuhi
            if ($condition) {
                $temp = $hotels[$j];
                $hotels[$j] = $hotels[$j + 1];
                $hotels[$j + 1] = $temp;
                $swapped = true;
                $swapCount++;
            }
        }
        
        // Optimasi: jika tidak ada swap dalam 1 pass, array sudah terurut
        if (!$swapped) {
            break;
        }
    }
    
    return [
        'data' => $hotels,
        'swap_count' => $swapCount,
        'comparisons' => $comparisons,
        'passes' => $passes,
        'algorithm' => 'Bubble Sort',
        'complexity' => 'O(n²)',
        'sorted_by' => $sortBy,
        'order' => $order
    ];
}

/**
 * Bubble Sort dengan tracking detail setiap langkah (untuk demo/presentasi)
 * 
 * @param array $hotels - Array hotel
 * @param string $sortBy - Kolom sorting
 * @param string $order - ASC atau DESC
 * @return array - Steps + hasil akhir
 */
function bubbleSortWithSteps($hotels, $sortBy = 'rating', $order = 'DESC') {
    $n = count($hotels);
    $steps = [];
    $swapCount = 0;
    
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            $condition = ($order == 'DESC') 
                ? $hotels[$j][$sortBy] < $hotels[$j + 1][$sortBy]
                : $hotels[$j][$sortBy] > $hotels[$j + 1][$sortBy];
            
            if ($condition) {
                // Swap
                $temp = $hotels[$j];
                $hotels[$j] = $hotels[$j + 1];
                $hotels[$j + 1] = $temp;
                $swapCount++;
                
                // Simpan langkah
                $steps[] = [
                    'pass' => $i + 1,
                    'comparison' => $j + 1,
                    'swapped' => true,
                    'element1' => $temp['hotel'],
                    'element2' => $hotels[$j]['hotel'],
                    'array_state' => array_map(function($h) use ($sortBy) {
                        return [
                            'hotel' => $h['hotel'],
                            'value' => $h[$sortBy]
                        ];
                    }, $hotels)
                ];
            }
        }
    }
    
    return [
        'final_data' => $hotels,
        'steps' => $steps,
        'total_swaps' => $swapCount,
        'total_passes' => count($steps) > 0 ? $steps[count($steps) - 1]['pass'] : 0
    ];
}

/**
 * Quick Sort (untuk perbandingan performance)
 * Kompleksitas: O(n log n)
 * 
 * @param array $hotels - Array hotel
 * @param string $sortBy - Kolom sorting
 * @param string $order - ASC atau DESC
 * @return array - Array terurut + info
 */
function quickSortHotels($hotels, $sortBy = 'rating', $order = 'DESC') {
    if (count($hotels) <= 1) {
        return [
            'data' => $hotels,
            'algorithm' => 'Quick Sort',
            'complexity' => 'O(n log n)'
        ];
    }
    
    // PHP memiliki usort yang menggunakan Quick Sort
    usort($hotels, function($a, $b) use ($sortBy, $order) {
        if ($order == 'DESC') {
            return $b[$sortBy] <=> $a[$sortBy];
        } else {
            return $a[$sortBy] <=> $b[$sortBy];
        }
    });
    
    return [
        'data' => $hotels,
        'algorithm' => 'Quick Sort (PHP usort)',
        'complexity' => 'O(n log n)',
        'sorted_by' => $sortBy,
        'order' => $order
    ];
}

/**
 * Fungsi untuk membandingkan performa Bubble Sort vs Quick Sort
 * 
 * @param array $hotels - Array hotel
 * @param string $sortBy - Kolom sorting
 * @param string $order - ASC atau DESC
 * @return array - Perbandingan kedua algoritma
 */
function compareSort($hotels, $sortBy = 'rating', $order = 'DESC') {
    // Bubble Sort
    $start_bubble = microtime(true);
    $bubble_result = bubbleSortHotels($hotels, $sortBy, $order);
    $time_bubble = microtime(true) - $start_bubble;
    
    // Quick Sort
    $start_quick = microtime(true);
    $quick_result = quickSortHotels($hotels, $sortBy, $order);
    $time_quick = microtime(true) - $start_quick;
    
    return [
        'bubble_sort' => [
            'time' => $time_bubble,
            'swaps' => $bubble_result['swap_count'],
            'comparisons' => $bubble_result['comparisons'],
            'complexity' => 'O(n²)'
        ],
        'quick_sort' => [
            'time' => $time_quick,
            'complexity' => 'O(n log n)'
        ],
        'faster' => $time_bubble < $time_quick ? 'Bubble Sort' : 'Quick Sort',
        'time_difference' => abs($time_bubble - $time_quick),
        'data_count' => count($hotels)
    ];
}

/**
 * Multi-level sorting (sort by multiple fields)
 * Contoh: Sort by star DESC, then by rating DESC
 * 
 * @param array $hotels - Array hotel
 * @param array $sortFields - Array of ['field' => 'order']
 * @return array - Sorted hotels
 */
function multiLevelSort($hotels, $sortFields) {
    usort($hotels, function($a, $b) use ($sortFields) {
        foreach ($sortFields as $field => $order) {
            $comparison = 0;
            
            if ($order == 'DESC') {
                $comparison = $b[$field] <=> $a[$field];
            } else {
                $comparison = $a[$field] <=> $b[$field];
            }
            
            // Jika tidak sama, return comparison
            if ($comparison != 0) {
                return $comparison;
            }
            // Jika sama, lanjut ke field berikutnya
        }
        return 0;
    });
    
    return [
        'data' => $hotels,
        'sort_fields' => $sortFields
    ];
}
?>
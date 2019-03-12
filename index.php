<?php

    $set = [
        [9,7,6,1,5,2,4,3,8],
        [8,2,3,4,7,6,9,1,5],
        [5,1,4,8,3,9,6,7,2],
        [1,4,8,7,2,5,3,6,9],
        [3,6,5,9,1,4,8,2,7],
        [7,9,2,3,6,8,5,4,1],
        [6,3,1,5,8,7,2,9,4],
        [4,5,7,2,9,3,1,8,6],
        [2,8,9,6,4,1,7,5,3]
    ];

    $set2 = [
        [6,3,9,2,4,1,7,8,5],
        [2,8,4,7,6,5,10,9,3],
        [5,1,7,9,8,3,6,2,4],
        [1,2,3,8,5,7,9,4,6],
        [7,9,6,4,3,2,8,5,1],
        [4,5,8,6,1,9,2,3,7],
        [3,4,2,1,7,8,5,6,9],
        [8,6,1,5,9,4,3,7,2],
        [9,7,5,3,2,6,4,1,8]
    ];

    // I found this little beauty on the web that is a one liner matrix rotation script 90 degress right
    function rotate_grid($set) {
        return call_user_func_array('array_map',array(-1 => null) + array_map('array_reverse', $set));
    }

    // regroup and re-evalute. we need to regroup the rows into subsets
    function sub_grid($set) {

        // set some initial values
        $new_set = [];
        $new_row = 0;
        $new_col = 0;
        $increment_new_row = 0;

        // only make one pass through all 81 numbers and sort out the new matrix
        for ($row = 0; $row < sizeof($set); $row++) {
            for ($col = 0; $col < sizeof ($set[$row]); $col++) {
                $new_set[$new_row][$new_col++] = $set[$row][$col];
                
                // increment the new row and col only if we've reach a modulus of the row and col for the various iterations
                if ($new_col > 0 && $new_col % 3 == 0) {
                    $new_col -= 3;
                    $new_row++;
                    $increment_new_row++;
                    if ($increment_new_row > 0 && $increment_new_row % 3 == 0) {
                        $new_row -= 3;
                        $new_col += 3;
                    }
                    if ($increment_new_row > 0 && $increment_new_row % 9 == 0) {
                        $new_row += 3;
                        $new_col = 0;
                        $increment_new_row = 0;
                    }
                }
                
            }
        }
        
        return $new_set;
    }

    // this is probably the easiest one to examine
    function validate($set) {
        foreach($set as $row => $numbers) {
            // remove all elements in the array that are not within the number range 1-9
            foreach ($numbers as $col => $number) {
                if ($number > 0 && $number < 10 && is_numeric($set[$row][$col])) {
                    // great!
                } else {
                    if (is_string($number) || is_null($number)) {
                        return false; // just bad data
                    }
                    array_splice($numbers[$col],1);  // remove that square and reindex. Will use the count function later.
                }
            }

            // just quickly check to see if the numbers in the row are all filled in
            if (sizeof($numbers) != 9) {
                return false;
            }
                        
            
            // if we strip out the duplicates do we still get the same number of numberss
            if (count($numbers) != count(array_unique($numbers))) {
                return false;
            }

            
        }
        return true;
    }

    
    function sudokuValidator($target_set) {
        if (validate($target_set) && validate(rotate_grid($target_set)) && validate(sub_grid($target_set))) {
            // set 1 Looks good!
            return true;
        } 
    }

    
    echo sudokuValidator($set) ? "yeah! set 1\n" : "booo set 1\n";

    echo sudokuValidator($set2) ? "yeah! set 2\n" : "booo set 2\n";    
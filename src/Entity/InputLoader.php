<?php

namespace App\Entity;

class InputLoader
{

    /**
     * @param string $file
     * @return array of string
     */
    public function parseFromFile($file) {
        $handle = @fopen($file, "r");
        if ($handle) {
            $data = array();
            while (($buffer = fgets($handle, 4096)) !== false) {
                $data[] = $buffer;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }

        return $data;
    }


}
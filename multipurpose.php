//this will give use multiple csv files


        ini_set('max_execution_time', '300');
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        //Taking in EAN number File
        $csv = array_map("str_getcsv", file("uploads/Ean.csv", FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($csv);

        //Taking in the file which needs to be checked
        $csv2 = array_map("str_getcsv", file("uploads/Karlstad2019.csv", FILE_SKIP_EMPTY_LINES));
        $keys2 = array_shift($csv2);

        dump("First Step");

        foreach ($csv2 as $i => $row) {
            $csv2[$i] = array_combine($keys2, $row);
        }

        foreach ($csv as $i => $row) {
            $csv[$i] = array_combine($keys, $row);
        }
        dump("second Step");

        //Counter for Products with EAN
        $count = 0;

        //Counter For products without EAN number
        $notcount = 0;

        //Array for products without EAN numbers
        $articlsWithoutEanNumbers = array();

        //Array for products with EAN numbers
        $articlsWithEanNumbers = array();


        //Checking EAN numbers
        foreach ($csv as $value) {

            foreach ($csv2 as &$value2) {

                if ($value['Artnr'] == $value2['Lev. Artnr']) {

                    $val = $value['Ean nr'];
                    $value2['EAN'] = $val;

                    //pushing products with EAN numbers in a separate array
                    array_push($articlsWithEanNumbers, $value2);
                    $count++;
                }
            }
        }

        //Pushing products without EAN numbers in a separate array
        foreach ($csv2 as $value) {

            if ($value['EAN'] == "") {

                array_push($articlsWithoutEanNumbers, $value);
                $notcount++;
            }
        }
        dump("Third Step");



        dump("Finished with EAN Products " . $count);

        dump("Finished with Non EAN Products " . $notcount);

        //Making Satistics
        $totalProducts = count($csv2);

        //For Ean Products
        $eanDivision = $count / $totalProducts;
        $eanMultiple = $eanDivision * 100;
        $eanPercentage = round($eanMultiple);

        //For Non Ean Products
        $nonEanDivision = $notcount / $totalProducts;
        $nonEanMultiple = $nonEanDivision * 100;
        $nonEanPercentage = round($nonEanMultiple);

        $satistics = array(

            array("Total Products", $totalProducts, "100%"),
            array("Products with EAN Number", $count, $eanPercentage . "%"),
            array("Products with No EAN Number", $notcount, $nonEanPercentage . "%")

        );

        //Output the updated csv file with EAN numbers for the missing values
        $file = 'C:\Users\arsalan\Desktop\consupedia\ScriptResults\\UpdatedKarlstad2019.csv';
        $fp = fopen($file, 'w');

        // Loop through file pointer and a line
        foreach ($csv2 as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
        dump("First File Created");
        //Outputs the csv file for Article Numbers without EAN
        $file2 = 'C:\Users\arsalan\Desktop\consupedia\ScriptResults\\NoEAnKarlstad2019.csv';
        $fp2 = fopen($file2, 'w');

        // Loop through file pointer and a line
        foreach ($articlsWithoutEanNumbers as $fields2) {
            fputcsv($fp2, $fields2);
        }

        fclose($fp2);
        dump("Second File Created");
        //Outputs the csv file for Article Numbers with EAN
        $file = 'C:\Users\arsalan\Desktop\consupedia\ScriptResults\\EAnKarlstad2019.csv';
        $fp = fopen($file, 'w');

        // Loop through file pointer and a line
        foreach ($articlsWithEanNumbers as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
        dump("Third File Created");

        $file = 'C:\Users\arsalan\Desktop\consupedia\ScriptResults\\SatisticsKarlstad2019.csv';
        $fp = fopen($file, 'w');

        // Loop through file pointer and a line
        foreach ($satistics as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
        dump("Forth File Created");
        dump("Process Completed");
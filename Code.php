<?php
// Function to read CSV file and return data as array
function readCSV($filename) {
    $data = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, "\t")) !== FALSE) {
            
            $data[] = explode(",",$row[0]) ;
        }
        fclose($handle);
    }
    return $data;
}

// Function to query services based on country code
function queryByCountry($data, $countryCode) {
    $services = [];
    foreach ($data as $row) {
        if ( strtolower($row[3]) === strtolower($countryCode)) {
            $services[] = $row[2];
        }
    }
    return $services;
}

// Function to generate summary of total services in each country
function generateSummary($data) {
    $summary = [];
    foreach ($data as $row) {
        $countryCode = strtolower($row[3]);
        if (!isset($summary[$countryCode])) {
            $summary[$countryCode] = 1;
        } else {
            $summary[$countryCode]++;
        }
    }
    return $summary;
}

// Main function
function main() {
    $filename = 'services.csv'; // Change this to your CSV file name
    $data = readCSV($filename);

    if (empty($data)) {
        echo "Error: Failed to read CSV file.\n";
        return;
    }

    $argc = $_SERVER['argc'];
    $argv = $_SERVER['argv'];
    if ($argc < 2) {
        echo "Please state the Country code with script";
        return;
    }

    $countryCode = strtoupper($argv[1]);
  
    $services = queryByCountry($data, $countryCode);

    if (empty($services)) {
        echo "No services found for country code '$countryCode'.\n";
    } else {
        echo "Services provided by $countryCode:\n";
        foreach ($services as $service) {
            echo "- $service\n";
        }
    }

    $summary = generateSummary($data);
    echo "\nSummary of total services in each country:\n";
    foreach ($summary as $country => $count) {
        echo "- $country: $count services\n";
    }
}

main();
?>

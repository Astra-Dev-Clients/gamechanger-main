<?php
include '../database/db.php';

// Get the requested country
$countryName = $_GET['country'] ?? '';

if (!$countryName) {
    echo "<p class='text-muted text-center'>No country specified.</p>";
    exit;
}

// Load the countries dataset
$countriesJsonPath = '../assets/dataset/countries.json';
$countriesList = [];
if (file_exists($countriesJsonPath)) {
    $jsonContent = file_get_contents($countriesJsonPath);
    $countriesList = json_decode($jsonContent, true);
}

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM opportunities WHERE country IS NOT NULL AND country <> ''");
$stmt->execute();
$result = $stmt->get_result();

$found = false;

while ($row = $result->fetch_assoc()) {
    $countryData = json_decode($row['country'], true);

    // Handle both JSON and plain text cases
    $storedCountry = $countryData['country'] ?? trim($row['country']);
    $storedCountryLower = strtolower($storedCountry);
    $flag = '';

    // Try to find a matching flag from countries.json
    foreach ($countriesList as $countryInfo) {
        if (strtolower($countryInfo['country']) === $storedCountryLower) {
            $flag = $countryInfo['flag'];
            break;
        }
    }

    // Fallback if no match found
    if (empty($flag)) {
        $flag = "https://countryflagsapi.com/png/" . strtolower(substr($storedCountry, 0, 2));
    }

    // If it matches the selected country
    if (strcasecmp($storedCountry, $countryName) === 0) {
        $found = true;
        $title = htmlspecialchars($row['title']);
        $institution = htmlspecialchars($row['institution']);
        $description = htmlspecialchars(substr($row['description'], 0, 100));
        $image = htmlspecialchars($row['image_url'] ?? '');
        $oppUrl = htmlspecialchars($row['opp_url']);

        echo "
        <div class='col-md-4'>
          <div class='card border-0 shadow-sm h-100'>
            <img src='upload/{$image}' class='card-img-top' alt='{$title}' 
                 style='height:200px; object-fit:cover;'>
            <div class='card-body'>
              <div class='d-flex align-items-center mb-2'>
                <img src='{$flag}' alt='{$storedCountry} flag' 
                     style='width:24px; height:16px; border-radius:2px; margin-right:8px;'>
                <p class='text-muted small mb-0'>{$institution} — {$storedCountry}</p>
              </div>
              <h5 class='fw-bold'>{$title}</h5>
              <p class='mb-3'>{$description}...</p>
              <a href='{$oppUrl}' target='_blank' class='btn btn-sm btn-primary'>View Details</a>
            </div>
          </div>
        </div>";
    }
}

// If no results found for that country
if (!$found) {
    echo "<p class='text-muted text-center'>No opportunities available in {$countryName} yet.</p>";
}

$stmt->close();
$conn->close();
?>

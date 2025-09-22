<?php
    $all_cities = ["New York", "Los Angeles", "Chicago", "Houston", "Phoenix", "Philadelphia", "San Antonio", "San Diego", "Dallas", "San Jose"];

    if (isset($_GET['search'])){
        $search_term = $_GET['search'];


        $filtered_cities = [];
        foreach ($all_cities as $city){
            if (str_contains(strtolower($search_term),strtolower($city))){
                $filtered_cities[] = $city;
            }

        }
        $cities_to_display = $filtered_cities;
    }else {
        $search_term = "";
        $cities_to_display = $all_cities;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>City Search</title>
         <style>
        body { font-family: sans-serif; background-color: #f2f2f2; }
        .container { max-width: 500px; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        form { display: flex; gap: 10px; margin-bottom: 20px; }
        input[type="text"] { flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        ul { list-style-type: square; }
    </style>
    </head>

    <body>
        <div class="container">
            <h1>Search for city</h1>
                <form action="" method="GET">
                    <input type="text" name="search" placeholder="Enter city name..." value="<?= htmlspecialchars($search_term);?>">
                    <button type="submit">Search</button>
                </form>
            
            <ul>
                <?php foreach ($cities_to_display as $city) : ?>
                    <li><?= htmlspecialchars($city); ?></li>
                <?php endforeach?>

                <?php if (empty($cities_to_display)) : ?>
                    <p>No Cities Found </p>
                <?php endif?>

            </ul>
        </div>

    </body>
</html>
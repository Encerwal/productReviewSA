<?php
session_start(); // Start the session

include('header.php');
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script> <!-- Include DataLabels plugin -->
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<main class="main">
    
<section id="hero" class="hero section">
    <div class="container2 d-flex justify-content-center align-items-start" id="csv-container">
        <div class="testa row gy-4">  
            <?php 
            // Check if file data is available in the session
            if (isset($_SESSION['file_data'])) { ?>
                <div class="col-lg-12 d-flex flex-column justify-content-center text-center">
                    <h1 id='csv-title'>Analysis Results</h1>
                </div>
                <!-- Large Box on the Left -->
                <div class="col-lg-5" id="csvTable">
                
                <?php
                    // Decode the JSON data
                    $data = json_decode($_SESSION['file_data'], true);
                    $data = json_decode($data, true);
                    $qualPos = 0;
                    $qualNeg = 0;
                    $pricePos = 0;
                    $priceNeg = 0;
                    $funcPos = 0;
                    $funcNeg = 0;
                    $otherPos = 0;
                    $otherNeg = 0;

                    // Check if decoding was successful
                    if (json_last_error() === JSON_ERROR_NONE) {
                        if (is_array($data)) {
                            echo "<table border='1'>";
                            echo "<tr><th>Text</th><th>Predicted Sentiment</th> <th>Category</th></tr>";

                            foreach ($data as $row) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['text']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['sentiment']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                                echo "</tr>";

                                if ($row['sentiment'] == 'Positive' && $row['category'] == 'quality'){
                                    $qualPos += 1;
                                }
                                else if ($row['sentiment'] == 'Negative' && $row['category'] == 'quality'){
                                    $qualNeg += 1;
                                }
                                else if ($row['sentiment'] == 'Positive' && $row['category'] == 'price'){
                                    $pricePos += 1;
                                }
                                else if ($row['sentiment'] == 'Negative' && $row['category'] == 'price'){
                                    $priceNeg += 1;
                                }
                                else if ($row['sentiment'] == 'Positive' && $row['category'] == 'functionality'){
                                    $funcPos += 1;
                                }
                                else if ($row['sentiment'] == 'Negative' && $row['category'] == 'functionality'){
                                    $funcNeg += 1;
                                }
                                else if ($row['sentiment'] == 'Positive' && $row['category'] == 'others'){
                                    $otherPos += 1;
                                }
                                else if ($row['sentiment'] == 'Negative' && $row['category'] == 'others'){
                                    $otherNeg += 1;
                                }
                            }

                            
                            echo "</table>";

                            $overallNeg = $qualNeg + $funcNeg + $otherNeg + $priceNeg;
                            $overallPos = $qualPos + $funcPos + $otherPos + $pricePos;
                            $funcAll = $funcPos + $funcNeg;
                            $priceAll = $pricePos + $priceNeg;
                            $qualAll = $qualPos + $qualNeg;
                            $otherAll = $otherPos + $otherNeg;

                            // Flag to check if 'date' column exists
                            $hasDateColumn = isset($data[0]['date']);
                            // Flag to track if the date format is valid
                            $correctDateFormat = true; 

                            if ($hasDateColumn) {
                                // Initialize an array to store sentiment counts for each month
                                $sentiment_tally = [];

                                // Loop through the data
                                foreach ($data as $row) {
                                    // Try to parse the date, assuming the expected format is "m/d/Y"
                                    $date = DateTime::createFromFormat('n/j/Y', $row['date']);

                                    if ($date && $date->format('n/j/Y') === $row['date']) {
                                        // Extract the month and year in "mm/YYYY" format
                                        $month = $date->format('n/Y');

                                        // Initialize the array for the month if not already set
                                        if (!isset($sentiment_tally[$month])) {
                                            $sentiment_tally[$month] = ['positive' => 0, 'negative' => 0];
                                        }

                                        // Tally the sentiment based on the 'sentiment' column
                                        if (strtolower($row['sentiment']) === 'positive') {
                                            $sentiment_tally[$month]['positive']++;
                                        } elseif (strtolower($row['sentiment']) === 'negative') {
                                            $sentiment_tally[$month]['negative']++;
                                        }
                                    } else {
                                        // If the date format is incorrect, set the flag to false
                                        $correctDateFormat = false;
                                        break;  // Exit the loop
                                    }
                                }

                                // Prepare data for Chart.js if the date format is correct
                                if ($correctDateFormat) {
                                    $months = array_keys($sentiment_tally);
                                    $positive_data = [];
                                    $negative_data = [];

                                    foreach ($sentiment_tally as $month => $tally) {
                                        $positive_data[] = $tally['positive'];
                                        $negative_data[] = $tally['negative'];
                                    }

                                    // Convert PHP arrays to JSON for use in JavaScript
                                    $months_json = json_encode($months);
                                    $positive_data_json = json_encode($positive_data);
                                    $negative_data_json = json_encode($negative_data);
                                } else {
                                    // If date format is incorrect, pass an empty array and a flag
                                    $months_json = json_encode([]);
                                    $positive_data_json = json_encode([]);
                                    $negative_data_json = json_encode([]);
                                }
                            } else {
                                // If there is no date column, pass empty arrays to JavaScript
                                $months_json = json_encode([]);
                                $positive_data_json = json_encode([]);
                                $negative_data_json = json_encode([]);
                            }

                        } else {
                        
                            echo "Error: Data is not an array.";
                        }
                    } else {
                        echo "Error: Invalid JSON data. " . json_last_error_msg();
                    }

                    // Clear session data
                    unset($_SESSION['file_data']);
                } else {
                    echo "Error: No file data found.";
                }
                ?>
            </div>
            <!-- Small Boxes on the Right -->
            <div class="col-md-6" >
               
                <div class="row">
                     <!-- Top Left-->
                    <div class="col-lg-6 col-md-6" id='topleft'>
                        <canvas id="numTopic" width="100%" height="100%"></canvas>
                    </div>
                    <!-- Top Right -->
                    <div class="col-lg-6 col-md-6" id='topright'>
                        <canvas id="sentimentChart" width="100%" height="100%"></canvas>
                    </div>
                </div>
                <div class="row">
                     <!-- Bottom Left-->
                    <div class="col-lg-6 col-md-6" id='botleft'> 
                        <div id="botleftcanva" style="width: 400px; height: 400px;"></div>
                    </div>
                    <!-- Bottom Right-->
                    <div class="col-lg-6 col-md-6" id='botright'>
                        <?php if (!$hasDateColumn): ?>
                            <p>No data available</p>
                        <?php elseif (!$correctDateFormat): ?>
                            <p>Incorrect Time Format</p>
                        <?php else: ?>
                            <canvas id="lineChart" width="100%" height="100%"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            
           
        </div>
    </div>
</section>
</main>


<script>
    var qualNeg='<?=$qualNeg?>';
    var qualPos='<?=$qualPos?>';
    var qualAll='<?=$qualAll?>';
    var priceNeg='<?=$priceNeg?>';
    var pricePos='<?=$pricePos?>';
    var priceAll='<?=$priceAll?>';
    var funcNeg='<?=$funcNeg?>';
    var funcPos='<?=$funcPos?>';
    var funcAll='<?=$funcAll?>';
    var otherNeg='<?=$otherNeg?>';
    var otherPos='<?=$otherPos?>';
    var otherAll='<?=$otherAll?>';
    var overallPos='<?=$overallPos?>';
    var overallNeg='<?=$overallNeg?>';

    // By Number of each Category
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('numTopic').getContext('2d');

        const data = {
            labels: ['Quality', 'Price', 'Functionality', 'Others'],
            datasets: [{
                label: 'Number of Reviews',
                data: [qualAll, priceAll, funcAll, otherAll],
                backgroundColor: [
                    'rgba(31, 119, 180, 0.7)', // Color for Quality
                    'rgba(255, 127, 14, 0.7)', // Color for Price
                    'rgba(44, 160, 44, 0.7)', // Color for Functionality
                    'rgba(214, 39, 40, 0.7)'  // Color for Others
                ],
                borderColor: [
                    'rgba(31, 119, 180, 1)',
                    'rgba(255, 127, 14, 1)',
                    'rgba(44, 160, 44, 1)',
                    'rgba(214, 39, 40, 1)'
                ],
                borderWidth: 1,
                borderRadius: 10 // Rounded corners
            }]
        };

        const options = {
            indexAxis: 'y', // Makes the chart horizontal
            scales: {
                x: {
                    beginAtZero: true,
                    
                    grid: {
                        display: false // Remove grid lines on the x-axis
                    },
                    ticks: {
                        display: false // Display x-axis labels
                    },
                    border: {
                        display: false // Remove x-axis line
                    }
                },
                y: {
           
                    grid: {
                        display: false // Remove grid lines on the y-axis
                    },
                    border: {
                        display: false // Remove y-axis line
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Number of Reviews by Category',
                    align: 'start', // Align title to the start (top-left)
                    font: {
                        size: 16, // Font size for the title
                        weight: 'bold' // Font weight for the title
                    },
                    padding: {
                        top: 10, // Padding from the top
                        bottom: 10 // Padding from the bottom
                    }
                },
                legend: {
                    display: false // Disable the legend
                },
                datalabels: {
                    color: '#ffffff', // White text color
                    anchor: 'center', // Positions the label outside the bar
                    align: 'center', // Aligns the label to the end of the bar
                    font: {
                        size: 20, // Font size for the data labels
                        weight: 'bold' // Make the text bold
                    },
                    formatter: (value) => {
                        return value; // Display the data value
                    }
                }
            }
        };

        const numTopic = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options,
            plugins: [ChartDataLabels] // Register the DataLabels plugin
        });
    });

    //Sentiment by Topic
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('sentimentChart').getContext('2d');

        const data = {
            labels: ['Quality', 'Price', 'Functionality', 'Others'], // Categories
            datasets: [
                {
                    label: 'Positive Sentiments',
                    data: [qualPos, pricePos, funcPos, otherPos],
                    backgroundColor: 'rgba(	40, 164, 40, 0.7)',
                    borderColor: 'rgba(	40, 164, 40, 1)',
                    borderWidth: 1,
                    borderRadius: 10, 
                },
                {
                    label: 'Negative Sentiments',
                    data: [qualNeg, priceNeg, funcNeg, otherNeg],
                    backgroundColor: 'rgba(223, 21, 41, 0.7)',
                    borderColor: 'rgba(223, 21, 41, 1)',
                    borderWidth: 1,
                    borderRadius: 10,
                }
            ]
        };

        const options = {
            indexAxis: 'y', 
            scales: {
                x: {
                    beginAtZero: true,
                    stacked: true, 
                    grid: {
                        display: false 
                    },
                    ticks: {
                        display: false
                    },
                    border: {
                        display: false 
                    }
                },
                y: {
                    stacked: true, 
                    grid: {
                        display: false 
                    },
                    border: {
                        display: false 
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Sentiment by Topic',
                    align: 'start', 
                    font: {
                        size: 16, 
                        weight: 'bold' 
                    },
                    padding: {
                        top: 10, 
                        bottom: 10
                    }
                },
                legend: {
                    display: false
                },
                datalabels: {
                    color: '#ffffff', 
                    anchor: 'center', 
                    align: 'center', 
                    font: {
                        size: 20,
                        weight: 'bold' 
                    },
                    formatter: (value, context) => {
                        return value;
                    }
                }
            }
        };

        const sentimentChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options,
            plugins: [ChartDataLabels]
        });
    });

    // Overall Sentiments Chart
    var chartDom = document.getElementById('botleftcanva');
        var myChart = echarts.init(chartDom);
        var option;

        // Define chart options
        option = {
            title: {
                text: 'Overall Sentiment',
                left: 'center',
                top: 'top',
                textStyle: {
                    fontSize: 20,
                    fontWeight: 'bold'
                }
            },
            tooltip: {
                trigger: 'item'
            },
            series: [
                {
                    name: 'Sentiments',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: 20,
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    itemStyle: {
                        color: function(params) {
                            // Return color based on the data index
                            return params.dataIndex === 0 ? 'rgba(40, 164, 40, 0.7)' : 'rgba(223, 21, 41, 0.7)';
                        }
                    },
                    data: [
                        { value: overallPos, name: 'Positive' },
                        { value: overallNeg, name: 'Negative' }
                    ]
                }
            ]
        };

        // Set the chart options
        myChart.setOption(option);

        // Time Chart
        const months = <?php echo $months_json; ?>;
        const positiveData = <?php echo $positive_data_json; ?>;
        const negativeData = <?php echo $negative_data_json; ?>;

        // Check if there is data to display
        if (months.length > 0) {
            // Create the chart if there is data
            const ctx = document.getElementById('lineChart').getContext('2d');
            const sentimentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,  // X-axis labels (months)
                    datasets: [
                        {
                            label: 'Positive Sentiment',
                            data: positiveData,  // Data points for positive sentiment
                            borderColor: 'rgba(75, 192, 192, 1)',  // Line color
                            fill: false,
                            tension: 0.1
                        },
                        {
                            label: 'Negative Sentiment',
                            data: negativeData,  // Data points for negative sentiment
                            borderColor: 'rgba(255, 99, 132, 1)',  // Line color
                            fill: false,
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false  // Disable the legend
                        },
                        title: {
                            display: true,
                            text: 'Monthly Sentiment Analysis'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: false,
                                text: 'Month'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: false,
                                text: 'Sentiment Count'
                            }
                        }
                    }
                }
            });
        } else {
            // Display an alert if no data is available (in case canvas exists but no data)
            console.log("No data available to display in the chart or Incorrect Time Format.");
        }

    //Change based on resolution 
    function updateClassBasedOnResolution() {
            const element = document.getElementById('csvTable');
            const element2 = document.getElementById('topleft');
            const element3 = document.getElementById('topright');
            const element4 = document.getElementById('botleft');
            const element5 = document.getElementById('botright');
            if (window.innerWidth < 1600) { // Apply new class name for small screens
                element.className = 'col-lg-12';
                element2.className = 'col-lg-12 col-md-12'; 
                element3.className = 'col-lg-12 col-md-12'; 
                element4.className = 'col-lg-12 col-md-12'; 
                element5.className = 'col-lg-12 col-md-12'; 
            } else { // Apply default class name for larger screens
                element.className = 'col-lg-5'; 
                element2.className = 'col-lg-6 col-md-6'; 
                element3.className = 'col-lg-6 col-md-6'; 
                element4.className = 'col-lg-6 col-md-6'; 
                element5.className = 'col-lg-6 col-md-6'; 
            }
        }

        // Initial check
        updateClassBasedOnResolution();

        // Check on window resize
        window.addEventListener('resize', updateClassBasedOnResolution);
</script>
<?php
include('footer.php');
?>
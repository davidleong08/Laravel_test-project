{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seasonal Soup Recommendation System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Shared Custom CSS -->

<style>
    .card-img-top {
  height: 200px; /* 设置固定高度 */
  object-fit: cover; /* 裁剪并覆盖容器 */
}
.soup-image {
  min-width: 100%; /* 确保图像至少与容器同宽 */
  min-height: 200px; /* 确保图像至少有200px高 */
  object-fit: cover;
  object-position: center;
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        {{-- 其他导航链接... --}}
        <div class="d-flex">
        <div id="weather-info" class="mr-auto">
        Temperature: <span id="temp"></span> | Humidity:<span id="humidity"></span> | Location: <span id="location">Macau</span>
            </div>
            @if(Auth::check())
            <a href="{{ route('bodycondition.index') }}" class="btn btn-outline-info mr-2">Edit Profile</a>
            <form action="{{ url('/logout') }}" method="POST">
        @csrf <!-- 保护 CSRF 攻击 -->
        <button type="submit" class="btn btn-outline-success">Logout</button>
    </form>
            @else
                <a href="{{ url('/login') }}" class="btn btn-outline-primary">Login</a>
                <a href="{{ url('/register') }}" class="btn btn-outline-secondary ml-2">Register</a> <!-- 新增的注册按钮 -->
            @endif
        </div>
    </div>
</nav>
    <div class="text-center mt-4">
        <h1>Seasonal Soup Recommendations</h1>
        <!-- 显示当前日期 -->
        <p id="current-date" class="font-weight-bold"></p>
        <p id="recommendation" style="color: blue;"></p>
        <!-- 季节筛选按钮 -->
        <div class="btn-group" role="group" aria-label="Seasonal Soup Selection">
            <button type="button" class="btn btn-primary" onclick="filterSoups('spring')">Spring</button>
            <button type="button" class="btn btn-success" onclick="filterSoups('summer')">Summer</button>
            <button type="button" class="btn btn-warning" onclick="filterSoups('autumn')">Autumn</button>
            <button type="button" class="btn btn-danger" onclick="filterSoups('winter')">Winter</button>
            <button type="button" class="btn btn-secondary" onclick="filterSoups('allseason')">All Seasons</button>
        </div>
    </div>
    <div class="container mt-4">
        <div id="soup-list" class="row">
            <!-- soup 对象现在有一个 season 字段 -->
            @foreach($soups as $soup)
    <div class="col-md-4 soup-item" data-season="{{ strtolower($soup->season) }}">
        <div class="card mb-4">
            <!-- 添加链接到图片和名字 -->
            <div class="image-container">
            <a href="{{ route('soup.show', $soup->id) }}">
                <img src="{{ $soup->image_url }}" alt="{{ $soup->name }}" class="card-img-top img-fluid soup-image">
            </a>
</div>
            <div class="card-body">
                <!-- 添加链接到名字 -->
                <h5 class="card-title">
                    <a href="{{ route('soup.show', $soup->id) }}">{{ $soup->name }}</a>
                </h5>

            </div>
        </div>
    </div>
@endforeach
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first (use the full version, not the slim version), then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.7.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
        // Set the current date and filter soups based on the current season
        var now = new Date();
        var currentDateElement = document.getElementById('current-date');
        currentDateElement.textContent = now.toISOString().split('T')[0];
        filterSoups('');
            fetchWeatherData();
        });

        function fetchWeatherData() {
    var url = 'http://soup.test_project/weather/macao';
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var temperature = data.temp; // Get temperature
            var humidity = data.humidity; // Get humidity

            // Format temperature string
            var temperatureString = temperature + '°C';
            if (temperature > 30) {
                temperatureString += ' (High temperature)';
            } else if (temperature < 15) {
                temperatureString += ' (Low temperature)';
            }

            // Update temperature element
            var tempElement = $('#temp');
            tempElement.text(temperatureString);
            if (temperature > 30) {
                tempElement.addClass('text-danger');
            } else if (temperature < 15) {
                tempElement.addClass('text-primary');
            }

            // Format humidity string
            var humidityString = humidity + '%';
            if (humidity > 70) {
                humidityString += ' (High humidity)';
                showRecommendation('Warming Remind: Better choose removes dampness soups.');
            } else if (humidity < 30) {
                humidityString += ' (Low humidity)';
                showRecommendation('Warming Remind: Better choose moisturizes dryness soups.');
            }

            // Update humidity element
            var humidityElement = $('#humidity');
            humidityElement.text(humidityString);
            if (humidity > 70) {
                humidityElement.addClass('text-danger');
            } else if (humidity < 30) {
                humidityElement.addClass('text-primary');
            }
        },
        error: function (error) {
            console.error("Unable to fetch weather data:", error);
        }
    });
}

    function showRecommendation(message) {
        var recommendationElement = $('#recommendation');
        recommendationElement.text(message);
    }

        // Filter soups based on the selected season
        function filterSoups(season) {
    // Send AJAX request to the server
    $.ajax({
        url: '{{ url('/') }}', // Adjust this if your URL structure is different
        type: 'GET',
        data: { season: season },
        success: function(soups) {
            var soupList = $('#soup-list');
            soupList.empty(); // Clear the current soups

            // Loop through the soups and append them to the list
            soups.forEach(function(soup) {
                var soupLink = '/soup/' + soup.id;
        var soupHtml = '<div class="col-md-4 soup-item" data-season="' + soup.season.toLowerCase() + '">' +
            '<div class="card mb-4">' +
            // 添加链接到图片和名字
            '<a href="' + soupLink + '">' +
            '<img src="' + soup.image_url + '" alt="' + soup.name + '" class="card-img-top img-fluid soup-image">' +
            '</a>' +
            '<div class="card-body">' +
            // 添加链接到名字
            '<h5 class="card-title">' +
            '<a href="' + soupLink + '">' + soup.name + '</a>' +
            '</h5>' +
            // 如果需要其他内容可以在这里添加
            '</div>' +
            '</div>' +
            '</div>';
                soupList.append(soupHtml);
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Set the current date
    var now = new Date();
    var currentDateElement = document.getElementById('current-date');
    currentDateElement.textContent = now.toISOString().split('T')[0];

    // Initially display soups for the current season
    filterSoups(''); // This will pass an empty string, which the server treats as the current season
});
    </script>
</body>
</html>

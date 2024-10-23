import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["time", "temperature", "description", "weatherIcon", "forecastTemp", "forecastIcon", "forecastDayName"];

    connect() {
        this.updateTime();
        this.checkWeatherData();
        setInterval(() => this.updateTime(), 1000); // Update time every second
    }

    updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('de-DE', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.querySelector('.time').textContent = timeString;
    }

    checkWeatherData() {
        const storedWeather = localStorage.getItem('weatherData');
        const lastFetch = localStorage.getItem('lastFetchTime');

        const now = new Date().getTime();
        const thirtyMinutes = 30 * 60 * 1000;

        if (storedWeather && lastFetch && (now - lastFetch < thirtyMinutes)) {
            this.updateWeatherDisplay(JSON.parse(storedWeather));
        } else {
            this.fetchWeather();
        }
    }

    fetchWeather() {
        const apiKey = openWeatherApiKey;
        const city = "Köln";
        const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&units=metric&appid=${apiKey}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                localStorage.setItem('weatherData', JSON.stringify(data));
                localStorage.setItem('lastFetchTime', new Date().getTime());

                this.updateWeatherDisplay(data);
            })
            .catch(error => console.log(error));
    }

    updateWeatherDisplay(data) {
        const currentWeather = data.list[0];
        const sunrise = data.city.sunrise * 1000; // Convert to milliseconds
        const sunset = data.city.sunset * 1000;
        const now = new Date().getTime();

        // Change background based on day or night
        if (now >= sunrise && now <= sunset) {
            document.body.classList.add('day-mode');
            document.body.classList.remove('night-mode');
        } else {
            document.body.classList.add('night-mode');
            document.body.classList.remove('day-mode');
        }

        // Current weather
        document.querySelector('.temperature').textContent = `${Math.round(currentWeather.main.temp)}°C`;
        document.querySelector('.weather-icon').src = `http://openweathermap.org/img/wn/${currentWeather.weather[0].icon}@2x.png`;

        // 3-day forecast with weekday names
        const forecastDays = [8, 16, 24]; // Assuming 3-hour intervals, pick one value per day
        forecastDays.forEach((index, i) => {
            const forecast = data.list[index];
            const forecastDate = new Date(forecast.dt * 1000); // Convert UNIX timestamp to date
            const weekday = forecastDate.toLocaleDateString('de-DE', { weekday: 'short' }); // Get abbreviated weekday name

            document.querySelectorAll('.forecast-day h4')[i].textContent = weekday; // Update weekday name
            document.querySelectorAll('.forecast-temp')[i].textContent = `${Math.round(forecast.main.temp)}°C`;
            document.querySelectorAll('.forecast-icon')[i].src = `http://openweathermap.org/img/wn/${forecast.weather[0].icon}@2x.png`;
        });
    }
}

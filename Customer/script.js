document.addEventListener("DOMContentLoaded", function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success, error);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
});

function success(position) {
    const userLat = position.coords.latitude;
    const userLng = position.coords.longitude;

    alert(`User Location: ${userLat}, ${userLng}`); // Debugging

    // Call backend to fetch nearby messes
    fetch(`get_messes.php?lat=${userLat}&lng=${userLng}`)
        .then(response => response.json())
        .then(data => {
            displayMesses(data, userLat, userLng);
        })
        .catch(error => console.error("Error fetching mess data:", error));
}

function error() {
    alert("Unable to retrieve your location. Please check your network connection.");
}

document.addEventListener("DOMContentLoaded", function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success, error);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
});

function success(position) {
    const userLat = position.coords.latitude;
    const userLng = position.coords.longitude;

    fetch(`get_messes.php?lat=${userLat}&lng=${userLng}`)
        .then(response => response.json())
        .then(data => {
            displayMesses(data, userLat, userLng);
        })
        .catch(error => console.error("Error fetching mess data:", error));
}

function error() {
    alert("Unable to retrieve your location. Please check your network connection.");
}

function displayMesses(messes, userLat, userLng) {
    const content = document.querySelector(".content");
    content.innerHTML = `<h2>Nearby Messes</h2><div class="mess-container"></div>`;

    const messContainer = document.querySelector(".mess-container");

    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth’s radius in km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // Distance in km
    }

    const nearbyMesses = messes.filter(mess => {
        let messLat, messLng; // Ensure these variables are defined inside the loop

        if (mess.messlat && mess.messlng) {
            messLat = parseFloat(mess.messlat);
            messLng = parseFloat(mess.messlng);
        } else if (mess.messgmap) {
            const coords = mess.messgmap.split(",");
            if (coords.length === 2) {
                messLat = parseFloat(coords[0].trim());
                messLng = parseFloat(coords[1].trim());
            }
        }

        if (isNaN(messLat) || isNaN(messLng)) {
            console.warn(`Skipping ${mess.messname}, invalid coordinates.`);
            return false;
        }

        const distance = getDistance(userLat, userLng, messLat, messLng);
        console.log(`Mess: ${mess.messname}, Distance: ${distance} km`);

        return distance <= 1;
    });

    messContainer.innerHTML = "";
    if (nearbyMesses.length === 0) {
        messContainer.innerHTML = `<p>No messes found within 1 km.</p>`;
        return;
    }

    nearbyMesses.forEach(mess => {
        let messLat, messLng;

        if (mess.messlat && mess.messlng) {
            messLat = parseFloat(mess.messlat);
            messLng = parseFloat(mess.messlng);
        } else if (mess.messgmap) {
            const coords = mess.messgmap.split(",");
            if (coords.length === 2) {
                messLat = parseFloat(coords[0].trim());
                messLng = parseFloat(coords[1].trim());
            }
        }

        const messCard = document.createElement("div");
        messCard.classList.add("mess-card");
        messCard.innerHTML = `
            <img src="${mess.messimage}" alt="${mess.messname}">
            <h3>${mess.messname}</h3>
            <p>Type: ${mess.messtype}</p>
            <p>Contact: ${mess.messcontact}</p>
            <p>Location: ${mess.messlocation}</p>
            <a href="https://www.google.com/maps?q=${messLat},${messLng}" target="_blank">View on Map</a>
            <a href="todays_menu.php?mId=${mess.messid}&cId=${cId}">View Today's Menu</a>
        `;
        messContainer.appendChild(messCard);
    });
}



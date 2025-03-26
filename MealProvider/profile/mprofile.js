function openGoogleMaps() {
    let locationInput = document.getElementById("mlocation").value.trim();

    if (locationInput) {
        // If user already entered coordinates, open Google Maps with them
        let [lat, lng] = locationInput.split(",");
        let gmapUrl = `https://www.google.com/maps?q=${lat.trim()},${lng.trim()}`;
        window.open(gmapUrl, "_blank");
    } else {
        // If no manual input, fetch live location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(success, error);
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    }
}

function success(position) {
    let latitude = position.coords.latitude;
    let longitude = position.coords.longitude;
    let gmapUrl = `https://www.google.com/maps?q=${latitude},${longitude}`;
    
    // Update the textarea with location coordinates
    document.getElementById("mlocation").value = `${latitude}, ${longitude}`;
    
    // Redirect to Google Maps
    window.open(gmapUrl, "_blank");
}

function error() {
    alert("Unable to retrieve your location. Please enable location services.");
}





document.getElementById("myForm").addEventListener("submit", function(event) {
    // Owner details
    let fname = document.getElementById("fname").value.trim();
    let lname = document.getElementById("lname").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let password = document.getElementById("password").value.trim();

    // Mess details
    let mname = document.getElementById("mname").value.trim();
    let mcontact = document.getElementById("mcontact").value.trim();
    let marea = document.getElementById("marea").value.trim();
    let mlocation = document.getElementById("mlocation").value.trim();
    let messimage = document.getElementById("messimage").value.trim();
    let messcard = document.getElementById("messcard").value.trim();

    // Check if ANY mess field (except mtype) is entered
    let isMessDataEntered = mname || mcontact || marea || mlocation || messimage || messcard;

    if (isMessDataEntered) {
        // If ANY mess field is entered, all must be filled
        if (!mname || !mcontact || !marea || !mlocation || !messimage || !messcard) {
            alert("Please fill all Mess details if you want to update Mess information.");
            event.preventDefault(); // Stop form submission
            return;
        }
    }
    
    // If no mess details are entered, allow form submission
});


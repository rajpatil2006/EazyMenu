
function register(event) {
    var form = document.getElementById('myForm');
    var fname = form.elements['fname'].value.trim(); // Trim whitespace
    var lname = form.elements['lname'].value.trim(); // Trim whitespace
    var email = form.elements['email'].value.trim(); // Trim whitespace
    var password = form.elements['password'].value;
   

     // Convert the first letter of firstname and lastname to uppercase
     fname = fname.charAt(0).toUpperCase() + fname.slice(1).toLowerCase();
     lname = lname.charAt(0).toUpperCase() + lname.slice(1).toLowerCase();
    

    var isConfirmed = confirm('Name: ' + fname + ' ' + lname +
        '\nEmail ' + email + 
        '\nPassword: ' + password + '\n\nDo you want to submit the form?');

    if (!isConfirmed) {
        // Prevent the form from submitting if the user clicks "Cancel"
        event.preventDefault();
    }
}


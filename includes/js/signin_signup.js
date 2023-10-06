function toggleForm() {
    var container = document.querySelector('.container');
    var section = document.querySelector('section');
    container.classList.toggle('active');
    section.classList.toggle('active');
}


// ẩn hiện mật khẩu sign in
const input_password = document.querySelector(".input_password");
const eyeOpen = document.querySelector(".open-eye");
const eyeClose = document.querySelector(".close-eye");
eyeOpen.addEventListener("click", function() {
    eyeOpen.classList.add("hidden");
    eyeClose.classList.remove("hidden");
    input_password.setAttribute("type", "password");
});
eyeClose.addEventListener("click", function() {
    eyeOpen.classList.remove("hidden");
    eyeClose.classList.add("hidden");
    input_password.setAttribute("type", "text");
});
// ẩn hiện mật khẩu logn sign up
// create pass
const input_create_pass = document.querySelector(".input_create_password");
const eyeOpen_of_createPass = document.querySelector(".open-eye_of_create_pass");
const eyeClose_of_createPass = document.querySelector(".close-eye_of_create_pass");
eyeOpen_of_createPass.addEventListener("click", function() {
    eyeOpen_of_createPass.classList.add("hidden");
    eyeClose_of_createPass.classList.remove("hidden");
    input_create_pass.setAttribute("type", "password");
});
eyeClose_of_createPass.addEventListener("click", function() {
    eyeOpen_of_createPass.classList.remove("hidden");
    eyeClose_of_createPass.classList.add("hidden");
    input_create_pass.setAttribute("type", "text");
});
// confirm pass
const input_confirm_pass = document.querySelector(".input_confirm_password");
const eyeOpen_of_confirmPass = document.querySelector(".open-eye_of_confirm_pass");
const eyeClose_of_confirmPass = document.querySelector(".close-eye_of_confirm_pass");
eyeOpen_of_confirmPass.addEventListener("click", function() {
    eyeOpen_of_confirmPass.classList.add("hidden");
    eyeClose_of_confirmPass.classList.remove("hidden");
    input_confirm_pass.setAttribute("type", "password");
});
eyeClose_of_confirmPass.addEventListener("click", function() {
    eyeOpen_of_confirmPass.classList.remove("hidden");
    eyeClose_of_confirmPass.classList.add("hidden");
    input_confirm_pass.setAttribute("type", "text");
});
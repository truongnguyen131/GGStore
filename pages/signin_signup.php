<?php
session_start();
include_once('../mod/database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign IN_UP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../includes/css/signin_signup.css">
</head>

<body>
    <div class="circles">
        <img class="line" src="../images/" alt="">
        <img class="just_chart" src="../images/just_gamers_chart.png" alt="">
        <img class="circle-1" src="../images/gamers_circle_shape.png" alt="">
        <img class="circle-2" src="../images/slider_circle.png" alt="">
    </div>
    <section>
        <div class="container">
            <div class="flip">
                <!-- login form -->
                <div class="user signinBx">
                    <div class="imgBx"><img src="../images/four_slider_img01.png" alt=""></div>
                    <div class="formBx">
                        <form action="">
                            <h2>Sign In</h2>
                            <input class="input is-invalid" type="text" name="" id="" placeholder="Username">
                            
                            <div class="password">
                                <input class="input input_password" type="password" placeholder="Password">
                                <i class="fa-solid fa-eye eye open-eye hidden" id="open_eye"></i>
                                <i class="fa-solid fa-eye-slash close-eye eye" id="close_eye"></i>
                            </div>
                            
                            <label class="remember_account" for="remember">
                                Remember Account
                                <input type="checkbox" name="checkbox" id="remember"/>
                              </label>
                            <a href="" class="submit">Submit</a>
                            <p class="signup">
                                Don't have an account?
                                <a href="#" onclick="toggleForm();">Sign up</a>
                            </p>
                        </form>
                    </div>
                </div>
                <!-- register form -->
                <div class="user signupBx">
                    <div class="formBx">
                        <form action="">
                            <h2>Sign Up</h2>
                            <input class="input" type="text" placeholder="Full Name">
                            
                            <input class="input" type="text" placeholder="Email">
                            
                            <input class="input" type="number" placeholder="Number Phone">
                            
                            <input class="input" type="text" placeholder="Username">
                            
                            <div class="password">
                                <input class="input input_create_password" type="password" placeholder="Create Password">
                                <i class="fa-solid fa-eye eye open-eye_of_create_pass hidden"></i>
                                <i class="fa-solid fa-eye-slash close-eye_of_create_pass eye"></i>
                            </div>
                            
                            <div class="password">
                                <input class="input input_confirm_password" type="password" placeholder="Confirm Password">
                                <i class="fa-solid fa-eye eye open-eye_of_confirm_pass hidden"></i>
                                <i class="fa-solid fa-eye-slash close-eye_of_confirm_pass eye"></i>
                            </div>
                            
                            <a href="" class="submit">Submit</a>
                            <p class="signup">
                                Already have an account?
                                <a href="#" onclick="toggleForm();">Sign in.</a>
                            </p>
                        </form>
                    </div>
                    <div class="imgBx">
                        <img src="../images/four_slider_img01.png" alt="">
                    </div>
                </div>
            </div>
        </div>`
    </section>
</body>
<script src="../includes/js/jquery.min.js"></script>
<script src="../includes/js/signin_signup.js"></script>

</html>
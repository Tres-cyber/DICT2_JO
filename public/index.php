<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/vite.php';

?>
<!DOCTYPE html>
<html lang="">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="icon" href="favicon.ico">
    <?= vite('main.ts') ?>
    <title>DICT JO</title>
  </head>
  <body>
    <noscript>
      <strong>We're sorry but DICT JO doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app">
       <div class="login-container">
        <div class="login-logo-wrapper">
          <img src="assets/logo.png" alt="" class="login-logo">
        </div>
       <div class="login-wrapper-2">
       <img src="assets/logo.png" alt="" class="login-logo-two">
       <div class="login-box">
          <h1 class="login-title">login</h1>
          <form action="">
            <input class="input-wrapper" type="text" placeholder="Email">
            <input class="input-wrapper" type="text" placeholder="Password">
            <input class="input-submit self-center" type="submit" value="Sign In">
          </form>
        </div>
       </div>
       </div>
      </div>
        
  </body>
</html>

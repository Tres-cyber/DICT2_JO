<!DOCTYPE html>
<html lang="">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="icon" href="<%= BASE_URL %>favicon.ico">
    <title><%= htmlWebpackPlugin.options.title %></title>
  </head>
  <body>
    <noscript>
      <strong>We're sorry but <%= htmlWebpackPlugin.options.title %> doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app">
       <div class="login-container">
        <div class="login-logo-wrapper">
          <img src="assets/logo.png" alt="" class="login-logo">
        </div>
       <div class="login-wrapper-2">
       <div class="login-box">
          <h1 class="login-title">login</h1>
          <form action="">
            <label class="input-title" for="">Email</label>
            <input class="input-wrapper" type="text">
            <label class="input-title" for="">Password</label>
            <input class="input-wrapper" type="text">
            <div class="input-submit-wrapper">
            <input class="input-submit" type="submit" value="Submit">
            </div>
          </form>
        </div>
       </div>
       </div>
      </div>
        
  </body>
</html>

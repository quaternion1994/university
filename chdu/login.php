<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Вхід в систему ЧНУ</title>

    <?php include_once './partials/bootstrap-css.php' ?>
    <?php include_once './partials/bootstrap-js.php' ?>
    <?php require_once("./auth.php"); ?>
    
    <link href="/css/signin.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">
        <form class="form-signin" method="POST" action='./signin-post.php'>
        <h2 class="form-signin-heading">Вхід в систему ЧНУ</h2>
        <?php if(Auth::IsLogedin()){
            echo 'Ви вже зайшли в систему!';
        }
        ?>
        <label for="inputEmail" class="sr-only">Email</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email або логін" required autofocus>
        <label for="inputPassword" class="sr-only">Пароль</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Пароль" required>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Запам'ятати пароль
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Увійти</button>
      </form>

    </div>
  </body>
</html>

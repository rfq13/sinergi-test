<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Harman cardon</title>
</head>
<body>
     <span><?= $angka; ?></span>
     <form action="" method="post">
          <label for="angka"></label>
          <input type="number" min="1" name="angka" id="angka">
          <button type="submit">submit</button>
          <br>
          <br>
          <ol>
               <?php if (isset($results)){ 
                    foreach ($results as $key => $value) { ?>
                    <li><?= $value['result'] ?></li>
               <?php }} ?>
          </ol>
     </form>
</body>
</html>
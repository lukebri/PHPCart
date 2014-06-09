       <section class="col-lg-3 col-sm-3 col-xs-12">
        <p class="col_title"><?php echo $product{'name'}; ?></p>
        <b>$<?php echo $product{'price'}; ?></b>
        <p>
          <img class="prodgallery" src="<?php echo 'img/upload/' . $product{'img'}; ?>"><br>
          <?php
          $string = htmlentities($product{'description'});

          if (strlen($string) > 30) {

    // if description > 30 chars, cut....
            $stringCut = substr($string, 0, 30);

    // make sure to end after word, not a letter
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')). ' ...';
          }
          echo $string;
          ?>
        </p>
        <a href="<?php echo 'product.php?id=' . $product{'id'}; ?>">
          <button class="buttonmain">View</button>
        </a>
      </section>
 <?php
    include('navbar.php');
     $connect=mysqli_connect("localhost","root","","the_bottle_database");
    $product_whiskey="SELECT * FROM product WHERE product_type='Whiskey'";
    $product_whiskey_query=mysqli_query($connect,$product_whiskey);
    $product_whiskey_count=mysqli_num_rows($product_whiskey_query);

    $product_beer="SELECT * FROM product WHERE product_type='Beer'";
    $product_beer_query=mysqli_query($connect,$product_beer);
    $product_beer_count=mysqli_num_rows($product_beer_query);
         
    $product_wine="SELECT * FROM product WHERE product_type='Wine'";
    $product_wine_query=mysqli_query($connect,$product_wine);
    $product_wine_count=mysqli_num_rows($product_wine_query);

    $product_gin="SELECT * FROM product WHERE product_type='Gin'";
    $product_gin_query=mysqli_query($connect,$product_gin);
    $product_gin_count=mysqli_num_rows($product_gin_query);

    $product_brandy="SELECT * FROM product WHERE product_type='Brandy'";
    $product_brandy_query=mysqli_query($connect,$product_brandy);
    $product_brandy_count=mysqli_num_rows($product_brandy_query);

    $product_rum="SELECT * FROM product WHERE product_type='Rum'";
    $product_rum_query=mysqli_query($connect,$product_rum);
    $product_rum_count=mysqli_num_rows($product_rum_query);

    $product_Tequila="SELECT * FROM product WHERE product_type='Tequila'";
    $product_Tequila_query=mysqli_query($connect,$product_Tequila);
    $product_Tequila_count=mysqli_num_rows($product_Tequila_query);

    $product_vodka="SELECT * FROM product WHERE product_type='Vodka'";
    $product_vodka_query=mysqli_query($connect,$product_vodka);
    $product_vodka_count=mysqli_num_rows($product_vodka_query);

        
  
     
    ?>

    <!DOCTYPE html>
    <html>
    <head>
       <meta charset="utf-8">
       <title></title>
    </head>
    <body>
      <br>
      <br>
      <br>
      <div class="whiskey">
         
      </div>
      <style type="text/css">
       
         .whiskey{
            width: 20px;
            height:200px;
            padding-top: 10px;
            border: 1px solid black;
         }
      </style>
    </body>
    </html>
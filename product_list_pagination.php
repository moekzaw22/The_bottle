$itemperpage = 60;
if (isset($_GET["page"])) {    
    $page = $_GET["page"];    
}
else { 
    $page = 1;    
}  
echo "<div class='pagination'>";
			if ($page == 1 && $lastpage != 1) {
				echo "<a class='button'><i class='fas fa-angle-left'></i></a>";
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";
?>
					<a href="product_list.php?page=<?php echo $addpage ?>"><i class="fas fa-angle-right"></i></a>
				<?php
			}
			elseif ($lastpage <= 1 ) {
				echo "<a class='button'><i class='fas fa-angle-left'></i></a>";
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";
				echo "<a class='button'><i class='fas fa-angle-right'></i></a>";}
			elseif($page > 1 && $page < $lastpage){?>
			<a href="product_list.php?page=<?php echo $subpage ?>"><i class='fas fa-angle-left'></i></a>
	<?php
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";
				?>
				<a href="product_list.php?page=<?php echo $addpage ?>"><i class="fas fa-angle-right"></i></a>
				<?php
			}
			elseif($page == $lastpage){
				?>
				<a href="product_list.php?page=<?php echo $subpage ?>"><i class='fas fa-angle-left'></i></a>

				<?php
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";
				?>
				<?php
				echo "<a class='button'><i class='fas fa-angle-right'></i></a>";
			}
			echo "</div>";
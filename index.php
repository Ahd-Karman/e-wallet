<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();

$_SESSION['balance'] = 2000;
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	
	case "buy":
		if(!empty($_SESSION["cart_item"])) {
			buyingModal();
		}
		
	break;	
}
}

function buyingModal(){
	echo "
	<style>
		#modal{
			z-index: 10;
			display: flex;
			flex-wrap: wrap;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			position: absolute;
			top: 0;
			left: 0.1px;
			justify-content: center;
			align-items: center;
		}

		.login {
			background-color: white;
			display: flex;
			flex-wrap: wrap;
			width: 30%;
			height: 50%;
			flex-wrap: wrap;
			border-radius: 10px ;
		}
		
		.login form {
			display: flex;
			flex-wrap: wrap;
			text-align: center;
			margin: 20px 50px;
		}
		
		.login input , finput{
			display: flex;
			margin: 5px ;
			width: 20vw;
			padding: 10px;
			border: 0.5px solid lightgrey;
			border-radius: 5px;
			background-color: whitesmoke;
		}
		
		
		
		.main{
			display: flex;
			width: 20vw;
			padding: 5px 0 5px 100px;
			color: oldlace;
			border: 0.5px solid;
			border-radius: 5px;
			background-color: steelblue;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			margin-top:20px;
			font-size: 18px;
			text-align: center;
		}
		
		
		.login h1 {
			text-align: center;
			color: black;
			margin-left:30px;
			margin-bottom: -1px;
		}

	</style>

	<div id='modal'>
		<div class='login'>
			<div>
				<form>
				<h1> Confirm Buying </h1>
				<div style='margin-bottom:10px;'> 
					<h4 style='margin-left:10px; text-align:left; color:#42526e' > Your Balance in wallet is: <span style='color:steelblue;'>".$_SESSION['balance']."$"." </span> </h4>
					<h4 style='margin-left:10px; margin-top:-5px; text-align:left; color:#42526e' > The total of your items is: <span style='color:steelblue;'>".$_SESSION['total']."$"." </span> </h4>
				</div>

				<label style='margin-left:10px;'> Enter your email to confirm buying: </label>
					<input type='email' placeholder='Email' style='margin-left:10px;' name='email' />

					<button class='main' style='margin-left:10px;' name='confirm'> Confirm </button>
				</form>
			</div>". 
			if (isset($_POST['confirm'])) {
				$email= $_POST['email'];

				if ($email == NULL) 
					echo " <i class='alert alert-danger'  align='center'>  Email Can Not Be NULL ! </i> " ; 
			}
			
			."

		</div>
	</div>

	<script>
		var modal = document.getElementById('modal');
		var showBTN  = document.getElementById('btnBuy') ;

		function exit() {
			modal.style.visibility = 'hidden';
		}
	</script>
	"
	;
}
?>
<HTML>
<HEAD>
<TITLE>Simple PHP Shopping Cart</TITLE>
<link href="cartStyle.css" type="text/css" rel="stylesheet" />
</HEAD>
<BODY>
<div id="shopping-cart">

<div class="txt-heading" >
<h1>
	Welcome <i> <?php echo $_SESSION['username']; ?>  </i> !
	</h1>
	<div class="header">
                <div class="nav">
                <ul>
					<li><a href="logout.php"> <b> Logout </b> </a></li>  
                    <li> Your Balance is : <b> <?php echo $_SESSION['balance']."$" ; ?> </b> </li>
       
                </ul>
               </div>
    </div> 
</div>



<a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>
<a id="btnBuy" href="index.php?action=buy">Buy</a>
<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	
<table class="tbl-cart" cellpadding="10" cellspacing="1">
<tbody>
<tr>
<th style="text-align:left;">Name</th>
<th style="text-align:left;">Code</th>
<th style="text-align:right;" width="5%">Quantity</th>
<th style="text-align:right;" width="10%">Unit Price</th>
<th style="text-align:right;" width="10%">Price</th>
<th style="text-align:center;" width="5%">Remove</th>
</tr>	
<?php		
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		?>
				<tr>
				<td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
				<td><?php echo $item["code"]; ?></td>
				<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
				<td style="text-align:center;"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
				</tr>
				<?php
				$total_quantity += $item["quantity"];
				$total_price += ($item["price"]*$item["quantity"]);
				$_SESSION['total'] = $total_price ;
		}
		?>

<tr>
<td colspan="2" align="right">Total:</td>
<td align="right"><?php echo $total_quantity; ?></td>
<td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
<td></td>
</tr>
</tbody>
</table>		
  <?php
} else {
?>
<div class="no-records">Your Cart is Empty</div>
<?php 
}
?>
</div>

<div id="product-grid">
	<div class="txt-heading">Products</div>
	<?php
	$product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
		<div class="product-item">
			<form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
			<div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
			<div class="product-tile-footer">
			<div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
			<div class="product-price"><?php echo "$".$product_array[$key]["price"]; ?></div>
			<div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
			</div>
			</form>
		</div>
	<?php
		}
	}
	?>
</div>
</BODY>
</HTML>
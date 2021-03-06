<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- <script type="text/javascript" src="scripts/ckeditor/ckeditor.js"></script> -->
<?php
include_once("connection.php");
function bind_Category_List($conn){
	$sqlstring="select cat_id, categoryname from public.category";
	$result=pg_query($conn,$sqlstring);
	echo "<select name='CategoryList' class='form-control'>
		<option value='0'>Choose category</option>";
		while($row= pg_fetch_array($result,NULL,PGSQL_ASSOC)){
			echo "<option value='".$row['cat_id']."'>".$row['categoryname']."</option>";
		} 
		echo "</select>";
}
?>
<?php
include_once("connection.php");
function bind_Store_List($conn){
	$sqlstring="select store_id, store_name from public.store";
	$result=pg_query($conn,$sqlstring);
	echo "<select name='StoreList' class='form-control'>
		<option value='0'>Choose store</option>";
		while($row= pg_fetch_array($result,NULL,PGSQL_ASSOC)){
			echo "<option value='".$row['store_id']."'>".$row['store_name']."</option>";
		} 
		echo "</select>";
}
?>

<?php
if(isset($_POST["btnAdd"]))
{
	$id=$_POST["txtID"];
	$proname=$_POST["txtName"];
	$description=$_POST["txtdescription"];
	$price=$_POST["txtPrice"];
	$qty=$_POST["txtQty"];
	$pic=$_FILES['txtImage'];
	$store=$_POST['StoreList'];
	$category=$_POST['CategoryList'];
	
	$err="";
	if(trim($id)==""){
		$err.="<li>Enter product ID, please</li>";
	}
	if(trim($proname)==""){
		$err.="<li>Enter product name, please</li>";
	}
	if(trim($category)==""){
		$err.="<li>Enter product category, please</li>";
	}
	if(trim($store)==""){
		$err.="<li>Enter store, please</li>";
	}
	if(!is_numeric($qty)){
		$err.="<li>Enter quantity, please</li>";
	}
	if($err!=""){
		echo "<ul>$err</ul>";
	}
	else{
		if($pic['type']=="image/jpg"||$pic['type']=="image/jpeg"||$pic['type']=="image/png"||$pic['type']=="image/gif")
		{
		if($pic['size']<614400)
		{
			$sq="select * from public.product where pro_id='$id' or productname='$proname'";
			$result=pg_query($conn,$sq);
			if(pg_num_rows($result)==0)
			{
				copy($pic['tmp_name'],"product/".$pic['name']);
				$filePic=$pic['name'];
				$sqlstring="Insert into public.product(
					pro_id, store_id, cat_id, productname, price, pro_image, quantity, description) 
					values ('$id','$store', '$category', '$proname', '$price', '$filePic', '$qty', '$description')";
					pg_query($conn,$sqlstring) or die(pg_error($conn));
					echo '<meta http-equiv="refresh" content="0;URL=?page=product_management"/>';
			}
			else{
				echo "<li>Duplicate product ID or Name</li>";
			}
		}
		else{
			echo"Size of image too big";
		}
	}
	else{
		echo"Image format is not correct";
	}
}
}
?>
<div class="container">
	<h2>Adding new Product</h2>

	 	<form id="frmProduct" name="frmProduct" method="post" enctype="multipart/form-data" action="" class="form-horizontal" role="form">
				<div class="form-group">
<label for="txtTen" class="col-sm-2 control-label">Product ID(*):  </label>
							<div class="col-sm-10">
							      <input type="text" name="txtID" id="txtID" class="form-control" placeholder="Product ID" value=''/>
							</div>
				</div> 
				<div class="form-group"> 
					<label for="txtTen" class="col-sm-2 control-label">Product Name(*):  </label>
							<div class="col-sm-10">
							      <input type="text" name="txtName" id="txtName" class="form-control" placeholder="Product Name" value=''/>
							</div>
                </div>   

				<div class="form-group">   
                    <label for="lblShort" class="col-sm-2 control-label">Description(*):  </label>
							<div class="col-sm-10">
							      <input type="text" name="txtdescription" id="txtdescription" class="form-control" placeholder="Description" value=''/>
							</div>
                </div>

				<div class="form-group">  
                    <label for="lblGia" class="col-sm-2 control-label">Price(*):  </label>
							<div class="col-sm-10">
							      <input type="text" name="txtPrice" id="txtPrice" class="form-control" placeholder="Price" value=''/>
							</div>
                 </div>   

				 <div class="form-group">  
                    <label for="lblSoLuong" class="col-sm-2 control-label">Quantity(*):  </label>
							<div class="col-sm-10">
							      <input type="number" name="txtQty" id="txtQty" class="form-control" placeholder="Quantity" value=""/>
							</div>
                </div>

				<div class="form-group">  
	                <label for="sphinhanh" class="col-sm-2 control-label">Image(*):  </label>
							<div class="col-sm-10">
							      <input type="file" name="txtImage" id="txtImage" class="form-control" value=""/>
							</div>
                </div>

				<div class="form-group">   
                    <label for="" class="col-sm-2 control-label">Product Store(*):  </label>
							<div class="col-sm-10">
							      <?php bind_Store_List($conn); ?>
							</div>
                </div>  

                <div class="form-group">   
                    <label for="" class="col-sm-2 control-label">Product category(*):  </label>
							<div class="col-sm-10">
							      <?php bind_Category_List($conn); ?>
							</div>
                </div>  
                
				<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
						      <input type="submit"  class="btn btn-primary" name="btnAdd" id="btnAdd" value="Add new" onclick="window.location='?page=product_management'" />                              	
						</div>
				</div>
				
		</form>
</div>

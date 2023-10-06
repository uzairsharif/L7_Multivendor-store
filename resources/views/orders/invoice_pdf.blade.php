<!DOCTYPE html>
<html>
<head>
<style>
	*{
		font-family: Arial, Helvetica, sans-serif;
	}
#customers {
  
  border-collapse: collapse;
  width: 100%;
  margin-top:20px;
}
h1{
	
	font-size:25px;
	text-align: center;
}
.small_font{
	font-size: 15px;
	font-weight: normal;
}
#customers td, #customers th {
  /*border: 1px solid #ddd;*/
  padding-left: 5px;
  padding-right: 5px;
  /*text-align: center;*/
}

/*#customers tr:nth-child(even){background-color: #f2f2f2;}*/

#customers th {
  padding-top: 5px;
  padding-bottom: 5px;
  text-align: left;
  /*background-color: #04AA6D;*/
  color: black;
  padding-left: 165px;
  /*text-align: center;*/
  border-bottom:1px solid black;
}
#customers td{
	padding-left:165px;
	border-bottom:1px solid black;
}
.di{
	display:inline;
}
/*#customers td:first-child{padding-left:0px;}*/
#customers th:first-child{padding-left: 0px;}
</style>
</head>
<body>

	<h1>ZAIRA CROCKERY<small> (RECEIPT)</small></h1>

	<h2 class="small_font di">Order ID: <b>{{$Invoice_single_value_data['Invoice_order_id'] }}</b></h2>
	<h2 class="small_font">Invoice Date: <b>{{$Invoice_single_value_data['Invoice_created_at'] }}</b></h2>

	<table id="customers">
	  <tr>
	    <th>Name</th>
	    <th>Qty</th>
	    <th>Price</th>
	    <th>Sub Total</th>
	  </tr>
	  	
	  	@for($i=0;$i<$number_of_invoice_items;$i++)
			<tr>
				<td style="padding-left:0px">{{$order_items_details['product_names'][$i]}}</td>
				<td>{{$order_items_details['product_quantities'][$i]}}</td>
				<td>{{$order_items_details['product_rates'][$i]}}</td>
				<td>{{$order_items_details['product_quantities'][$i]*$order_items_details['product_rates'][$i]}}</td>
			</tr>
	  	@endfor
	 
	  
	</table>
	<h2 style="text-align:right; margin-top:20px;" class="small_font">Grand Total: <b>{{round($Invoice_single_value_data['Grand_Total'],2) }}</b></h2>

</body>
</html>
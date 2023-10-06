<!DOCTYPE html>
<html>
<head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
h1{
	text-align: center;
}
img{
	width: 40px;
	height: 30px;
}
#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
  text-align: center;
}
</style>
</head>
<body>

<h1>Low Stock Products</h1>

<table id="customers">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Image</th>
    <th>Description</th>
    <th>Quantity</th>
    <th>Sale Price</th>
  </tr>
  @foreach($products as $product)
  <tr>
  <td>{{$product->id}}</td>
	<td>{{$product->name}}</td>
	<td>
  		<!-- <img src="{{ asset($product->img_upload_url) }}"> -->
  		<!-- <img src="{{ storage_path('app/public/user_1/product_3.jpg') }}"> -->
  		<img src="{{ public_path($product->img_upload_url) }}">
  	</td>
	<td>{{$product->description}}</td>
	<td>{{$product->stock}}</td>
	<td>{{$product->Sale_price}}</td>
  </tr>
  @endforeach;
</table>

</body>
</html>
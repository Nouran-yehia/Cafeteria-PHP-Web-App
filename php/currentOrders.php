<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cafeteria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>;
   </head>

<body>
    <h1>Current Orders</h1>
    <br>
    <?php
        require_once('databaseHandler.php');
        $db = new databaseHandler();
        $result = $db->getCurrentOrders();
    ?>
    
    <table class="table table-bordered justify-content-center text-center " id="orders_table">
        <tr class="thead-dark">
            <th>Order Date</th>
            <th>View</th>
            <th>Username</th>
            <th>Room</th>
            <th>Ext.</th>
            <th>Action</th>
        </tr>
        <script>
            let table = document.getElementById("orders_table");
            let total_amount=[];
            <?= json_encode($result) ?>.forEach(myFun);
            function myFun(item,index){
                tr = document.createElement("tr");
                tr.setAttribute("row_id",item["id"]);
                tr.innerHTML= "<td>" + item["date"] + "</td>"
                + "<td>" +  "<button class='not_viewd btn btn-info' onclick=" + "view(this," + item["id"] + "); return false;' id=" + item["id"] + ">+</button>" + "</td>"
                + "<td>" + item["username"] + "</td>"
                + "<td>" + item["room"] + "</td>"
                + "<td>" + item["ext"] + "</td>"
                + "<td>" + "<button class='deliver btn btn-info' id=" + item["id"] + ">Deliver</button></td>";
                table.append(tr);
                total_amount[item["id"]]=item["total_price"];
            }
        </script>
        <script src='http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js'></script>
        <script>
            $('.deliver').click(function () {
            var deliver_id = $(this).attr('id');
            var info = 'oId=' + deliver_id;
            $.ajax({
                type: 'POST',
                url: 'deliverOrder.php',
                data: info,
                success: function () {
                    // alert('Order Cancelled Successfully!');
                }
            });
            $('tr[row_id='+deliver_id+']').remove();
            return false;
          });

          function view(element,order_id) {
            var info = 'oId=' + order_id;
            $.ajax({
                type: 'POST',
                url: 'orderDetails.php',
                dataType: 'json',
                data: info,
                success: function(response){
                    $('#orders').remove();
                    let el = document.getElementsByClassName('viewd');
                    for (let i = 0; i < el.length; i++) {
                        hide(el[i],el[i].id);
                    }
                    let order_details_div=document.createElement('div');
                    // order_details_div.setAttribute("id","orders");
                    // for (var key in response) {
                    // let order_item=document.createElement('span');
                    // let product_name=document.createElement('label');
                    // let product_image=document.createElement('img');
                    // let product_price=document.createElement('label');
                    // product_name.innerHTML=response[key][0]["productname"];
                    // product_image.setAttribute("src",response[key][0]["image"]);
                    // product_price.innerHTML="Price: " + response[key][0]["price"] + " LE";
                    // order_item.appendChild(product_image);
                    // order_item.appendChild(product_name);
                    // order_item.appendChild(product_price);
                    // order_details_div.appendChild(order_item);
                    // element.setAttribute("class","viewd btn btn-danger");
                    // element.setAttribute("onclick","hide(this,"+order_id+")");
                    // element.innerHTML="-";                
                    let order_details_table =document.createElement('table');
                    order_details_table.setAttribute("id","orders")
                    order_details_table.setAttribute("class","table table-bordered justify-content-center text-center");
                    let order_details_table_headings=document.createElement('tr');
                    order_details_table_headings.innerHTML="<th>Item Name</th><th>Item Price</th><th>Item Picture</th>";
                    order_details_table.appendChild(order_details_table_headings);
                    for (var key in response) {
                        let order_item=document.createElement('tr');
                        order_item.innerHTML="<td>"+response[key][0]["name"]+"</td>"+
                                             "<td>"+response[key][0]["price"]+"</td>"+
                                             "<td><img src=../assets/images/products/"+response[key][0]["image"]+" height=50px width=50px></td>";
                        order_details_table.appendChild(order_item);
                    }
                    let order_total_amount=document.createElement('tr');
                    order_total_amount.innerHTML="<td>Total Amount</td><td>" + total_amount[order_id] + "</td>";
                    order_details_table.appendChild(order_total_amount);
                    order_details_div.appendChild(order_details_table);
                    element.closest('tr').after(order_details_div);
                    element.setAttribute("class","viewd btn btn-danger");
                    element.setAttribute("onclick","hide(this,"+order_id+")");
                    element.innerHTML="-";  
                    }
            
            });
            return false;
            };

            function hide(element,order_id){
                $('#orders').remove();
                element.setAttribute("class","not_viewd btn btn-info");
                element.setAttribute("onclick","view(this,"+order_id+")");
                element.innerHTML="+";

            }
        </script>
    </table>
</body>
</html>
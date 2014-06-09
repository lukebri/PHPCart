  <div class="table-responsive adminbox col-lg-6">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Amount</th>
          <th>Order Id</th>
          <th>Order Status</th>
          <th>Order Date</th>
          <th>Address</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $key => $val): ?>
         <tr>
          <td>$<? echo $val['order_id'] ;?></td>
          <td>#<? echo $val['total'] ;?></td>
          <td><? echo $val['status'] ;?></td>
          <td><? echo $val['created'] ;?></td>
          <td><?php
            $address = $db->prepare("SELECT * FROM address WHERE order_id = :id");
            $address->bindValue(':id', $val['order_id'], PDO::PARAM_INT);
            $address->execute();
            $add = $address->fetch(PDO::FETCH_ASSOC);
            if (!empty($add)) {
            foreach ($add as $key => $value) { // Skip id fields
              if ($key == "order_id" || $key == "id") {continue;}
              echo $value . "<br>";
               }
            }
            ?>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>
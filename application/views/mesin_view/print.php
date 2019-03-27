<!DOCTYPE html>
<html>
<head>
<style>
@page { size: landscape; }
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 5px;
}

#customers td {
  font-size: 10px;
}

#customers th {
  font-size: 10px;  
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 5px;
  padding-bottom: 5px;
  text-align: left;
}
</style>
</head>
<body>

<table id="customers">
  <tr>
    <th>CODE</th>
    <th>NAME</th>
    <th>BRAND</th>
    <th>SPESIFICATION</th>
    <th>SERIAL NUMBER</th>
    <th>POWER</th>
    <th>PANEL LOCATION</th>
    <th>LOCATION</th>
    <th>DEPARTURE</th>
  </tr>
  <?php
    foreach ($dataMain as $data) {
  ?>
    <tr>
      <td><?=$data->vckode?></td>
      <td><?=$data->vcnama?></td>
      <td><?=$data->vcbrand?></td>
      <td><?=$data->vcjenis?></td>
      <td><?=$data->vcserial?></td>
      <td><?=$data->vcpower?></td>
      <td><?=$data->vcpanel_location?></td>
      <td><?=$data->vclocation?></td>
      <td><?=$data->intdeparture?></td>
    </tr>
  <?php
    }
  ?>
</table>
<script type="text/javascript">window.print()</script>
</body>
</html>
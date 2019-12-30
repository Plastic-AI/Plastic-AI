<?php if($arr_event):
      /* Mengambil query report*/
      foreach($arr_event as $value){
         $date[] = timestamp_to_date($value->created, 'd/m/Y');
         $all_count[] = (int) $value->all_count;
         $people_count[] = (int) $value->people_count;
         $suspect_count[] = (int) $value->suspect_count;
      }
      /* end mengambil query*/
?>

<div align="center">
   <br>
</div>

<div class="card bd-0 shadow-base pd-15">
    <div class="bd pd-15 mg-b-15 rounded">
      <b>Data Statistics</b>
    </div>
   
    <div class="row">
      <div class="col-xl-6">
         <div class="bd pd-t-30 pd-b-20 pd-x-20"><canvas id="chartLine1" height="150"></canvas></div>
      </div><!-- col-6 -->
      <div class="col-xl-6 mg-t-20 mg-xl-t-0">
         <div class="bd pd-t-30 pd-b-20 pd-x-20"><canvas id="chartLine2" height="150"></canvas></div>
      </div><!-- col-6 -->
   </div><!-- row -->
</div>

<script>
   // pH
   var ctx = document.getElementById('chartLine1').getContext('2d');
   new Chart(ctx, {
   type: 'bar',
   data: {
      labels: <?php echo json_encode($date);?>,
      datasets: [{
            label: '# Events',
            data: <?php echo json_encode(array_reverse($all_count));?>,
            borderColor: '#1fa707',
            backgroundColor: '#1fa707',
            borderWidth: 2,
            fill: false
        },{
            label: '# Peoples',
            data: <?php echo json_encode(array_reverse($people_count));?>,
            borderColor: '#F07124',
            backgroundColor: '#F07124',
            borderWidth: 2,
            fill: false
        }]
      },
      options: {
         legend: {
         display: false,
            labels: {
               display: false
            }
         },
         scales: {
         yAxes: [{
            ticks: {
               beginAtZero:true,
               fontSize: 10,
               max: 200
            }
         }],
         xAxes: [{
            ticks: {
               beginAtZero:true,
               fontSize: 11
            }
         }]
         }
      }
   });
   // Light
   var ctx = document.getElementById('chartLine2').getContext('2d');
   new Chart(ctx, {
   type: 'bar',
   data: {
      labels: <?php echo json_encode($date);?>,
      datasets: [{
            label: '# Suspects',
            data: <?php echo json_encode(array_reverse($suspect_count));?>,
            borderColor: '#189987',
            backgroundColor: '#189987',
            borderWidth: 2,
            fill: true
         }]
      },
      options: {
         legend: {
         display: false,
            labels: {
               display: false
            }
         },
         scales: {
         yAxes: [{
            ticks: {
               beginAtZero:true,
               fontSize: 10,
               max: 200
            }
         }],
         xAxes: [{
            ticks: {
               beginAtZero:true,
               fontSize: 11
            }
         }]
         }
      }
   });
</script>

<?php endif;?>

<div align="center">
   <br>
</div>
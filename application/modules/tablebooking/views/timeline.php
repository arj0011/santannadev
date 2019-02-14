<div  id="timelinechart" style="width: 100%;height:auto;"></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
  google.charts.load("current", {packages:["timeline"]});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var container = document.getElementById('timelinechart');
    var chart = new google.visualization.Timeline(container);
    var dataTable = new google.visualization.DataTable();
    dataTable.addColumn({ type: 'string', id: 'Room' });
    dataTable.addColumn({ type: 'string', id: 'Name' });
    dataTable.addColumn({ type: 'date', id: 'Start' });
    dataTable.addColumn({ type: 'date', id: 'End' });
    dataTable.addRows([['Tables','',new Date(0,0,0,10,0,0),  new Date(0,0,0,22,0,0) ]]);
    dataTable.addRows([
      [ 'Table1','Arjun',new Date(0,0,0,12,00,0),  new Date(0,0,0,13,00,0)],
      [ 'Table2','Anand',new Date(0,0,0,12,0,0), new Date(0,0,0,13,0,0) ],
      [ 'Table3','Vikas',new Date(0,0,0,12,0,0), new Date(0,0,0,13,0,0) ],
      [ 'Table1','Ankit',new Date(0,0,0,13,0,0), new Date(0,0,0,14,0,0) ],
      [ 'Table1','Ashish',new Date(0,0,0,14,30,0), new Date(0,0,0,16,0,0) ],
      [ 'Table2','Rohit',new Date(0,0,0,16,30,0), new Date(0,0,0,18,0,0) ],
      [ 'Table2','Arun',new Date(0,0,0,14,30,0), new Date(0,0,0,16,0,0) ],
      [ 'Table1','Amit',new Date(0,0,0,16,30,0), new Date(0,0,0,18,30,0) ]
      ]);

    var options = {
      timeline: { colorByRowLabel: true },
      backgroundColor: '#ffd',
      colorByRowLabel:true
    };

    chart.draw(dataTable, options);
  }
</script>


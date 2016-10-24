<center><h2><?php echo $banda[0]['banda_nome']?></h2></center>
<div class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center" id="dashboard-col">
            <h2 class="text-center">Musicalizze
              <small>&nbsp;Relatórios</small>
            </h2>
            <p class="lead">Selecione um período abaixo:</p> 
            <br>
            <br>
          </div>
        </div>
         <form role="form" method="POST" name="cadastrar" action="<?php echo base_url('banda/relatorio_consulta'); ?>">
              <input type="hidden" name="captcha">
               <input type="hidden" name="banda_id" value="<?php echo $banda[0]['banda_id']?>"> 
            <div class="row">
              <div class="container">
                  <div class='col-md-5'>
                      <div class="form-group">
                          <div class='input-group date' id='datetimepicker6'>
                              <input value="<?php echo set_value('data_inicio') ?: ''?>" name="data_inicio" type='text' class="form-control" required/>
                              <span class="input-group-addon">
                                  <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                          </div>
                      </div>
                  </div>
                  <div class='col-md-5'>
                      <div class="form-group">
                          <div class='input-group date' id='datetimepicker7'>
                              <input value="<?php echo set_value('data_fim') ?: ''?>" name="data_fim" type='text' class="form-control" required/>
                              <span class="input-group-addon">
                                  <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-2">
                     <button name="cadastrar" value="cadastrar" type="submit" class="btn btn-block btn-info">Consultar</button>
                  </div>
              </div>
              <script type="text/javascript">
                  $(function () {
                      $('#datetimepicker6').datetimepicker({format: 'YYYY/MM/DD'});
                      $('#datetimepicker7').datetimepicker({
                          useCurrent: false, //Important! See issue #1075
                          format: 'YYYY/MM/DD'
                      });
                      $("#datetimepicker6").on("dp.change", function (e) {
                          $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
                      });
                      $("#datetimepicker7").on("dp.change", function (e) {
                          $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
                      });
                  });
              </script>
        </form>
        </div><br><br>
        <?php if(!$atividade_executada && !$atividade_nao_executada && !$atividade_recusada && !$atividade_tipo){?>
          <center><h3 class="text-danger">Sem atividades nesse periodo!</h3><center><hr>
          <?php }else{?>
             <center><?php if($periodo){echo $periodo;}?><center><hr><br><br>
            <div class="col-md-6">
            <center><h3>Atividades Gerais</h3></center>
              <canvas id="canvas" height="400" width="400"></canvas>
               <div style="background:blue;height:10px;width:40px;"></div><label >
                 Atividades Executadas
                </label>
                <div style="background:red;height:10px;width:40px;"></div><label >
                Atividades Não Executadas
                </label>
                <div style="background:#A9A9A9;height:10px;width:40px;"></div><label >
                Atividades Recusadas
                </label>
            </div>

             <div class="col-md-6">
             <center><h3>Tipos de Atividade</h3></center>
              <canvas id="canvas3" height="400" width="400"></canvas>
               <div style="background:#2F4F4F;height:10px;width:40px;"></div><label >
                 Ensaio
                </label>
                <div style="background:#DEB887;height:10px;width:40px;"></div><label >
                Reunião
                </label>
                <div style="background:#3CB371;height:10px;width:40px;"></div><label >
                Show
                </label>
                 <div style="background:#D2691E;height:10px;width:40px;"></div><label >
                Festival
                </label>
                 <div style="background:#483D8B;height:10px;width:40px;"></div><label >
                Evento
                </label>
                 <div style="background:#AFEEEE;height:10px;width:40px;"></div><label >
                Outros
                </label>
            </div>

              <div class="row">
              <div class="col-md-12">
                            <br><br><hr>
              <div id="chartContainer" style="height: 400px; width: 100%;"></div>
              </div>
              </div>
            
        <?php }?>

        <script>

          var data = [
              {
                  value: <?php echo $atividade_executada?>,
                  color:"blue",
                  highlight: "#4169E1",
                  label: "Atividades executadas"
              },
              {
                  value: <?php echo $atividade_nao_executada?>,
                  color:"red",
                  highlight: "#FA8072",
                  label: "Atividades não executadas"
              },
              {
                  value: <?php echo $atividade_recusada?>,
                  color:"#A9A9A9",
                  highlight: "#D3D3D3",
                  label: "Atividades recusadas"
              },
          ];

          var data3 = [
              {
                  value: <?php echo $atividade_tipo['ensaio']?>,
                  color:"#2F4F4F",
                  highlight: "#6B8E23",
                  label: "Ensaio"
              },
              {
                  value: <?php echo $atividade_tipo['reuniao']?>,
                  color:"#DEB887",
                  highlight: "#FFEBCD",
                  label: "Reunião"
              },
              {
                  value: <?php echo $atividade_tipo['show']?>,
                  color:"#3CB371",
                  highlight: "#98FB98",
                  label: "Show"
              },
               {
                  value: <?php echo $atividade_tipo['festival']?>,
                  color:"#D2691E",
                  highlight: "#FFA500",
                  label: "Festival"
              },
               {
                  value: <?php echo $atividade_tipo['evento']?>,
                  color:"#483D8B",
                  highlight: "#BA55D3",
                  label: "Evento"
              },
               {
                  value: <?php echo $atividade_tipo['outros']?>,
                  color:"#AFEEEE",
                  highlight: "#00FFFF",
                  label: "Outros"
              }
          ];

      var ctx = $("#canvas").get(0).getContext("2d");
       var mychart = new Chart(ctx).Pie(data);

      var ctx = $("#canvas3").get(0).getContext("2d");
      var mychart = new Chart(ctx).Pie(data3);

        </script>

      </div>
    </div>
    

    <script>
        <?php echo $executado;?>
  </script>
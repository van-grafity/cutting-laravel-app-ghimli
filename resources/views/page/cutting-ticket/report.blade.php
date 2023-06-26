<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Order Record</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style type="text/css">
        @page {
            margin-top: 1cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
            margin-bottom: 1cm;
        }

		  table > thead > tr > th{
            border : 1px dashed black;
        }

        .table thead th {
            border: 1px dashed black;
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
        }
        
        .table tbody td {
            border: 1px dashed black;
            vertical-align: middle;
            font-weight: bold;
            font-size: 8px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
            padding-left: 5 !important;
            padding-right: 5 !important;
        }
        
	</style>
</head>
<body>
  <div>
    <table width="100%" style="margin-bottom: 0px !important; padding-bottom: 0px !important; font-weight: bold;">
            <tr>
              <td width="50%" style="font-size: 11px;">Ghim Li Indonesia</td>
              <td width="50%" style="font-size: 11px;"></td>
              <td width="60%" style="font-size: 11px;"></td>
              <td width="50%" style="font-size: 11px;"></td>
            </tr>
            <tr>
              <td width="50%" style="font-size: 11px;">Date: {{ date('d/m/Y') }}</td>
              <td width="50%" style="font-size: 11px;"></td>
              <td width="60%" style="font-size: 11px;"></td>
            <td width="50%" style="font-size: 11px;"></td>
            </tr>
            <tr>
              <td width="50%" height="11px" style="font-size: 11px;"></td>
              <td width="50%" height="11px" style="font-size: 11px;"></td>
              <td width="60%" height="11px" style="font-size: 11px;"></td>
                <td width="50%" height="11px" style="font-size: 11px;"></td>
            </tr>
            <tr>
              <td width="50%" style="font-size: 11px;">PACKING LIST</td>
              <td width="50%" style="font-size: 11px;"></td>
              <td width="60%" style="font-size: 11px;"></td>
              <td width="50%" style="font-size: 11px;">Table No. : {{ $data['cutting_order_record']->layingPlanningDetail->table_number }}</td>
            </tr>
            <tr>
              <td width="50%" style="font-size: 11px;">Style# : {{ $data['cutting_order_record']->layingPlanningDetail->layingPlanning->style->style }}</td>
              <td width="50%" style="font-size: 11px;">Pieces :
                <?php 
                    $total = 0;
                    foreach($data['cutting_order_record']->cuttingOrderRecordDetail as $ct){
                        foreach($data['cutting_order_record']->cuttingTicket as $ctt){
                            if($ctt->cutting_order_record_detail_id == $ct->id){
                                $total += $ctt->layer;
                            }
                        }
                    }
                    echo $total;
                ?>
              </td>
              <td width="60%" style="font-size: 11px;">Color : {{ $data['cutting_order_record']->layingPlanningDetail->layingPlanning->color->color }}</td>
              <td width="50%" style="font-size: 11px;">Date : {{ date('d/m/Y') }}</td>
            </tr>
            <tr>
              <td width="50%" style="font-size: 11px;">Job/PO# : {{ $data['cutting_order_record']->layingPlanningDetail->layingPlanning->gl->gl_number }}</td>
              <td width="50%" style="font-size: 11px;">Bundles : {{ $data['cutting_order_record']->cuttingTicket->count() }}</td>
              <td width="60%" style="font-size: 11px;">Roll# : {{ $data['cutting_order_record']->cuttingOrderRecordDetail->count() }}</td>
                <td width="50%" style="font-size: 11px;">Page : 1 of 1</td>
            </tr>
        </table>
        <div style="margin-top: 3px !important; margin-bottom: 3px !important;"></div>
                <table class="table" style="table-layout:fixed;">
                    <thead class="">
                        <tr>
                            <th rowspan="2">Roll</th>
                            <th rowspan="2">Ply</th>
                            @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                                @for($i = 0; $i < $ct->ratio_per_size; $i++)
                                    <th colspan="2">{{ $ct->size->size }}</th>
                                @endfor
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                                @for($i = 0; $i < $ct->ratio_per_size; $i++)
                                    <th>Bundle</th>
                                    <th>Qty</th>
                                @endfor
                            @endforeach
                        </tr>
                    </thead>
                    <tbody style="text-align: center;">
                        @foreach($data['cutting_order_record']->cuttingOrderRecordDetail as $ct)
                            <tr>
                                <td style="width: 2.2%;">{{ $ct->fabric_roll }}</td>
                                <!-- ctt->layer -->
                                <td style="width: 2%;">{{ $ct->layer }}</td>
                                @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $sz)
                                        @foreach($data['cutting_order_record']->cuttingTicket as $ctt)
                                            @if($ctt->size_id == $sz->size_id && $ctt->cutting_order_record_detail_id == $ct->id)
                                            <td> {{ $ctt->ticket_number }} </td>
                                            <td style="width: 2%;"> {{ $ctt->layer }} </td>
                                            @endif
                                        @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="table">
                  <thead class="">
                      <tr>
                          <th>Color</th>
                          @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                              <th>{{ $ct->size->size }}</th>
                          @endforeach
                          <th>Total</th>
                      </tr>
                  </thead>

                  <tbody>
                      <tr>
                          <td>{{ $data['cutting_order_record']->layingPlanningDetail->layingPlanning->color->color }}</td>
                          @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $sz)
                                <?php $total = 0; ?>
                                @foreach($data['cutting_order_record']->cuttingOrderRecordDetail as $ct)
                                    @foreach($data['cutting_order_record']->cuttingTicket as $ctt)
                                        @if($ctt->size_id == $sz->size_id && $ctt->cutting_order_record_detail_id == $ct->id)
                                            <?php $total += $ctt->layer; ?>
                                        @endif
                                    @endforeach
                                @endforeach
                                <td style="text-align: center;">{{ $total }}</td>
                            @endforeach
                          <td style="text-align: center;">
                                <?php 
                                    $total = 0;
                                    foreach($data['cutting_order_record']->cuttingOrderRecordDetail as $ct){
                                        foreach($data['cutting_order_record']->cuttingTicket as $ctt){
                                            if($ctt->cutting_order_record_detail_id == $ct->id){
                                                $total += $ctt->layer;
                                            }
                                        }
                                    }
                                    echo $total;
                                ?>
                            </td>
                      </tr>
                      <tr>
                          <td>Total</td>
                            @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $sz)
                                <?php $total = 0; ?>
                                @foreach($data['cutting_order_record']->cuttingOrderRecordDetail as $ct)
                                    @foreach($data['cutting_order_record']->cuttingTicket as $ctt)
                                        @if($ctt->size_id == $sz->size_id && $ctt->cutting_order_record_detail_id == $ct->id)
                                            <?php $total += $ctt->layer; ?>
                                        @endif
                                    @endforeach
                                @endforeach
                                <td style="text-align: center;">{{ $total }}</td>
                            @endforeach
                          <td style="text-align: center;">
                                <?php 
                                    $total = 0;
                                    foreach($data['cutting_order_record']->cuttingOrderRecordDetail as $ct){
                                        foreach($data['cutting_order_record']->cuttingTicket as $ctt){
                                            if($ctt->cutting_order_record_detail_id == $ct->id){
                                                $total += $ctt->layer;
                                            }
                                        }
                                    }
                                    echo $total;
                                ?>
                            </td>
                      </tr>
                  </tbody>
              </table>

              <br>
              <table width="100%" style="margin-bottom: 0px !important; padding-bottom: 0px !important;">
                <tr>
                  <td style="font-size: 10px;">Checked By</td>
                  <td style="text-align: left; !important; float: left;">_________________________</td>
                  <td style="font-size: 10px;">Cutting Supervisor</td>
                  <td style="text-align: left; !important; float: left;">_________________________</td>
                </tr>
            </table>
    </div>
</body>
</html>

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
            margin-left: 1cm;
            margin-bottom: 0cm;
        }

		table tr td,
		table tr th{
			font-size: 8pt;
		}

        .table-nota td, .table-nota th {
            padding: 0.25rem 0.25rem;
			font-size: 7pt;
            /* text-align:center; */
            vertical-align:middle;
        }

        .header-main { 
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .company-name {
            float: left;
            text-align: left;
            font-size: 12px;
        }

        .title-nota {
            clear:left;
            /* clear:right; */
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .subtitle-nota {
            font-weight: Normal;
            font-size: 11px;
        }

        .serial-number-qr {
            float:right;
            text-align: right;
            font-size: 12px;
        }

        .header-subtitle {
            font-weight: bold;
            width: 100%;
            margin-bottom: .5rem;
        }

        .header-subtitle td {
            vertical-align: bottom;
            border-bottom: 1px solid;
            font-size:12px;
        }
        .header-subtitle td.no-border {
            border: none;
        }

        .subtitle-right {
            /* text-align: right */
        }

        .table-nota {
            border: 2px solid;
        }

        .table-nota thead th {
            border: 1px solid;
            vertical-align: middle;
            font-size: 9pt;
        }
        .table-nota tbody td {
            border: 1px solid;
            font-weight: bold;
            height:25px;
            /* font-size:8pt; */
        }
        
	</style>
</head>
<body>
    <div class="">
        <div class="header-main">
            <div class="company-name">
                PT. GHIMLI INDONESIA
            </div>
            <div class="serial-number-qr">
                <div>RP-GLA-CUT-004</div>
                <div>Rev 0</div>
                <!-- <div class="qr-wrapper" style="margin-top: -15px; margin-right: -15px;">
                    <img src="https://chart.googleapis.com/chart?chs=70x70&cht=qr&chl=123456" alt="">
                </div> -->
            </div>
            <div class="title-nota">
                DAILY CUTTING OUTPUT REPORT
                <br>
                <div class="subtitle-nota"></div>
            </div>

        </div>
        <table class="header-subtitle">
            <thead>
                <tr>
                    <td class="no-border"></td>
                </tr>
            </thead>
        </table>

    <!-- {
      "gl_number": "63168-00",
      "buyer": "GIII",
      "style": "J39H0129",
      "color": "BRIDAL ROSE (15-1611 TCX)",
      "mi_qty": 2400,
      "cutting_order_record": [
        {
          "id": 154,
          "serial_number": "COR-63063-00-WHT TRUFLE HTR-007",
          "laying_planning_detail_id": 190,
          "id_status_layer": 2,
          "id_status_cut": 1,
          "created_at": "2023-06-07T00:29:21.000000Z",
          "updated_at": "2023-06-15T03:55:19.000000Z"
        },
        {
          "id": 156,
          "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-003",
          "laying_planning_detail_id": 609,
          "id_status_layer": 1,
          "id_status_cut": 1,
          "created_at": "2023-06-07T01:30:21.000000Z",
          "updated_at": "2023-06-07T01:30:21.000000Z"
        },
        {
          "id": 157,
          "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-004",
          "laying_planning_detail_id": 610,
          "id_status_layer": 1,
          "id_status_cut": 1,
          "created_at": "2023-06-07T01:30:23.000000Z",
          "updated_at": "2023-06-07T01:30:23.000000Z"
        },
        {
          "id": 159,
          "serial_number": "COR-63063-00-WHT TRUFLE HTR-004",
          "laying_planning_detail_id": 187,
          "id_status_layer": 2,
          "id_status_cut": 1,
          "created_at": "2023-06-07T02:58:44.000000Z",
          "updated_at": "2023-06-15T03:55:21.000000Z"
        },
        {
          "id": 160,
          "serial_number": "COR-63063-00-WHT TRUFLE HTR-005",
          "laying_planning_detail_id": 188,
          "id_status_layer": 2,
          "id_status_cut": 1,
          "created_at": "2023-06-07T02:58:47.000000Z",
          "updated_at": "2023-06-15T03:55:24.000000Z"
        },
        {
          "id": 161,
          "serial_number": "COR-63063-00-WHT TRUFLE HTR-008",
          "laying_planning_detail_id": 191,
          "id_status_layer": 2,
          "id_status_cut": 1,
          "created_at": "2023-06-07T02:58:50.000000Z",
          "updated_at": "2023-06-15T03:55:25.000000Z"
        },
        {
          "id": 165,
          "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-002",
          "laying_planning_detail_id": 608,
          "id_status_layer": 1,
          "id_status_cut": 1,
          "created_at": "2023-06-07T03:49:12.000000Z",
          "updated_at": "2023-06-07T03:49:12.000000Z"
        },
        {
          "id": 166,
          "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-005",
          "laying_planning_detail_id": 611,
          "id_status_layer": 1,
          "id_status_cut": 1,
          "created_at": "2023-06-07T05:26:40.000000Z",
          "updated_at": "2023-06-07T05:26:40.000000Z"
        }
      ],
      "cutting_order_record_detail": [
        {
          "id": 59,
          "cutting_order_record_id": 154,
          "fabric_roll": "29",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 75.1,
          "weight": 22.1,
          "layer": 9,
          "joint": 30,
          "balance_end": 41,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T01:06:33.000000Z",
          "updated_at": "2023-06-07T01:06:33.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 154,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-007",
            "laying_planning_detail_id": 190,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T00:29:21.000000Z",
            "updated_at": "2023-06-15T03:55:19.000000Z"
          }
        },
        {
          "id": 60,
          "cutting_order_record_id": 154,
          "fabric_roll": "46",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 77.5,
          "weight": 22.8,
          "layer": 21,
          "joint": 35,
          "balance_end": -2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T01:07:53.000000Z",
          "updated_at": "2023-06-07T01:07:53.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 154,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-007",
            "laying_planning_detail_id": 190,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T00:29:21.000000Z",
            "updated_at": "2023-06-15T03:55:19.000000Z"
          }
        },
        {
          "id": 61,
          "cutting_order_record_id": 154,
          "fabric_roll": "26",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 70,
          "weight": 20.6,
          "layer": 21,
          "joint": 0,
          "balance_end": -10,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T01:14:19.000000Z",
          "updated_at": "2023-06-07T01:14:19.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 154,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-007",
            "laying_planning_detail_id": 190,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T00:29:21.000000Z",
            "updated_at": "2023-06-15T03:55:19.000000Z"
          }
        },
        {
          "id": 69,
          "cutting_order_record_id": 156,
          "fabric_roll": "1",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 74,
          "weight": 26.01,
          "layer": 17,
          "joint": 36,
          "balance_end": 10,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:40:42.000000Z",
          "updated_at": "2023-06-07T05:40:42.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 156,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-003",
            "laying_planning_detail_id": 609,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:21.000000Z",
            "updated_at": "2023-06-07T01:30:21.000000Z"
          }
        },
        {
          "id": 74,
          "cutting_order_record_id": 156,
          "fabric_roll": "14",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 74,
          "weight": 26.01,
          "layer": 20,
          "joint": 0,
          "balance_end": -2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:59:41.000000Z",
          "updated_at": "2023-06-07T05:59:41.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 156,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-003",
            "laying_planning_detail_id": 609,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:21.000000Z",
            "updated_at": "2023-06-07T01:30:21.000000Z"
          }
        },
        {
          "id": 77,
          "cutting_order_record_id": 156,
          "fabric_roll": "12",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 75,
          "weight": 26.06,
          "layer": 20,
          "joint": 18,
          "balance_end": -1,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T06:19:55.000000Z",
          "updated_at": "2023-06-07T06:19:55.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 156,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-003",
            "laying_planning_detail_id": 609,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:21.000000Z",
            "updated_at": "2023-06-07T01:30:21.000000Z"
          }
        },
        {
          "id": 78,
          "cutting_order_record_id": 156,
          "fabric_roll": "8",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 75,
          "weight": 26.26,
          "layer": 19,
          "joint": 0,
          "balance_end": 3,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T06:38:56.000000Z",
          "updated_at": "2023-06-07T06:38:56.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 156,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-003",
            "laying_planning_detail_id": 609,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:21.000000Z",
            "updated_at": "2023-06-07T01:30:21.000000Z"
          }
        },
        {
          "id": 82,
          "cutting_order_record_id": 157,
          "fabric_roll": "5",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 79,
          "weight": 27.73,
          "layer": 21,
          "joint": 54,
          "balance_end": 0,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T08:58:12.000000Z",
          "updated_at": "2023-06-07T08:58:12.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 157,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-004",
            "laying_planning_detail_id": 610,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:23.000000Z",
            "updated_at": "2023-06-07T01:30:23.000000Z"
          }
        },
        {
          "id": 83,
          "cutting_order_record_id": 157,
          "fabric_roll": "13",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 66,
          "weight": 23.07,
          "layer": 17,
          "joint": 0,
          "balance_end": 2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T09:57:14.000000Z",
          "updated_at": "2023-06-07T09:57:14.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 157,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-004",
            "laying_planning_detail_id": 610,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:23.000000Z",
            "updated_at": "2023-06-07T01:30:23.000000Z"
          }
        },
        {
          "id": 84,
          "cutting_order_record_id": 157,
          "fabric_roll": "7",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 75,
          "weight": 26.08,
          "layer": 19,
          "joint": 36,
          "balance_end": 3,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T09:58:32.000000Z",
          "updated_at": "2023-06-07T09:58:32.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 157,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-004",
            "laying_planning_detail_id": 610,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:23.000000Z",
            "updated_at": "2023-06-07T01:30:23.000000Z"
          }
        },
        {
          "id": 85,
          "cutting_order_record_id": 157,
          "fabric_roll": "9",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 70,
          "weight": 24.62,
          "layer": 18,
          "joint": 0,
          "balance_end": 2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T09:59:30.000000Z",
          "updated_at": "2023-06-07T09:59:30.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 157,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-004",
            "laying_planning_detail_id": 610,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:23.000000Z",
            "updated_at": "2023-06-07T01:30:23.000000Z"
          }
        },
        {
          "id": 70,
          "cutting_order_record_id": 159,
          "fabric_roll": "39",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 77.5,
          "weight": 22.9,
          "layer": 13,
          "joint": 0,
          "balance_end": -4,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:40:47.000000Z",
          "updated_at": "2023-06-07T05:40:47.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 159,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-004",
            "laying_planning_detail_id": 187,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:44.000000Z",
            "updated_at": "2023-06-15T03:55:21.000000Z"
          }
        },
        {
          "id": 71,
          "cutting_order_record_id": 159,
          "fabric_roll": "48",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 76.8,
          "weight": 22.6,
          "layer": 13,
          "joint": 35,
          "balance_end": -5,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:41:47.000000Z",
          "updated_at": "2023-06-07T05:41:47.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 159,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-004",
            "laying_planning_detail_id": 187,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:44.000000Z",
            "updated_at": "2023-06-15T03:55:21.000000Z"
          }
        },
        {
          "id": 72,
          "cutting_order_record_id": 159,
          "fabric_roll": "31",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 76.5,
          "weight": 22.5,
          "layer": 12,
          "joint": 50,
          "balance_end": 1,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:42:46.000000Z",
          "updated_at": "2023-06-07T05:42:46.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 159,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-004",
            "laying_planning_detail_id": 187,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:44.000000Z",
            "updated_at": "2023-06-15T03:55:21.000000Z"
          }
        },
        {
          "id": 73,
          "cutting_order_record_id": 159,
          "fabric_roll": "38",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 70,
          "weight": 20.6,
          "layer": 12,
          "joint": 0,
          "balance_end": -5,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:43:52.000000Z",
          "updated_at": "2023-06-07T05:43:52.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 159,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-004",
            "laying_planning_detail_id": 187,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:44.000000Z",
            "updated_at": "2023-06-15T03:55:21.000000Z"
          }
        },
        {
          "id": 75,
          "cutting_order_record_id": 160,
          "fabric_roll": "45",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 78.9,
          "weight": 23.2,
          "layer": 13,
          "joint": 85,
          "balance_end": -2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T06:18:32.000000Z",
          "updated_at": "2023-06-07T06:18:32.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 160,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-005",
            "laying_planning_detail_id": 188,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:47.000000Z",
            "updated_at": "2023-06-15T03:55:24.000000Z"
          }
        },
        {
          "id": 76,
          "cutting_order_record_id": 160,
          "fabric_roll": "30",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 75.5,
          "weight": 22.2,
          "layer": 11,
          "joint": 50,
          "balance_end": 7,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T06:19:40.000000Z",
          "updated_at": "2023-06-07T06:19:40.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 160,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-005",
            "laying_planning_detail_id": 188,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:47.000000Z",
            "updated_at": "2023-06-15T03:55:24.000000Z"
          }
        },
        {
          "id": 86,
          "cutting_order_record_id": 161,
          "fabric_roll": "51",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 45.3,
          "weight": 17.8,
          "layer": 11,
          "joint": 0,
          "balance_end": 4,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T10:02:04.000000Z",
          "updated_at": "2023-06-07T10:02:04.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 161,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-008",
            "laying_planning_detail_id": 191,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:50.000000Z",
            "updated_at": "2023-06-15T03:55:25.000000Z"
          }
        },
        {
          "id": 87,
          "cutting_order_record_id": 161,
          "fabric_roll": "30",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 75.5,
          "weight": 22.2,
          "layer": 3,
          "joint": 0,
          "balance_end": 64,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T10:02:56.000000Z",
          "updated_at": "2023-06-07T10:02:56.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 161,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-008",
            "laying_planning_detail_id": 191,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:50.000000Z",
            "updated_at": "2023-06-15T03:55:25.000000Z"
          }
        },
        {
          "id": 65,
          "cutting_order_record_id": 165,
          "fabric_roll": "16",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 74,
          "weight": 25.85,
          "layer": 19,
          "joint": 36,
          "balance_end": 2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:06:40.000000Z",
          "updated_at": "2023-06-07T05:06:40.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 165,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-002",
            "laying_planning_detail_id": 608,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T03:49:12.000000Z",
            "updated_at": "2023-06-07T03:49:12.000000Z"
          }
        },
        {
          "id": 66,
          "cutting_order_record_id": 165,
          "fabric_roll": "4",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 78,
          "weight": 27.27,
          "layer": 20,
          "joint": 36,
          "balance_end": 2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:07:41.000000Z",
          "updated_at": "2023-06-07T05:07:41.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 165,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-002",
            "laying_planning_detail_id": 608,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T03:49:12.000000Z",
            "updated_at": "2023-06-07T03:49:12.000000Z"
          }
        },
        {
          "id": 67,
          "cutting_order_record_id": 165,
          "fabric_roll": "20",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 82,
          "weight": 28.51,
          "layer": 20,
          "joint": 72,
          "balance_end": 6,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:08:43.000000Z",
          "updated_at": "2023-06-07T05:08:43.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 165,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-002",
            "laying_planning_detail_id": 608,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T03:49:12.000000Z",
            "updated_at": "2023-06-07T03:49:12.000000Z"
          }
        },
        {
          "id": 68,
          "cutting_order_record_id": 165,
          "fabric_roll": "72",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 72,
          "weight": 25.08,
          "layer": 18,
          "joint": 36,
          "balance_end": 4,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:09:34.000000Z",
          "updated_at": "2023-06-07T05:09:34.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 165,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-002",
            "laying_planning_detail_id": 608,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T03:49:12.000000Z",
            "updated_at": "2023-06-07T03:49:12.000000Z"
          }
        },
        {
          "id": 79,
          "cutting_order_record_id": 166,
          "fabric_roll": "3",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 68,
          "weight": 23.77,
          "layer": 17,
          "joint": 36,
          "balance_end": 4,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T08:19:13.000000Z",
          "updated_at": "2023-06-07T08:19:13.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 166,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-005",
            "laying_planning_detail_id": 611,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T05:26:40.000000Z",
            "updated_at": "2023-06-07T05:26:40.000000Z"
          }
        },
        {
          "id": 80,
          "cutting_order_record_id": 166,
          "fabric_roll": "10",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 73,
          "weight": 25.57,
          "layer": 19,
          "joint": 36,
          "balance_end": 1,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T08:20:31.000000Z",
          "updated_at": "2023-06-07T08:20:31.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 166,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-005",
            "laying_planning_detail_id": 611,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T05:26:40.000000Z",
            "updated_at": "2023-06-07T05:26:40.000000Z"
          }
        },
        {
          "id": 81,
          "cutting_order_record_id": 166,
          "fabric_roll": "17",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 66,
          "weight": 23.15,
          "layer": 17,
          "joint": 72,
          "balance_end": 2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T08:21:46.000000Z",
          "updated_at": "2023-06-07T08:21:46.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 166,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-005",
            "laying_planning_detail_id": 611,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T05:26:40.000000Z",
            "updated_at": "2023-06-07T05:26:40.000000Z"
          }
        }
      ]
    },
    {
      "gl_number": "63168-00",
      "buyer": "GIII",
      "style": "J39H0129",
      "color": "SYZ/SKY CAPT(19-3922 TCX)",
      "mi_qty": 2400,
      "cutting_order_record": [
        {
          "id": 154,
          "serial_number": "COR-63063-00-WHT TRUFLE HTR-007",
          "laying_planning_detail_id": 190,
          "id_status_layer": 2,
          "id_status_cut": 1,
          "created_at": "2023-06-07T00:29:21.000000Z",
          "updated_at": "2023-06-15T03:55:19.000000Z"
        },
        {
          "id": 156,
          "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-003",
          "laying_planning_detail_id": 609,
          "id_status_layer": 1,
          "id_status_cut": 1,
          "created_at": "2023-06-07T01:30:21.000000Z",
          "updated_at": "2023-06-07T01:30:21.000000Z"
        },
        {
          "id": 157,
          "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-004",
          "laying_planning_detail_id": 610,
          "id_status_layer": 1,
          "id_status_cut": 1,
          "created_at": "2023-06-07T01:30:23.000000Z",
          "updated_at": "2023-06-07T01:30:23.000000Z"
        },
        {
          "id": 159,
          "serial_number": "COR-63063-00-WHT TRUFLE HTR-004",
          "laying_planning_detail_id": 187,
          "id_status_layer": 2,
          "id_status_cut": 1,
          "created_at": "2023-06-07T02:58:44.000000Z",
          "updated_at": "2023-06-15T03:55:21.000000Z"
        },
        {
          "id": 160,
          "serial_number": "COR-63063-00-WHT TRUFLE HTR-005",
          "laying_planning_detail_id": 188,
          "id_status_layer": 2,
          "id_status_cut": 1,
          "created_at": "2023-06-07T02:58:47.000000Z",
          "updated_at": "2023-06-15T03:55:24.000000Z"
        },
        {
          "id": 161,
          "serial_number": "COR-63063-00-WHT TRUFLE HTR-008",
          "laying_planning_detail_id": 191,
          "id_status_layer": 2,
          "id_status_cut": 1,
          "created_at": "2023-06-07T02:58:50.000000Z",
          "updated_at": "2023-06-15T03:55:25.000000Z"
        },
        {
          "id": 165,
          "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-002",
          "laying_planning_detail_id": 608,
          "id_status_layer": 1,
          "id_status_cut": 1,
          "created_at": "2023-06-07T03:49:12.000000Z",
          "updated_at": "2023-06-07T03:49:12.000000Z"
        },
        {
          "id": 166,
          "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-005",
          "laying_planning_detail_id": 611,
          "id_status_layer": 1,
          "id_status_cut": 1,
          "created_at": "2023-06-07T05:26:40.000000Z",
          "updated_at": "2023-06-07T05:26:40.000000Z"
        }
      ],
      "cutting_order_record_detail": [
        {
          "id": 59,
          "cutting_order_record_id": 154,
          "fabric_roll": "29",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 75.1,
          "weight": 22.1,
          "layer": 9,
          "joint": 30,
          "balance_end": 41,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T01:06:33.000000Z",
          "updated_at": "2023-06-07T01:06:33.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 154,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-007",
            "laying_planning_detail_id": 190,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T00:29:21.000000Z",
            "updated_at": "2023-06-15T03:55:19.000000Z"
          }
        },
        {
          "id": 60,
          "cutting_order_record_id": 154,
          "fabric_roll": "46",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 77.5,
          "weight": 22.8,
          "layer": 21,
          "joint": 35,
          "balance_end": -2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T01:07:53.000000Z",
          "updated_at": "2023-06-07T01:07:53.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 154,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-007",
            "laying_planning_detail_id": 190,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T00:29:21.000000Z",
            "updated_at": "2023-06-15T03:55:19.000000Z"
          }
        },
        {
          "id": 61,
          "cutting_order_record_id": 154,
          "fabric_roll": "26",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 70,
          "weight": 20.6,
          "layer": 21,
          "joint": 0,
          "balance_end": -10,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T01:14:19.000000Z",
          "updated_at": "2023-06-07T01:14:19.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 154,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-007",
            "laying_planning_detail_id": 190,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T00:29:21.000000Z",
            "updated_at": "2023-06-15T03:55:19.000000Z"
          }
        },
        {
          "id": 69,
          "cutting_order_record_id": 156,
          "fabric_roll": "1",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 74,
          "weight": 26.01,
          "layer": 17,
          "joint": 36,
          "balance_end": 10,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:40:42.000000Z",
          "updated_at": "2023-06-07T05:40:42.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 156,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-003",
            "laying_planning_detail_id": 609,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:21.000000Z",
            "updated_at": "2023-06-07T01:30:21.000000Z"
          }
        },
        {
          "id": 74,
          "cutting_order_record_id": 156,
          "fabric_roll": "14",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 74,
          "weight": 26.01,
          "layer": 20,
          "joint": 0,
          "balance_end": -2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:59:41.000000Z",
          "updated_at": "2023-06-07T05:59:41.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 156,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-003",
            "laying_planning_detail_id": 609,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:21.000000Z",
            "updated_at": "2023-06-07T01:30:21.000000Z"
          }
        },
        {
          "id": 77,
          "cutting_order_record_id": 156,
          "fabric_roll": "12",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 75,
          "weight": 26.06,
          "layer": 20,
          "joint": 18,
          "balance_end": -1,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T06:19:55.000000Z",
          "updated_at": "2023-06-07T06:19:55.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 156,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-003",
            "laying_planning_detail_id": 609,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:21.000000Z",
            "updated_at": "2023-06-07T01:30:21.000000Z"
          }
        },
        {
          "id": 78,
          "cutting_order_record_id": 156,
          "fabric_roll": "8",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 75,
          "weight": 26.26,
          "layer": 19,
          "joint": 0,
          "balance_end": 3,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T06:38:56.000000Z",
          "updated_at": "2023-06-07T06:38:56.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 156,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-003",
            "laying_planning_detail_id": 609,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:21.000000Z",
            "updated_at": "2023-06-07T01:30:21.000000Z"
          }
        },
        {
          "id": 82,
          "cutting_order_record_id": 157,
          "fabric_roll": "5",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 79,
          "weight": 27.73,
          "layer": 21,
          "joint": 54,
          "balance_end": 0,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T08:58:12.000000Z",
          "updated_at": "2023-06-07T08:58:12.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 157,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-004",
            "laying_planning_detail_id": 610,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:23.000000Z",
            "updated_at": "2023-06-07T01:30:23.000000Z"
          }
        },
        {
          "id": 83,
          "cutting_order_record_id": 157,
          "fabric_roll": "13",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 66,
          "weight": 23.07,
          "layer": 17,
          "joint": 0,
          "balance_end": 2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T09:57:14.000000Z",
          "updated_at": "2023-06-07T09:57:14.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 157,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-004",
            "laying_planning_detail_id": 610,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:23.000000Z",
            "updated_at": "2023-06-07T01:30:23.000000Z"
          }
        },
        {
          "id": 84,
          "cutting_order_record_id": 157,
          "fabric_roll": "7",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 75,
          "weight": 26.08,
          "layer": 19,
          "joint": 36,
          "balance_end": 3,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T09:58:32.000000Z",
          "updated_at": "2023-06-07T09:58:32.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 157,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-004",
            "laying_planning_detail_id": 610,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:23.000000Z",
            "updated_at": "2023-06-07T01:30:23.000000Z"
          }
        },
        {
          "id": 85,
          "cutting_order_record_id": 157,
          "fabric_roll": "9",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 70,
          "weight": 24.62,
          "layer": 18,
          "joint": 0,
          "balance_end": 2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T09:59:30.000000Z",
          "updated_at": "2023-06-07T09:59:30.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 157,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-004",
            "laying_planning_detail_id": 610,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T01:30:23.000000Z",
            "updated_at": "2023-06-07T01:30:23.000000Z"
          }
        },
        {
          "id": 70,
          "cutting_order_record_id": 159,
          "fabric_roll": "39",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 77.5,
          "weight": 22.9,
          "layer": 13,
          "joint": 0,
          "balance_end": -4,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:40:47.000000Z",
          "updated_at": "2023-06-07T05:40:47.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 159,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-004",
            "laying_planning_detail_id": 187,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:44.000000Z",
            "updated_at": "2023-06-15T03:55:21.000000Z"
          }
        },
        {
          "id": 71,
          "cutting_order_record_id": 159,
          "fabric_roll": "48",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 76.8,
          "weight": 22.6,
          "layer": 13,
          "joint": 35,
          "balance_end": -5,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:41:47.000000Z",
          "updated_at": "2023-06-07T05:41:47.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 159,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-004",
            "laying_planning_detail_id": 187,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:44.000000Z",
            "updated_at": "2023-06-15T03:55:21.000000Z"
          }
        },
        {
          "id": 72,
          "cutting_order_record_id": 159,
          "fabric_roll": "31",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 76.5,
          "weight": 22.5,
          "layer": 12,
          "joint": 50,
          "balance_end": 1,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:42:46.000000Z",
          "updated_at": "2023-06-07T05:42:46.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 159,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-004",
            "laying_planning_detail_id": 187,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:44.000000Z",
            "updated_at": "2023-06-15T03:55:21.000000Z"
          }
        },
        {
          "id": 73,
          "cutting_order_record_id": 159,
          "fabric_roll": "38",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 70,
          "weight": 20.6,
          "layer": 12,
          "joint": 0,
          "balance_end": -5,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:43:52.000000Z",
          "updated_at": "2023-06-07T05:43:52.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 159,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-004",
            "laying_planning_detail_id": 187,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:44.000000Z",
            "updated_at": "2023-06-15T03:55:21.000000Z"
          }
        },
        {
          "id": 75,
          "cutting_order_record_id": 160,
          "fabric_roll": "45",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 78.9,
          "weight": 23.2,
          "layer": 13,
          "joint": 85,
          "balance_end": -2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T06:18:32.000000Z",
          "updated_at": "2023-06-07T06:18:32.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 160,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-005",
            "laying_planning_detail_id": 188,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:47.000000Z",
            "updated_at": "2023-06-15T03:55:24.000000Z"
          }
        },
        {
          "id": 76,
          "cutting_order_record_id": 160,
          "fabric_roll": "30",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 75.5,
          "weight": 22.2,
          "layer": 11,
          "joint": 50,
          "balance_end": 7,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T06:19:40.000000Z",
          "updated_at": "2023-06-07T06:19:40.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 160,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-005",
            "laying_planning_detail_id": 188,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:47.000000Z",
            "updated_at": "2023-06-15T03:55:24.000000Z"
          }
        },
        {
          "id": 86,
          "cutting_order_record_id": 161,
          "fabric_roll": "51",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 45.3,
          "weight": 17.8,
          "layer": 11,
          "joint": 0,
          "balance_end": 4,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T10:02:04.000000Z",
          "updated_at": "2023-06-07T10:02:04.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 161,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-008",
            "laying_planning_detail_id": 191,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:50.000000Z",
            "updated_at": "2023-06-15T03:55:25.000000Z"
          }
        },
        {
          "id": 87,
          "cutting_order_record_id": 161,
          "fabric_roll": "30",
          "fabric_batch": "1#",
          "color_id": 37,
          "yardage": 75.5,
          "weight": 22.2,
          "layer": 3,
          "joint": 0,
          "balance_end": 64,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T10:02:56.000000Z",
          "updated_at": "2023-06-07T10:02:56.000000Z",
          "color": {
            "id": 37,
            "color": "WHITE TRUFFLE HTR",
            "color_code": "WHT TRUFLE HTR",
            "created_at": "2023-05-23T02:14:00.000000Z",
            "updated_at": "2023-05-23T02:14:00.000000Z"
          },
          "cutting_order_record": {
            "id": 161,
            "serial_number": "COR-63063-00-WHT TRUFLE HTR-008",
            "laying_planning_detail_id": 191,
            "id_status_layer": 2,
            "id_status_cut": 1,
            "created_at": "2023-06-07T02:58:50.000000Z",
            "updated_at": "2023-06-15T03:55:25.000000Z"
          }
        },
        {
          "id": 65,
          "cutting_order_record_id": 165,
          "fabric_roll": "16",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 74,
          "weight": 25.85,
          "layer": 19,
          "joint": 36,
          "balance_end": 2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:06:40.000000Z",
          "updated_at": "2023-06-07T05:06:40.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 165,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-002",
            "laying_planning_detail_id": 608,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T03:49:12.000000Z",
            "updated_at": "2023-06-07T03:49:12.000000Z"
          }
        },
        {
          "id": 66,
          "cutting_order_record_id": 165,
          "fabric_roll": "4",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 78,
          "weight": 27.27,
          "layer": 20,
          "joint": 36,
          "balance_end": 2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:07:41.000000Z",
          "updated_at": "2023-06-07T05:07:41.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 165,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-002",
            "laying_planning_detail_id": 608,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T03:49:12.000000Z",
            "updated_at": "2023-06-07T03:49:12.000000Z"
          }
        },
        {
          "id": 67,
          "cutting_order_record_id": 165,
          "fabric_roll": "20",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 82,
          "weight": 28.51,
          "layer": 20,
          "joint": 72,
          "balance_end": 6,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:08:43.000000Z",
          "updated_at": "2023-06-07T05:08:43.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 165,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-002",
            "laying_planning_detail_id": 608,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T03:49:12.000000Z",
            "updated_at": "2023-06-07T03:49:12.000000Z"
          }
        },
        {
          "id": 68,
          "cutting_order_record_id": 165,
          "fabric_roll": "72",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 72,
          "weight": 25.08,
          "layer": 18,
          "joint": 36,
          "balance_end": 4,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T05:09:34.000000Z",
          "updated_at": "2023-06-07T05:09:34.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 165,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-002",
            "laying_planning_detail_id": 608,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T03:49:12.000000Z",
            "updated_at": "2023-06-07T03:49:12.000000Z"
          }
        },
        {
          "id": 79,
          "cutting_order_record_id": 166,
          "fabric_roll": "3",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 68,
          "weight": 23.77,
          "layer": 17,
          "joint": 36,
          "balance_end": 4,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T08:19:13.000000Z",
          "updated_at": "2023-06-07T08:19:13.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 166,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-005",
            "laying_planning_detail_id": 611,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T05:26:40.000000Z",
            "updated_at": "2023-06-07T05:26:40.000000Z"
          }
        },
        {
          "id": 80,
          "cutting_order_record_id": 166,
          "fabric_roll": "10",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 73,
          "weight": 25.57,
          "layer": 19,
          "joint": 36,
          "balance_end": 1,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T08:20:31.000000Z",
          "updated_at": "2023-06-07T08:20:31.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 166,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-005",
            "laying_planning_detail_id": 611,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T05:26:40.000000Z",
            "updated_at": "2023-06-07T05:26:40.000000Z"
          }
        },
        {
          "id": 81,
          "cutting_order_record_id": 166,
          "fabric_roll": "17",
          "fabric_batch": "99717A1",
          "color_id": 64,
          "yardage": 66,
          "weight": 23.15,
          "layer": 17,
          "joint": 72,
          "balance_end": 2,
          "remarks": "-",
          "operator": "User Admin",
          "created_at": "2023-06-07T08:21:46.000000Z",
          "updated_at": "2023-06-07T08:21:46.000000Z",
          "color": {
            "id": 64,
            "color": "BRIDAL ROSE (15-1611 TCX)",
            "color_code": "BRDL RSE (15-1611 TCX)",
            "created_at": "2023-06-02T14:27:36.000000Z",
            "updated_at": "2023-06-02T15:10:25.000000Z"
          },
          "cutting_order_record": {
            "id": 166,
            "serial_number": "COR-63168-00-BRDL RSE (15-1611 TCX)-005",
            "laying_planning_detail_id": 611,
            "id_status_layer": 1,
            "id_status_cut": 1,
            "created_at": "2023-06-07T05:26:40.000000Z",
            "updated_at": "2023-06-07T05:26:40.000000Z"
          }
        }
      ]
    }, -->

        <div class="body-nota">
            <table class="table table-nota">
                <thead class="">
                    <tr>
                        <th rowspan="1" >Buyer</th>
                        <th rowspan="1" >Style</th>
                        <th rowspan="1" >GL#</th>
                        <th rowspan="1" >COLOR</th>
                        <th rowspan="1" >MI QTY</th>
                        <th rowspan="1" >Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['laying_planning'] as $key => $value)
                    <!-- data['laying_planning'][$key]['cutting_order_record'] -->
                    <!-- data['laying_planning'][$key]['cutting_order_record']['color'] -->

                    <tr>
                        <!-- jika nama buyer sama maka string kosong -->
                        <td rowspan="1" >{{ $value['buyer'] ?? '' }}</td>
                        <td rowspan="1" >{{ $value['style'] }}</td>
                        <td rowspan="1" >{{ $value['gl_number'] }}</td>
                        <td rowspan="1" >{{ $value['color'] }}</td>
                        <td rowspan="1" ></td>
                        <td rowspan="1" ></td>
                        <td rowspan="1" ></td>
                        <td rowspan="1" ></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>    
        </div>
    </div>
</body>
</html>
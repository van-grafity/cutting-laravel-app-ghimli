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

		table.table-bordered > thead > tr > th{
            border-top: 1px dotted black;
            border-bottom: 1px dotted black;
            border-left: 1px dotted black;
            border-right: 1px dotted black;
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

        .table thead th {
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
        }
        
        .table tbody td {
            border: 1px dotted black;
            text-align: center;
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
<!-- {
  "id": 129,
  "serial_number": "COR-63063-00-DRTING FRL - ROYAL-002",
  "laying_planning_detail_id": 300,
  "created_at": "2023-05-31T06:16:02.000000Z",
  "updated_at": "2023-05-31T06:16:02.000000Z",
  "laying_planning_detail": {
    "id": 300,
    "no_laying_sheet": "63063-002",
    "table_number": 2,
    "laying_planning_id": 28,
    "layer_qty": 50,
    "marker_code": "B",
    "marker_yard": 5,
    "marker_inch": 35.25,
    "marker_length": 6.01,
    "total_length": 300.5,
    "total_all_size": 300,
    "created_at": "2023-05-27T08:33:05.000000Z",
    "updated_at": "2023-05-27T08:33:05.000000Z",
    "laying_planning_detail_size": [
      {
        "id": 2337,
        "laying_planning_detail_id": 300,
        "size_id": 32,
        "ratio_per_size": 0,
        "qty_per_size": 0,
        "created_at": "2023-05-27T08:36:20.000000Z",
        "updated_at": "2023-05-27T08:36:20.000000Z",
        "size": {
          "id": 32,
          "size": "0",
          "created_at": "2023-05-23T08:44:39.000000Z",
          "updated_at": "2023-05-23T08:44:39.000000Z"
        }
      },
      {
        "id": 2338,
        "laying_planning_detail_id": 300,
        "size_id": 33,
        "ratio_per_size": 2,
        "qty_per_size": 100,
        "created_at": "2023-05-27T08:36:20.000000Z",
        "updated_at": "2023-05-27T08:36:20.000000Z",
        "size": {
          "id": 33,
          "size": "1",
          "created_at": "2023-05-23T08:47:15.000000Z",
          "updated_at": "2023-05-23T08:47:15.000000Z"
        }
      },
      {
        "id": 2339,
        "laying_planning_detail_id": 300,
        "size_id": 35,
        "ratio_per_size": 2,
        "qty_per_size": 100,
        "created_at": "2023-05-27T08:36:20.000000Z",
        "updated_at": "2023-05-27T08:36:20.000000Z",
        "size": {
          "id": 35,
          "size": "2",
          "created_at": "2023-05-23T08:50:34.000000Z",
          "updated_at": "2023-05-27T07:18:49.000000Z"
        }
      },
      {
        "id": 2340,
        "laying_planning_detail_id": 300,
        "size_id": 36,
        "ratio_per_size": 2,
        "qty_per_size": 100,
        "created_at": "2023-05-27T08:36:20.000000Z",
        "updated_at": "2023-05-27T08:36:20.000000Z",
        "size": {
          "id": 36,
          "size": "3",
          "created_at": "2023-05-27T07:19:00.000000Z",
          "updated_at": "2023-05-27T07:19:00.000000Z"
        }
      }
    ],
    "laying_planning": {
      "id": 28,
      "serial_number": "LP-63063-DRTING FRL - ROYAL",
      "gl_id": 12,
      "style_id": 18,
      "buyer_id": 7,
      "color_id": 34,
      "order_qty": 1200,
      "delivery_date": "2023-07-03",
      "plan_date": "2023-05-23",
      "fabric_po": "100049955",
      "fabric_cons_id": 14,
      "fabric_type_id": 14,
      "fabric_cons_qty": 11.9,
      "fabric_cons_desc": "BODY A :Front/Back/Sleeves: 11.90 yds (mini marker 11.90 yds/dzn) x 58\" x 255gm(cuttable) (AOP)",
      "created_at": "2023-05-23T09:31:44.000000Z",
      "updated_at": "2023-05-27T08:33:38.000000Z",
      "gl": {
        "id": 12,
        "gl_number": "63063-00",
        "season": "FALL 23",
        "size_order": "0-3",
        "buyer_id": 7,
        "created_at": "2023-05-23T09:03:42.000000Z",
        "updated_at": "2023-05-23T09:03:42.000000Z",
        "buyer": {
          "id": 7,
          "name": "CHICO'S",
          "address": "INTERNATIONAL",
          "shipment_address": "INTERNATIONAL",
          "code": "CHICO'S",
          "created_at": "2023-05-04T10:05:38.000000Z",
          "updated_at": "2023-05-04T10:05:38.000000Z"
        }
      },
      "style": {
        "id": 18,
        "style": "202-23C111595",
        "description": "NOTCH NK LONG SLV TEE WITH GATHERS AT",
        "gl_id": 12,
        "created_at": "2023-05-23T09:03:42.000000Z",
        "updated_at": "2023-05-23T09:03:42.000000Z"
      },
      "color": {
        "id": 34,
        "color": "DARTING FLORAL - ROYAL",
        "color_code": "DRTING FRL - ROYAL",
        "created_at": "2023-05-23T02:11:12.000000Z",
        "updated_at": "2023-05-23T02:11:12.000000Z"
      }
    }
  },
  "cutting_order_record_detail": [
    {
      "id": 31,
      "cutting_order_record_id": 129,
      "fabric_roll": "15",
      "fabric_batch": "1#",
      "color_id": 34,
      "yardage": 83.4,
      "weight": 24.6,
      "layer": 14,
      "joint": 0,
      "balance_end": 0,
      "remarks": "-",
      "operator": "User Admin",
      "created_at": "2023-06-03T02:21:09.000000Z",
      "updated_at": "2023-06-03T02:21:09.000000Z"
    },
    {
      "id": 32,
      "cutting_order_record_id": 129,
      "fabric_roll": "4",
      "fabric_batch": "1#",
      "color_id": 34,
      "yardage": 81.9,
      "weight": 24.1,
      "layer": 14,
      "joint": 40,
      "balance_end": -2,
      "remarks": "-",
      "operator": "User Admin",
      "created_at": "2023-06-03T02:22:02.000000Z",
      "updated_at": "2023-06-03T02:22:02.000000Z"
    },
    {
      "id": 33,
      "cutting_order_record_id": 129,
      "fabric_roll": "16",
      "fabric_batch": "1#",
      "color_id": 34,
      "yardage": 79.6,
      "weight": 23.7,
      "layer": 13,
      "joint": 0,
      "balance_end": 2,
      "remarks": "-",
      "operator": "User Admin",
      "created_at": "2023-06-03T02:22:44.000000Z",
      "updated_at": "2023-06-03T02:22:44.000000Z"
    },
    {
      "id": 34,
      "cutting_order_record_id": 129,
      "fabric_roll": "10",
      "fabric_batch": "1#",
      "color_id": 34,
      "yardage": 80,
      "weight": 23.6,
      "layer": 9,
      "joint": 20,
      "balance_end": 26,
      "remarks": "-",
      "operator": "User Admin",
      "created_at": "2023-06-03T02:23:19.000000Z",
      "updated_at": "2023-06-03T02:23:19.000000Z"
    }
  ],
  "cutting_ticket": [
    {
      "id": 188,
      "ticket_number": 2,
      "size_id": 33,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 31,
      "table_number": 2,
      "fabric_roll": "15",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 33,
        "size": "1",
        "created_at": "2023-05-23T08:47:15.000000Z",
        "updated_at": "2023-05-23T08:47:15.000000Z"
      }
    },
    {
      "id": 189,
      "ticket_number": 3,
      "size_id": 33,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 32,
      "table_number": 2,
      "fabric_roll": "4",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 33,
        "size": "1",
        "created_at": "2023-05-23T08:47:15.000000Z",
        "updated_at": "2023-05-23T08:47:15.000000Z"
      }
    },
    {
      "id": 190,
      "ticket_number": 4,
      "size_id": 33,
      "layer": 13,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 33,
      "table_number": 2,
      "fabric_roll": "16",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 33,
        "size": "1",
        "created_at": "2023-05-23T08:47:15.000000Z",
        "updated_at": "2023-05-23T08:47:15.000000Z"
      }
    },
    {
      "id": 191,
      "ticket_number": 5,
      "size_id": 33,
      "layer": 9,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 34,
      "table_number": 2,
      "fabric_roll": "10",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 33,
        "size": "1",
        "created_at": "2023-05-23T08:47:15.000000Z",
        "updated_at": "2023-05-23T08:47:15.000000Z"
      }
    },
    {
      "id": 192,
      "ticket_number": 6,
      "size_id": 33,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 31,
      "table_number": 2,
      "fabric_roll": "15",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 33,
        "size": "1",
        "created_at": "2023-05-23T08:47:15.000000Z",
        "updated_at": "2023-05-23T08:47:15.000000Z"
      }
    },
    {
      "id": 193,
      "ticket_number": 7,
      "size_id": 33,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 32,
      "table_number": 2,
      "fabric_roll": "4",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 33,
        "size": "1",
        "created_at": "2023-05-23T08:47:15.000000Z",
        "updated_at": "2023-05-23T08:47:15.000000Z"
      }
    },
    {
      "id": 194,
      "ticket_number": 8,
      "size_id": 33,
      "layer": 13,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 33,
      "table_number": 2,
      "fabric_roll": "16",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 33,
        "size": "1",
        "created_at": "2023-05-23T08:47:15.000000Z",
        "updated_at": "2023-05-23T08:47:15.000000Z"
      }
    },
    {
      "id": 195,
      "ticket_number": 9,
      "size_id": 33,
      "layer": 9,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 34,
      "table_number": 2,
      "fabric_roll": "10",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 33,
        "size": "1",
        "created_at": "2023-05-23T08:47:15.000000Z",
        "updated_at": "2023-05-23T08:47:15.000000Z"
      }
    },
    {
      "id": 196,
      "ticket_number": 10,
      "size_id": 35,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 31,
      "table_number": 2,
      "fabric_roll": "15",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 35,
        "size": "2",
        "created_at": "2023-05-23T08:50:34.000000Z",
        "updated_at": "2023-05-27T07:18:49.000000Z"
      }
    },
    {
      "id": 197,
      "ticket_number": 11,
      "size_id": 35,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 32,
      "table_number": 2,
      "fabric_roll": "4",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 35,
        "size": "2",
        "created_at": "2023-05-23T08:50:34.000000Z",
        "updated_at": "2023-05-27T07:18:49.000000Z"
      }
    },
    {
      "id": 198,
      "ticket_number": 12,
      "size_id": 35,
      "layer": 13,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 33,
      "table_number": 2,
      "fabric_roll": "16",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 35,
        "size": "2",
        "created_at": "2023-05-23T08:50:34.000000Z",
        "updated_at": "2023-05-27T07:18:49.000000Z"
      }
    },
    {
      "id": 199,
      "ticket_number": 13,
      "size_id": 35,
      "layer": 9,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 34,
      "table_number": 2,
      "fabric_roll": "10",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 35,
        "size": "2",
        "created_at": "2023-05-23T08:50:34.000000Z",
        "updated_at": "2023-05-27T07:18:49.000000Z"
      }
    },
    {
      "id": 200,
      "ticket_number": 14,
      "size_id": 35,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 31,
      "table_number": 2,
      "fabric_roll": "15",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 35,
        "size": "2",
        "created_at": "2023-05-23T08:50:34.000000Z",
        "updated_at": "2023-05-27T07:18:49.000000Z"
      }
    },
    {
      "id": 201,
      "ticket_number": 15,
      "size_id": 35,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 32,
      "table_number": 2,
      "fabric_roll": "4",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 35,
        "size": "2",
        "created_at": "2023-05-23T08:50:34.000000Z",
        "updated_at": "2023-05-27T07:18:49.000000Z"
      }
    },
    {
      "id": 202,
      "ticket_number": 16,
      "size_id": 35,
      "layer": 13,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 33,
      "table_number": 2,
      "fabric_roll": "16",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 35,
        "size": "2",
        "created_at": "2023-05-23T08:50:34.000000Z",
        "updated_at": "2023-05-27T07:18:49.000000Z"
      }
    },
    {
      "id": 203,
      "ticket_number": 17,
      "size_id": 35,
      "layer": 9,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 34,
      "table_number": 2,
      "fabric_roll": "10",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 35,
        "size": "2",
        "created_at": "2023-05-23T08:50:34.000000Z",
        "updated_at": "2023-05-27T07:18:49.000000Z"
      }
    },
    {
      "id": 204,
      "ticket_number": 18,
      "size_id": 36,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 31,
      "table_number": 2,
      "fabric_roll": "15",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 36,
        "size": "3",
        "created_at": "2023-05-27T07:19:00.000000Z",
        "updated_at": "2023-05-27T07:19:00.000000Z"
      }
    },
    {
      "id": 205,
      "ticket_number": 19,
      "size_id": 36,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 32,
      "table_number": 2,
      "fabric_roll": "4",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 36,
        "size": "3",
        "created_at": "2023-05-27T07:19:00.000000Z",
        "updated_at": "2023-05-27T07:19:00.000000Z"
      }
    },
    {
      "id": 206,
      "ticket_number": 20,
      "size_id": 36,
      "layer": 13,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 33,
      "table_number": 2,
      "fabric_roll": "16",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 36,
        "size": "3",
        "created_at": "2023-05-27T07:19:00.000000Z",
        "updated_at": "2023-05-27T07:19:00.000000Z"
      }
    },
    {
      "id": 207,
      "ticket_number": 21,
      "size_id": 36,
      "layer": 9,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 34,
      "table_number": 2,
      "fabric_roll": "10",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 36,
        "size": "3",
        "created_at": "2023-05-27T07:19:00.000000Z",
        "updated_at": "2023-05-27T07:19:00.000000Z"
      }
    },
    {
      "id": 208,
      "ticket_number": 22,
      "size_id": 36,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 31,
      "table_number": 2,
      "fabric_roll": "15",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 36,
        "size": "3",
        "created_at": "2023-05-27T07:19:00.000000Z",
        "updated_at": "2023-05-27T07:19:00.000000Z"
      }
    },
    {
      "id": 209,
      "ticket_number": 23,
      "size_id": 36,
      "layer": 14,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 32,
      "table_number": 2,
      "fabric_roll": "4",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 36,
        "size": "3",
        "created_at": "2023-05-27T07:19:00.000000Z",
        "updated_at": "2023-05-27T07:19:00.000000Z"
      }
    },
    {
      "id": 210,
      "ticket_number": 24,
      "size_id": 36,
      "layer": 13,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 33,
      "table_number": 2,
      "fabric_roll": "16",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 36,
        "size": "3",
        "created_at": "2023-05-27T07:19:00.000000Z",
        "updated_at": "2023-05-27T07:19:00.000000Z"
      }
    },
    {
      "id": 211,
      "ticket_number": 25,
      "size_id": 36,
      "layer": 9,
      "cutting_order_record_id": 129,
      "cutting_order_record_detail_id": 34,
      "table_number": 2,
      "fabric_roll": "10",
      "created_at": "2023-06-03T02:33:02.000000Z",
      "updated_at": "2023-06-03T02:33:02.000000Z",
      "size": {
        "id": 36,
        "size": "3",
        "created_at": "2023-05-27T07:19:00.000000Z",
        "updated_at": "2023-05-27T07:19:00.000000Z"
      }
    }
  ]
} -->
<body>
    <div class="">
        <div class="header-main">
            <div class="company-name">
                Ghim Li Indonesia
                <br>
                Date: {{ date('d/m/Y') }}
                <br>
                </br>
                PAKING LIST
                <br>
                Style# : {{ $data['cutting_order_record']->layingPlanningDetail->layingPlanning->style->style }}
                <br>
                Job/PO# : {{ $data['cutting_order_record']->layingPlanningDetail->layingPlanning->gl->gl_number }}
            </div>
            <div>
                <br>
                <br>
                <br>
                <br>
            </div>

        </div>
        <div>
            </br>
            @php
                $size = $data['cutting_order_record']->cuttingTicket->pluck('size_id')->unique();
                $ticket = $data['cutting_order_record']->cuttingTicket->pluck('ticket_number')->unique();
            @endphp
            
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Ticket</td>
                                @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                                    <td>{{ $ct->size->size }}</td>
                                @endforeach
                            </tr>
                            @foreach($ticket as $tk)
                                <tr>
                                    <td>{{ $tk }}</td>
                                    @foreach($size as $sz)
                                        <td>
                                            @foreach($data['cutting_order_record']->cuttingTicket as $ct)
                                                @if($ct->size_id == $sz && $ct->ticket_number == $tk)
                                                    {{ $ct->ticket_number }}
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead class="">
                            <tr>
                                <th>Color</th>
                                @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                                    @for($i = 0; $i < $ct->ratio_per_size; $i++)
                                        <th>{{ $ct->size->size }}</th>
                                    @endfor
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['cutting_order_record']->cuttingOrderRecordDetail as $ct)
                                <tr>
                                    <td>{{ $ct->fabric_roll }}</td>
                                    @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $sz)
                                            @foreach($data['cutting_order_record']->cuttingTicket as $ctt)
                                                @if($ctt->size_id == $sz->size_id && $ctt->cutting_order_record_detail_id == $ct->id)
                                                <td> {{ $ctt->ticket_number }} </td>
                                                @endif
                                            @endforeach
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <table class="table table-bordered">
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
                        @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                            <td>{{ $ct->ratio_per_size * $ct->qty_per_size }}</td>
                        @endforeach
                        <td><?php 
                            $total = 0;
                            foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct){
                                $total += $ct->ratio_per_size * $ct->qty_per_size;
                            }
                            echo $total;
                        ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                            <td>{{ $ct->ratio_per_size * $ct->qty_per_size }}</td>
                        @endforeach
                        <td><?php 
                            $total = 0;
                            foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct){
                                $total += $ct->ratio_per_size * $ct->qty_per_size;
                            }
                            echo $total;
                        ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>

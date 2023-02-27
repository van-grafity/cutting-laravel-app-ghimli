// function modalCutting(add, id = null, url) {
//     var modal = $('#modalCutting'),
//         form = modal.find('form');
//     if (add) {
//         modal.find('.modal-title').text('Add Cutting');
//         // form.attr('action', '/cutting');
//         form.attr('method', 'POST');
//         form.find('input[name="_method"]').remove();
//         form.find('input[name="job_number"]').val('');
//         form.find('input[name="style_number"]').val('');
//         form.find('input[name="table_number"]').val('');
//         form.find('input[name="next_bundling"]').val('');
//         form.find('input[name="color"]').val('');
//         form.find('input[name="size"]').val('');
//         modal.modal('show');
//     } else {
//         modal.find('.modal-title').text('Edit Cutting');
//         form.attr('action', '/cutting/' + id);
//         form.attr('method', 'POST');
//         form.append('<input type="hidden" name="_method" value="PUT">');
//         $.ajax({
//             url: '/cutting/' + id,
//             method: 'GET',
//             success: function (res) {
//                 form.find('input[name="job_number"]').val(res.job_number);
//                 form.find('input[name="style_number"]').val(res.style_number);
//                 form.find('input[name="table_number"]').val(res.table_number);
//                 form.find('input[name="next_bundling"]').val(res.next_bundling);
//                 form.find('input[name="color"]').val(res.color);
//                 form.find('input[name="size"]').val(res.size);
//             }
//         }).then(function () {
//             modal.modal('show');
//         });
//     }
// }

function modalClothRoll(add, id = null) {
    var modal = $('#modalClothRoll'),
        form = modal.find('form');
    if (add) {
        modal.find('.modal-title').text('Add Cloth Roll');
        // form.attr('action', '/clothroll');
        form.attr('method', 'POST');
        form.find('input[name="_method"]').remove();
        form.find('input[name="po_id"]').val('');
        form.find('input[name="fabric_type"]').val('');
        form.find('input[name="color"]').val('');
        form.find('input[name="roll_no"]').val('');
        form.find('input[name="width"]').val('');
        form.find('input[name="length"]').val('');
        form.find('input[name="weight"]').val('');
        form.find('input[name="batch_no"]').val('');
        modal.modal('show');
    } else {
        modal.find('.modal-title').text('Edit Cloth Roll');
        form.attr('action', '/clothroll/' + id);
        form.attr('method', 'POST');
        form.append('<input type="hidden" name="_method" value="PUT">');
        $.ajax({
            url: '/clothroll/' + id,
            method: 'GET',
            success: function (res) {
                form.find('input[name="po_id"]').val(res.po_id);
                form.find('input[name="fabric_type"]').val(res.fabric_type);
                form.find('input[name="color"]').val(res.color);
                form.find('input[name="roll_no"]').val(res.roll_no);
                form.find('input[name="width"]').val(res.width);
                form.find('input[name="length"]').val(res.length);
                form.find('input[name="weight"]').val(res.weight);
                form.find('input[name="batch_no"]').val(res.batch_no);
            }
        }).then(function () {
            modal.modal('show');
        });
    }
}

function modalPurchaseOrder(add, id = null) {
    var modal = $('#modalPurchaseOrder'),
        form = modal.find('form');
    if (add) {
        modal.find('.modal-title').text('Add Purchase Order');
        // form.attr('action', '/purchaseorder');
        form.attr('method', 'POST');
        form.find('input[name="_method"]').remove();
        form.find('input[name="po_no"]').val('');
        form.find('input[name="vendor_id"]').val('');
        form.find('input[name="company_name"]').val('');
        form.find('input[name="product_id"]').val('');
        form.find('input[name="order_date"]').val('');
        modal.modal('show');
    } else {
        modal.find('.modal-title').text('Edit Purchase Order');
        form.attr('action', '/purchaseorder/' + id);
        form.attr('method', 'POST');
        form.append('<input type="hidden" name="_method" value="PUT">');
        $.ajax({
            url: '/purchaseorder/' + id,
            method: 'GET',
            success: function (res) {
                form.find('input[name="po_no"]').val(res.po_no);
                form.find('input[name="vendor_id"]').val(res.vendor_id);
                form.find('input[name="company_name"]').val(res.company_name);
                form.find('input[name="product_id"]').val(res.product_id);
                form.find('input[name="order_date"]').val(res.order_date);
            }
        }).then(function () {
            modal.modal('show');
        });
    }
}

function modalPurchaseOrder(add, id = null) {
    var modal = $('#modalPurchaseOrder'),
        form = modal.find('form');
    if (add) {
        modal.find('.modal-title').text('Add Purchase Order');
        // form.attr('action', '/purchaseorder');
        form.attr('method', 'POST');
        form.find('input[name="_method"]').remove();
        form.find('input[name="po_no"]').val('');
        form.find('input[name="vendor_id"]').val('');
        form.find('input[name="company_name"]').val('');
        form.find('input[name="product_id"]').val('');
        form.find('input[name="order_date"]').val('');
        modal.modal('show');
    } else {
        modal.find('.modal-title').text('Edit Purchase Order');
        form.attr('action', '/purchaseorder/' + id);
        form.attr('method', 'POST');
        form.append('<input type="hidden" name="_method" value="PUT">');
        $.ajax({
            url: '/purchaseorder/' + id,
            method: 'GET',
            success: function (res) {
                form.find('input[name="po_no"]').val(res.po_no);
                form.find('input[name="vendor_id"]').val(res.vendor_id);
                form.find('input[name="company_name"]').val(res.company_name);
                form.find('input[name="product_id"]').val(res.product_id);
                form.find('input[name="order_date"]').val(res.order_date);
            }
        }).then(function () {
            modal.modal('show');
        });
    }
}
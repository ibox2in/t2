<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>Лента заказов</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/style.css" />
    <link rel="stylesheet" type="text/css" href="../../public/css/css/ext-all.css" />
    <script type="text/javascript" src="../../public/js/ext.js"></script>
    <script type="text/javascript" src="../../public/js/ext-all.js"></script>
    <script type="text/javascript" src="../../public/js/jquery2.min.js"></script>
    <script type="text/javascript" src="../../public/js/jquery-ias.min.js"></script>
</head>
<body>
<?php
require_once("../util/session_util.php");
require_once("../util/user_util.php");
init_session();
if(!check_auth(true)) {
    die_with_redirect();
}


require_once("../util/order_util.php");
require_once("../../resources/config.php");

function asd() {
    echo get_config()["db"]["user_db"]["dbname"];
}


$orders = array();

if(intval($_GET["max_id"]) != 0) {
    $orders = get_orders_by_customer_id_and_max_id_and_limit($_SESSION["uid"], intval($_GET["max_id"]), PAGE_SIZE);
} else {
    $orders = get_orders_by_customer_id_and_limit($_SESSION["uid"], PAGE_SIZE);
}

$ids = array();


//$orders = get_orders_by_customer_id($_SESSION["uid"]);
//foreach($orders as $key => &$val) {
//    if($val["status"] == STATUS_COMPLETED) {
//        $val["status"] = "Выполнен";
//    } else {
//        $val["status"] = "Открыт";
//    }
//}
//echo json_encode($orders)

//add_user("test91", "test", 0);
//session_start();
//login("test91", "test");
//echo "suc";
//$orders = get_orders_page(298, 20);
//foreach($orders as $key => &$val) {
//    //echo $val["id"]."<br/>";
//}
?>

<!--  ========================================================  If customer  ================================  -->

<?php if(user_customer($_SESSION["uid"])) { ?>

    <script type="text/javascript">


    Ext.onReady(function() {

        var last_id = 0;

        Ext.create('Ext.form.Panel', {
            style: "margin: 0px auto 0px auto;",
            border: true,
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [{
                    xtype: 'tbfill'
                }, {
                    xtype: 'text',
                    text: '<?php echo $_SESSION["login"]; ?>'
                }, {
                    xtype: 'tbseparator',
                    style: 'margin: 5px; margin-right:0px'
                }, {
                    xtype: 'button',
                    text: '<?= _("Выход") ?>',
                    handler: function() {
                        window.location.href = "../login/logout_form.php";
                    }
                }]
            }],

            renderTo: Ext.get("header")
        });

        Ext.create('Ext.form.Panel', {

            title: 'Новый заказ',
            bodyPadding: 5,
            width: 300,
            url: '../customer/add_order_form.php',

            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },

            defaultType: 'textfield',
            items: [{
                fieldLabel: '<?= _("Название") ?>',
                name: 'title'
            }, {
                fieldLabel: '<?= _("Цена") ?>',
                name: 'price'
            }],
            style: "margin: 0px auto 0px auto;",
            buttonAlign: 'right',
            buttons: [{
                text: '<?= _("Опубликовать") ?>',
                formBind: true,
                //only enabled once the form is valid
                disabled: true,
                handler: function () {
                    var form = this.up('form').getForm();
                    if (form.isValid()) {
                        form.submit({
                            success: function (form, action) {
                                var data = Ext.JSON.decode(action.response.responseText);

                                form_row(data.response, function(list) {
                                    $(list).hide().prependTo("#order_list").fadeIn();
                                });

                            },
                            failure: function (result, request) {
                                if(result.status == 401) {
                                    Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Вы вышли") ?>', function() {
                                        window.location.href = "../../public/index.php";
                                    });
                                }
                            }
                        });
                    }
                }
            }],
            renderTo: Ext.get("add_order_form")
        });

        var order_list = Ext.get('order_list');

        var order_store = new Ext.data.JsonStore({
            storeId: 'order_store',
            proxy: {
                type: 'ajax',
                url: '../customer/order_list_form.php',
                reader: {
                    type: 'json'
                }
            },
            fields: [
                { name: 'id' },
                { name: 'title' },
                { name: 'price' },
                { name: 'status' },
                { name: 'contractor_id' }
            ]
        });
        order_store.on('load', function () {
            order_store.data.removeAt(order_store.data.length - 1);
            //return;
            order_store.data.each(function(item, index, totalItems) {

                if(last_id == 0) {
                    last_id = item.data.id;
                }
                last_id = Math.min(item.data.id, last_id);

                form_row(item.data, function(list) {
                    Ext.DomHelper.append(order_list, list);
                });

            });

            $(".row").on({
                mouseenter: function () {
                    $(this).addClass('hover');
                },
                mouseleave:function () {
                    $(this).removeClass('hover');
                }
            });


        });
        order_store.load({
            callback:function(records, operation, success){
                if(success == false){
                    if(operation.error.status == 401) {
                        Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Вы вышли") ?>', function() {
                            window.location.href = "../../public/index.php";
                        });
                    }
                }
            }
        });
        Ext.create('Ext.Button', {
            text: '<?= _("Обновить") ?>',
            renderTo: 'refresh_button',
            icon: '../../public/img/refresh.gif',
            listeners: {
                click: function() {

                    last_id = 0;
                    Ext.get('order_list').update('');
                    order_store.load({
                        callback:function(records, operation, success){
                            if(success == false){
                                if(operation.error.status == 401) {
                                    Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Вы вышли") ?>', function() {
                                        window.location.href = "../../public/index.php";
                                    });
                                }
                            }
                        }
                    });
                }
            }
        });

        $(window).scroll(function() {
            if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                Ext.getStore('order_store').loadRecords([]);
                Ext.getStore('order_store').load({
                    params: {
                        max_id: last_id
                    },
                    callback:function(records, operation, success){
                        if(success == false){
                            if(operation.error.status == 401) {
                                Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Вы вышли") ?>', function() {
                                    window.location.href = "../../public/index.php";
                                });
                            }
                        }
                    }
                });
            }
        });
    });

    function form_row(data, append) {
        var list = '<tr class="pt10 pb10 border_bottom row" id="tr_' + data.id + '">';

        var id = 'complete_' + data.id;

        var contractor = '';
        if(data.status == 0) {
            data.status = '<?= _("Открыт") ?>';
        } else {
            data.status = '<?= _("Выполнен") ?>';
            contractor = '<div><image src="../../public/img/customer.png" style="vertical-align: middle"/> <span style="vertical-align: middle">' + data.contractor_id + '</span></div>';
        }

        list += '<td width="100" class="pl10"><div>№ ' + data.id + '</div></td>';
        list += '<td>' + data.title + '</td>';
        list += '<td width="200"><div class="mb5">' + data.status + '</div>' + contractor + '</td>';
        list += '<td width="100" id="' + id + '"><div class="mb5"><image src="../../public/img/money.png" style="vertical-align: middle"/> <span style="vertical-align: middle">' + data.price + '</span></div></td>';

        list += '</tr>';

        append(list);


        Ext.create('Ext.Button', {
            text: '<?= _("Удалить") ?>',
            renderTo: id,
            icon: '../../public/img/delete.png',
            listeners: {
                click: function() {
                    $('#tr_' + data.id).fadeOut();
                    Ext.Ajax.request({
                        url: '../customer/delete_order_form.php',
                        method: 'POST',
                        params: {
                            order_id: data.id
                        },
                        failure: function (result, request) {
                            if(result.status == 401) {
                                Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Вы вышли") ?>', function() {
                                    window.location.href = "../../public/index.php";
                                });
                            }
                        }
                    });
                }
            }
        });
    }


    </script>

    <div class="ma base">

        <div class="rght mt20"></div>
        <div class="cntr">
            <div id="header"></div>
        </div>


        <div class="cntr">
            <div id="add_order_form" class="mt20"></div>
        </div>
        <div id="refresh_button" class="mt20"></div>
        <div class="hr mt10"></div>
        <table class="txt" width="720" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed">
            <tbody id="order_list" >
            </tbody>
        </table>

        <div class="mt20 mb20"></div>


    </div>


    </div>

<?php } ?>

<!--  ========================================================  If contractor  ================================  -->

<?php if(user_contractor($_SESSION["uid"])) { ?>

    <script type="text/javascript">

        Ext.onReady(function() {

            var last_id = 0;

            Ext.create('Ext.form.Panel', {
                style: "margin: 0px auto 0px auto;",
                border: true,
                dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [{
                        xtype: 'text',
                        text: '<?php echo $_SESSION["login"]; ?>',
                        style: 'margin-left: 5px'
                    }, {
                        xtype: 'tbseparator',
                        style: 'margin: 5px'
                    }, {
                        xtype: 'text',
                        id: 'account',
                        width: 500,
                        style: 'text-align: left',
                        text: '<?= _("На счету") ?>: <?php echo $_SESSION["account"]; ?>'
                    }, {
                        xtype: 'tbfill'
                    }, {
                        xtype: 'button',
                        text: '<?= _("Выход") ?>',
                        handler: function() {
                            window.location.href = "../login/logout_form.php";
                        }
                    }]
                }],

                renderTo: Ext.get("header")
            });


            var order_list = Ext.get('order_list');

            var order_store = new Ext.data.JsonStore({
                storeId: 'order_store',
                root: 'data',
                proxy: {
                    type: 'ajax',
                    url: '../contractor/order_list_form.php',
                    reader: {
                        type: 'json'
                    }
                },
                fields: [
                    { name: 'id' },
                    { name: 'title' },
                    { name: 'price' },
                    { name: 'customer_id' }
                ]
            });



            order_store.on('load', function () {
                order_store.data.removeAt(order_store.data.length - 1);
                //return;
                order_store.data.each(function(item, index, totalItems) {

                    if(last_id == 0) {
                        last_id = item.data.id;
                    }
                    last_id = Math.min(item.data.id, last_id);


                    var list = '<tr class="pt10 pb10 border_bottom row" id="tr_' + item.data.id + '">';

                    var id = 'complete_' + item.data.id;


                    list += '<td width="100" class="pl10"><div>№ ' + item.data.id + '</div></td>';
                    list += '<td>' + item.data.title + '</td>';
                    list += '<td width="200"><div><image src="../../public/img/customer.png" style="vertical-align: middle"/> <span style="vertical-align: middle">' + item.data.customer_id + '</span></div></td>';
                    list += '<td width="100" id="' + id + '"><div class="mb5"><image src="../../public/img/money.png" style="vertical-align: middle"/> <span style="vertical-align: middle">' + item.data.price + '</span></div></td>';

                    list += '</tr>';
                    Ext.DomHelper.append(order_list, list);

                    Ext.create('Ext.Button', {
                        text: '<?= _("Выполнить") ?>',
                        renderTo: id,
                        icon: '../../public/img/accept.png',
                        listeners: {
                            click: function() {
                                $('#tr_' + item.data.id).fadeOut();
                                Ext.Ajax.request({
                                    url: '../contractor/complete_order_form.php',
                                    method: 'POST',
                                    params: {
                                        order_id: item.data.id
                                    },
                                    success: function (result, request) {
                                        var data = Ext.JSON.decode(result.responseText);
                                        console.log(Ext.getCmp('account'));

                                        Ext.getCmp('account').setHeight('auto');
                                        Ext.getCmp('account').update('<?= _("На счету") ?>: ' + data.response.account);
                                        //Ext.getCmp('account').doLayout();
                                    },
                                    failure: function (result, request) {
                                        if(result.status == 401) {
                                            Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Вы вышли") ?>', function() {
                                                window.location.href = "../../public/index.php";
                                            });
                                        } else if(result.status == 410) {
                                            Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Заказ удален") ?>');
                                        } else if(result.status == 403) {
                                            Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Заказ уже выполнен") ?>');
                                        }
                                    }
                                });
                            }
                        }
                    });
                });

                $(".row").on({
                    mouseenter: function () {
                        $(this).addClass('hover');
                    },
                    mouseleave:function () {
                        $(this).removeClass('hover');
                    }
                });


            });
            order_store.load({
                callback:function(records, operation, success){
                    if(success == false){
                        if(operation.error.status == 401) {
                            Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Вы вышли") ?>', function() {
                                window.location.href = "../../public/index.php";
                            });
                        }
                    }
                }
            });
            Ext.create('Ext.Button', {
                text: '<?= _("Обновить") ?>',
                renderTo: 'refresh_button',
                icon: '../../public/img/refresh.gif',
                listeners: {
                    click: function() {
                        last_id = 0;
                        Ext.get('order_list').update('');
                        order_store.load({
                            callback:function(records, operation, success){
                                if(success == false){
                                    if(operation.error.status == 401) {
                                        Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Вы вышли") ?>', function() {
                                            window.location.href = "../../public/index.php";
                                        });
                                    }
                                }
                            }
                        });
                    }
                }
            });

            $(window).scroll(function() {
                if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                    Ext.getStore('order_store').loadRecords([]);
                    Ext.getStore('order_store').load({
                        params: {
                            max_id: last_id
                        },
                        callback:function(records, operation, success){
                            if(success == false){
                                if(operation.error.status == 401) {
                                    Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Вы вышли") ?>', function() {
                                        window.location.href = "../../public/index.php";
                                    });
                                }
                            }
                        }
                    });
                }
            });

        });
    </script>


    <div class="ma base">

        <div class="rght mt20"></div>
        <div class="cntr">
            <div id="header"></div>
        </div>
        <div>
            <div id="refresh_button" class="mt20"></div>
            <div class="hr mt10"></div>
            <table class="txt" width="720" border="0" cellspacing="0" cellpadding="0">
                <tbody id="order_list" >
                </tbody>
            </table>

            <div class="mt20 mb20"></div>


        </div>
    </div>

    </div>

<?php } ?>

</body>
</html>

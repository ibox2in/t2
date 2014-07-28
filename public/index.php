<html>
<head>
    <title>АСВЗ</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/style.css" />
    <link rel="stylesheet" type="text/css" href="../../public/css/css/ext-all.css" />
    <script type="text/javascript" src="../../public/js/ext.js"></script>
    <script type="text/javascript" src="../../public/js/ext-all.js"></script>
</head>
<body>

<script type="text/javascript">
    Ext.onReady(function() {
        var login_form = Ext.create('Ext.form.Panel', {

            bodyPadding: 5,
            width: 300,

            // The form will submit an AJAX request to this URL when submitted
            url: '../application/login/login_form.php',

            // Fields will be arranged vertically, stretched to full width
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },

            // The fields
            defaultType: 'textfield',
            items: [{
                fieldLabel: '<?= _("Логин") ?>',
                name: 'login'
            }, {
                fieldLabel: '<?= _("Пароль") ?>',
                name: 'password'
            }],

            style: "margin: 0px auto 0px auto;",


            buttonAlign: 'center',
            // Reset and Submit buttons
            buttons: [{
                text: '<?= _("Войти") ?>',
                formBind: true,
                //only enabled once the form is valid
                disabled: true,
                handler: function () {
                    var form = this.up('form').getForm();
                    if (form.isValid()) {
                        form.submit({
                            success: function (form, action) {
                                window.location.href = "../application/common/feed.php";
                            },
                            failure: function (form, action) {
                                Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Неправильный логин или пароль") ?>');
                            }
                        });
                    }
                }
            }, {
                text: '<?= _("Регистрация") ?>',
                formBind: true,
                //only enabled once the form is valid
                disabled: true,
                handler: function () {
                    login_form.hide();
                    Ext.get('registration_container').show();
                }
            }],
            renderTo: 'login_form'
        });

        Ext.create('Ext.form.Panel', {

            bodyPadding: 5,
            width: 300,

            // The form will submit an AJAX request to this URL when submitted
            url: '../application/registration/registration_form.php',

            // Fields will be arranged vertically, stretched to full width
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },

            // The fields
            defaultType: 'textfield',
            items: [{
                fieldLabel: '<?= _("Логин") ?>',
                name: 'login'
            }, {
                fieldLabel: '<?= _("Пароль") ?>',
                name: 'password'
            }, {
                xtype: 'radiogroup',
                columns: 1,
                items: [{
                    xtype: 'radiofield',
                    boxLabel: '<?= _("Заказчик") ?>',
                    checked: true,
                    name: 'type',
                    inputValue: 0
                }, {
                    xtype: 'radiofield',
                    boxLabel: '<?= _("Исполнитель") ?>',
                    name: 'type',
                    inputValue: 1
                }]
            }],

            style: "margin: 0px auto 0px auto;",


            buttonAlign: 'center',
            // Reset and Submit buttons
            buttons: [{
                text: '<?= _("Зарегистрироваться") ?>',
                formBind: true,
                //only enabled once the form is valid
                disabled: true,
                handler: function () {
                    var form = this.up('form').getForm();
                    if (form.isValid()) {
                        form.submit({
                            success: function (form, action) {
                                window.location.href = "../application/common/feed.php";
                            },
                            failure: function (form, action) {
                                Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Произошла какая то ошибка") ?>');
                            }
                        });
                    }
                }
            }, {
                text: '<?= _("Отмена") ?>',
                handler: function () {
                    login_form.show();
                    Ext.get('registration_container').hide();
                }
            }],
            renderTo: Ext.get("registration_form")
        });
    });
</script>

<div class="cntr" id="login_container">
    <div id="login_form" class="mt20"></div>
</div>

<div class="cntr" id="registration_container" style="visibility: hidden;">
    <div class="mt20 mb20"><?= _("Для регистрации выберите логин и пароль") ?></div>
    <div id="registration_form" class="mt20"</div>
</div>

</body>
</html>

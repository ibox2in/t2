<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>АСВЗ</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
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

            url: '../application/login/login_form.php',

            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },

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
            buttons: [{
                text: '<?= _("Войти") ?>',
                formBind: true,
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

            url: '../application/registration/registration_form.php',

            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },

            defaultType: 'textfield',
            items: [{
                fieldLabel: '<?= _("Логин") ?>',
                name: 'login',
                validator: function (value) {
                    if(value.length < 6 || value.length > 32) {
                        return "<?= _("Длина от 6 до 32 символов") ?>";
                    }
                    return true;
                }
            }, {
                fieldLabel: '<?= _("Пароль") ?>',
                name: 'password',
                validator: function (value) {
                    if(value.length == 0) {
                        return "<?= _("Обязательное поле") ?>";
                    }
                    return true;
                }
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
                                if(action.response.status == 406) {
                                    Ext.Msg.alert('<?= _("Ошибка") ?>', '<?= _("Неправильный ввод") ?>');
                                }
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

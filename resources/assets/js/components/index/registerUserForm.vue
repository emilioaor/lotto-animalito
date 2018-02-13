<template>
    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Formulario de registro</h3>
                </div>
                <div class="panel-body">

                    <form action="" method="post" novalidate v-on:submit="validateData($event)">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Email (No se puede cambiar)</label>
                                    <img
                                        src="img/loading.gif"
                                        alt="Cargando.."
                                        width="20px"
                                        v-show="loadingEmail"
                                    >
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        placeholder="Email"
                                        v-model="registerUserForm.email"
                                        v-validate
                                        v-on:blur="verifyEmailExists()"
                                        v-on:focus="emailChange=false;emailExists=true"
                                        data-vv-rules="required|email"
                                    >
                                    <p class="text-danger" v-show="send && hasError('email', 'required', errors)">
                                       Debe completar este campo
                                    </p>
                                    <p class="text-danger" v-show="send && hasError('email', 'email', errors)">
                                        Formato invalido
                                    </p>
                                    <p class="text-success" v-show="! errors.has('email') && ! emailExists && emailChange">
                                        Email valido!
                                    </p>
                                    <p class="text-danger" v-show="! errors.has('email') && emailExists && emailChange">
                                        Este email ya esta siendo usado
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="identity_card">Cédula de identidad (No se puede cambiar)</label>
                                    <img
                                            src="img/loading.gif"
                                            alt="Cargando.."
                                            width="20px"
                                            v-show="loadingIdentityCard"
                                            >
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="identity_card"
                                            name="identity_card"
                                            placeholder="Cédula de identidad"
                                            v-model="registerUserForm.identity_card"
                                            v-on:blur="verifyIdentityCardExists()"
                                            v-on:focus="identityCardChange=false;identityCardExists=true"
                                            v-validate
                                            data-vv-rules="required"
                                            >
                                    <p class="text-danger" v-show="send && hasError('identity_card', 'required', errors)">
                                        Debe completar este campo
                                    </p>
                                    <p class="text-danger" v-show="send && ! errors.has('identity_card') && ! validateIdentityCard()">
                                        Formato invalido
                                    </p>
                                    <p class="text-success" v-show="! errors.has('identity_card') && validateIdentityCard() && ! identityCardExists && identityCardChange">
                                        Todo bien!
                                    </p>
                                    <p class="text-danger" v-show="! errors.has('identity_card') && validateIdentityCard() && identityCardExists && identityCardChange">
                                        Esta cedula ya esta registrada
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input
                                            type="password"
                                            class="form-control"
                                            id="password"
                                            name="password"
                                            placeholder="Contraseña"
                                            v-model="registerUserForm.password"
                                            v-validate
                                            data-vv-rules="required|min:6|max:20|confirmed:password_confirmation"
                                            >
                                    <p class="text-danger" v-show="send && hasError('password', 'required', errors)">
                                        Debe completar este campo
                                    </p>
                                    <p class="text-danger" v-show="send && hasError('password', 'min', errors)">
                                        Minimo 6 caracteres
                                    </p>
                                    <p class="text-danger" v-show="send && hasError('password', 'max', errors)">
                                        Maximo 20 caracteres
                                    </p>
                                    <p class="text-danger" v-show="send && hasError('password', 'confirmed', errors)">
                                        Las contraseñas no coinciden
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Repetir contraseña</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Repetir contraseña"
                                        v-model="registerUserForm.password_confirmation"
                                        v-validate
                                        data-vv-rules="required|min:6|max:20|confirmed:password"
                                    >
                                    <p class="text-danger" v-show="send && hasError('password_confirmation', 'required', errors)">
                                        Debe completar este campo
                                    </p>
                                    <p class="text-danger" v-show="send && hasError('password_confirmation', 'min', errors)">
                                        Minimo 6 caracteres
                                    </p>
                                    <p class="text-danger" v-show="send && hasError('password_confirmation', 'max', errors)">
                                        Maximo 20 caracteres
                                    </p>
                                    <p class="text-danger" v-show="send && hasError('password_confirmation', 'confirmed', errors)">
                                        Las contraseñas no coinciden
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Nombre completo</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="name"
                                            name="name"
                                            placeholder="Nombre completo"
                                            v-model="registerUserForm.name"
                                            v-validate
                                            data-vv-rules="required"
                                            >
                                    <p class="text-danger" v-show="send && hasError('name', 'required', errors)">
                                        Debe completar este campo
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="bank_id">Banco</label>
                                    <select
                                        name="bank_id"
                                        id="bank_id"
                                        class="form-control"
                                        v-model="registerUserForm.bank_id"
                                    >

                                            <!-- Lista de bancos -->
                                            <option value="0">- Selecciona un banco -</option>
                                            <option
                                                v-for="bank in bankList"
                                                v-bind:value="bank.id"
                                            >
                                                {{ bank.name }}
                                            </option>
                                    </select>
                                    <p class="text-danger" v-show="send && registerUserForm.bank_id == 0">
                                        Debe completar este campo
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="number_account">Número de cuenta</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="number_account"
                                        name="number_account"
                                        maxlength="20"
                                        placeholder="Número de cuenta"
                                        v-model="registerUserForm.number_account"
                                        v-validate
                                        data-vv-rules="required|regex:^[0-9]{20}$"
                                    >
                                    <p class="text-danger" v-show="send && hasError('number_account', 'required', errors)">
                                        Debe completar este campo
                                    </p>
                                    <p class="text-danger" v-show="send && hasError('number_account', 'regex', errors)">
                                        Formato invalido
                                    </p>
                                </div>
                            </div>

                        </div>

                        <p>
                            <a v-bind:href="login_url">
                                <i class="glyphicon glyphicon-log-in"></i>
                                ¿Ya tienes cuenta?. Inicia sesión.
                            </a>
                        </p>

                        <button class="btn btn-primary" v-if="! loading">
                            <i class="glyphicon glyphicon-thumbs-up"></i> Registro
                        </button>
                        <img src="img/loading.gif" alt="Cargando.." v-show="loading">
                    </form>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props : ['banks', 'login_url'],
        data: function() {
            return {
                send: false,
                emailChange: false,
                emailExists: true,
                identityCardChange: false,
                identityCardExists: true,
                bankList: JSON.parse(this.banks),
                loading: false,
                loadingEmail: false,
                loadingIdentityCard: false,

                registerUserForm: {
                    email: '',
                    password: '',
                    password_confirmation: '',
                    name: '',
                    identity_card: '',
                    number_account: '',
                    bank_id: 0,
                }
            }
        },
        methods: {

            //  Procedimiento al enviar form
            validateData: function (evt) {
                this.send = true;
                evt.preventDefault();

                // Validar si hay errores
                this.$validator.validateAll().then((result) => {

                    if (result &&
                        this.validateIdentityCard() &&
                        ! this.identityCardExists &&
                        ! this.emailExists &&
                        parseInt(this.registerUserForm.bank_id) > 0
                    ) {
                        this.registerUser();
                    }

                });
            },

            //  Verifica si existe un error para un campo determinado
            hasError: function(field, rule, errors) {
                for (var err in errors.errors) {
                    //  Verifica si el campo tiene errores
                    if (errors.errors[err].field === field) {

                        if (errors.errors[err].rule === rule) {
                            //  Si es el error que estoy validando
                            return true;
                        }

                        return false;
                    }
                }

                return false;
            },

            //  Valida si la cedula cumple con el formato correcto
            validateIdentityCard: function() {
                var reg = new RegExp('^[1-9]{1}[0-9]{6,7}$');

                return reg.test(this.registerUserForm.identity_card);
            },

            //  Peticion para registrar usuario
            registerUser: function () {
                this.loading = true;

                axios.post('/register', this.registerUserForm).then(response => {

                    if (response.data.success) {
                        location.href = response.data.redirect;
                    } else {
                        this.loading = false;
                    }
                }).catch(response => {
                    this.loading = false;
                });

            },

            // Valida si un email esta disponible
            verifyEmailExists: function () {
                if (! this.hasError('email', 'email', this.errors) && this.registerUserForm.email !== '') {
                    this.emailChange = true;
                    this.loadingEmail = true;

                    axios.get('/emailExists/' + this.registerUserForm.email).then((data) => {
                        this.emailExists  = data.data.exists;
                        this.loadingEmail = false;
                    }).catch(response => {
                        this.loadingEmail = false;
                    });

                }

            },

            // Valida si la cedula esta disponible
            verifyIdentityCardExists: function () {
                if (this.validateIdentityCard()) {
                    this.identityCardChange = true;
                    this.loadingIdentityCard = true;

                    axios.get('/identityCardExists/' + this.registerUserForm.identity_card).then((data) => {
                        this.identityCardExists  = data.data.exists;
                        this.loadingIdentityCard = false;
                    }).catch(response => {
                        this.loadingIdentityCard = false;
                    });

                }

            },
        }
    }
</script>

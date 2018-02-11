<template>
    <div class="row">

        <div class="col-sm-8">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Credenciales de usuario</h3>
                </div>
                <div class="panel-body">

                    <p class="text-danger" v-show="invalid">
                        Credenciales invalidas
                    </p>

                    <form action="" method="post" v-on:submit="validateData($event)">

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Email"
                                v-model="loginUserForm.email"
                                v-validate
                                data-vv-rules="required|email"
                            >
                            <p class="text-danger" v-show="send && hasError('email', 'required', errors)">
                                Debe completar este campo
                            </p>
                            <p class="text-danger" v-show="send && hasError('email', 'email', errors)">
                                Formato invalido
                            </p>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Contraseña"
                                v-model="loginUserForm.password"
                                v-validate
                                data-vv-rules="required|min:6|max:20"
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
                        </div>

                        <button class="btn btn-primary" v-show="! loading">
                            <i class="glyphicon glyphicon-off"></i> Login
                        </button>
                        <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
                    </form>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                send: false,
                loading: false,
                invalid: false,

                loginUserForm: {
                    email: '',
                    password: '',
                }
            }
        },

        methods: {
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

            //  Valida la data
            validateData: function (evt) {
                this.send = true;
                this.invalid = false;
                evt.preventDefault();

                this.$validator.validateAll().then(result => {

                    if (result) {
                        this.loginUser();
                    }

                });
            },

            // Peticion para login
            loginUser: function() {
                this.loading = true;

                axios.post('/login', this.loginUserForm).then(response => {

                    if (response.data.success) {
                        location.href = response.data.redirect;
                    } else {
                        this.invalid = true;
                        this.loginUserForm.password = '';
                        this.loading = false;
                    }

                    console.log(response);
                }).catch(response => {
                    this.loading = false;
                    this.invalid = true;
                    this.loginUserForm.password = '';
                });
            }
        }
    }
</script>
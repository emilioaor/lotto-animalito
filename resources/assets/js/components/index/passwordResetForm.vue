<template>
    <section>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Recuperación de contraseña</h3>
            </div>
            <div class="panel-body">

                <div class="alert alert-danger" v-show="invalid">
                    <p class="text-danger">
                        No existe el correo
                    </p>
                </div>

                <form action="" method="post" v-on:submit.prevent="validateData()">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Email"
                            v-model="passwordResetForm.email"
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

                    <button class="btn btn-primary" v-show="! loading">
                        <i class="glyphicon glyphicon-envelope"></i> Recuperar
                    </button>
                    <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
                </form>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        data: function () {
            return {
                send: false,
                loading: false,
                invalid: false,

                passwordResetForm: {
                    email: '',
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
            validateData: function () {
                this.send = true;
                this.invalid = false;

                this.$validator.validateAll().then(result => {

                    if (result) {
                        this.resetPassword();
                    }

                });

            },

            // Peticion para login
            resetPassword: function() {
                this.loading = true;

                axios.post('/restorePassword', this.passwordResetForm).then(response => {

                    if (response.data.success) {
                        location.href = response.data.redirect;
                    } else {
                        this.loading = false;
                        this.invalid = true;
                    }

                }).catch(response => {
                    this.loading = false;
                    this.invalid = true;
                });
            }
        }
    }
</script>
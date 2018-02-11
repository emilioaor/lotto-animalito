<template>
    <form v-on:submit.prevent="validateData()">

        <div class="alert alert-danger" v-show="currentPasswordInvalid">
            Contraseña actual es incorrecta
        </div>

        <div class="alert alert-success" v-show="updatePasswordSuccess">
            Contraseña actualizada
        </div>

        <div class="row">

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="current_password">Contraseña actual</label>
                    <input
                        type="password"
                        class="form-control"
                        name="current_password"
                        id="current_password"
                        placeholder="Contraseña actual"
                        v-model="changePasswordForm.current_password"
                        v-validate
                        data-vv-rules="required|min:6|max:20"
                    >
                    <p class="text-danger" v-show="send && hasError('current_password', 'required', errors)">
                        Este campo es requerido
                    </p>
                    <p class="text-danger" v-show="send && hasError('current_password', 'min', errors)">
                        Minimo 6 caracteres
                    </p>
                    <p class="text-danger" v-show="send && hasError('current_password', 'max', errors)">
                        Maximo 20 caracteres
                    </p>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="new_password">Nueva contraseña</label>
                    <input
                        type="password"
                        class="form-control"
                        name="new_password"
                        id="new_password"
                        placeholder="Nueva contraseña"
                        v-model="changePasswordForm.new_password"
                        v-validate
                        data-vv-rules="required|min:6|max:20|confirmed:new_password_confirmation"
                    >
                    <p class="text-danger" v-show="send && hasError('new_password', 'required', errors)">
                        Este campo es requerido
                    </p>
                    <p class="text-danger" v-show="send && hasError('new_password', 'min', errors)">
                        Minimo 6 caracteres
                    </p>
                    <p class="text-danger" v-show="send && hasError('new_password', 'max', errors)">
                        Maximo 20 caracteres
                    </p>
                    <p class="text-danger" v-show="send && hasError('new_password', 'confirmed', errors)">
                        Las contraseñas no coinciden
                    </p>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="new_password_confirmation">Confirma nueva contraseña</label>
                    <input
                        type="password"
                        class="form-control"
                        name="new_password_confirmation"
                        id="new_password_confirmation"
                        placeholder="Nueva contraseña"
                        v-model="changePasswordForm.new_password_confirmation"
                        v-validate
                        data-vv-rules="required|min:6|max:20|confirmed:new_password"
                    >
                    <p class="text-danger" v-show="send && hasError('new_password_confirmation', 'required', errors)">
                        Este campo es requerido
                    </p>
                    <p class="text-danger" v-show="send && hasError('new_password_confirmation', 'min', errors)">
                        Minimo 6 caracteres
                    </p>
                    <p class="text-danger" v-show="send && hasError('new_password_confirmation', 'max', errors)">
                        Maximo 20 caracteres
                    </p>
                    <p class="text-danger" v-show="send && hasError('new_password_confirmation', 'confirmed', errors)">
                        Las contraseñas no coinciden
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <button class="btn btn-info" v-show="! loading">
                    <i class="glyphicon glyphicon-exclamation-sign"></i>
                    Cambiar contraseña
                </button>

                <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
            </div>
        </div>

    </form>
</template>

<script>
    export default {
        data: function() {
            return {
                send: false,
                loading: false,
                currentPasswordInvalid: false,
                updatePasswordSuccess: false,
                changePasswordForm: {
                    current_password: null,
                    new_password: null,
                    new_password_confirmation: null,
                },
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

            validateData: function () {
                this.send = true;
                this.currentPasswordInvalid = false;
                this.updatePasswordSuccess = false;

                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.changePassword();
                    }
                });
            },

            changePassword: function () {
                this.loading = true;

                axios.post('/user/changePassword', this.changePasswordForm).then(response => {

                    if (response.data.success) {

                        this.updatePasswordSuccess = true;
                        this.changePasswordForm = {
                            current_password: null,
                            new_password: null,
                            new_password_confirmation: null,
                        };
                        this.send = false;

                        window.setTimeout(() => {
                            this.updatePasswordSuccess = false;
                        }, 5000);

                    } else {

                        if (response.data.currentPasswordInvalid) {
                            this.currentPasswordInvalid = true;

                            window.setTimeout(() => {
                                this.currentPasswordInvalid = false;
                            }, 5000);
                        }
                    }

                    this.loading = false;

                }).catch(response => {
                    this.loading = false;
                });
            },
        }
    }
</script>
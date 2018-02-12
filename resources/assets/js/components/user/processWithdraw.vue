<template>
    <form v-on:submit.prevent="validateData()">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Contraseña"
                            v-model="processWithdrawForm.password"
                            v-validate
                            data-vv-rules="required|min:6|max:20"
                    >
                    <p class="text-danger" v-show="send && hasError('password', 'required', errors)">
                        Este campo es requerido
                    </p>
                    <p class="text-danger" v-show="send && hasError('password', 'min', errors)">
                        Minimo 6 caracteres
                    </p>
                    <p class="text-danger" v-show="send && hasError('password', 'max', errors)">
                        Maximo 20 caracteres
                    </p>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="capture">Captura de pantalla (Opcional)</label>
                    <input
                            type="file"
                            name="capture"
                            id="capture"
                            class="form-control"
                            placeholder="Captura de pantalla"
                            @change="setCapture()"
                            v-validate
                            data-vv-rules="mimes:image/jpeg,image/png|size:1024"
                            >
                    <p
                            class="text-danger"
                            v-show="send && hasError('capture', 'required', errors)"
                            >
                        Este campo es requerido
                    </p>
                    <p
                            class="text-danger"
                            v-show="send && hasError('capture', 'mimes', errors)"
                            >
                        Captura necesita ser formato .png o .jpg
                    </p>
                    <p
                            class="text-danger"
                            v-show="send && hasError('capture', 'size', errors)"
                            >
                        Maximo 1 mb
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <img v-bind:src="processWithdrawForm.capture" class="img-responsive">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <button class="btn btn-success" v-show="! loading">
                        <i class="glyphicon glyphicon-check"></i>
                        Aprobar
                    </button>

                    <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        props: ['accept_url'],

        data: function () {
            return {
                send: false,
                loading: false,
                url: this.accept_url,

                processWithdrawForm: {
                    password: null,
                    capture: null,
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

            validateData: function () {
                this.send = true;

                this.$validator.validateAll().then(result => {

                    if (result) {
                        this.processWithdraw();
                    }
                });
            },

            processWithdraw: function() {
                this.loading = true;

                axios.post(this.url, this.processWithdrawForm).then(response => {

                    if (response.data.success) {
                        location.href = response.data.redirect;
                    } else {
                        this.loading = false;
                        alert('Error interno, intente de nuevo');
                    }

                }).catch(response => {
                    this.loading = false;
                });
            },

            setCapture: function() {
                const file = $('#capture')[0].files[0];
                const reader = new FileReader();

                reader.addEventListener('load', () => {
                    this.processWithdrawForm.capture = reader.result;
                });

                reader.readAsDataURL(file);

            },
        }
    }
</script>
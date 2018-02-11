<template>
    <form v-on:submit.prevent="validateData()">

        <div class="alert alert-danger" v-if="passwordInvalid">
            Contraseña invalida
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="amount">Monto a solicitar</label>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Monto a solicitar"
                        id="amount"
                        name="amount"
                        v-model="withdrawForm.amount"
                        v-validate
                        data-vv-rules="required|numeric|min_value:5000"
                    >
                    <p
                        class="text-danger"
                        v-show="send && hasError('amount', 'required', errors)"
                    >
                        Este campo es requerido
                    </p>
                    <p
                        class="text-danger"
                        v-show="send && hasError('amount', 'numeric', errors)"
                    >
                        Formato invalido
                    </p>
                    <p
                        class="text-danger"
                        v-show="send && hasError('amount', 'min_value', errors)"
                    >
                        El minimo de cobro es 5000 Bsf
                    </p>
                    <p
                        class="text-danger"
                        v-show="send && ! errors.has('amount') && withdrawForm.amount > available"
                    >
                        Solo posee {{ available }} Bsf. disponibles
                    </p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="amount">Contraseña</label>
                    <input
                        type="password"
                        class="form-control"
                        placeholder="Contraseña"
                        id="password"
                        name="password"
                        v-model="withdrawForm.password"
                        v-validate
                        data-vv-rules="required|min:6|max:20"
                    >
                    <p
                        class="text-danger"
                        v-show="send && hasError('password', 'required', errors)"
                    >
                        Este campo es requerido
                    </p>
                    <p
                        class="text-danger"
                        v-show="send && hasError('password', 'min', errors)"
                    >
                        Minimo 6 caracteres
                    </p>
                    <p
                        class="text-danger"
                        v-show="send && hasError('password', 'max', errors)"
                    >
                        Maximo 20 caracteres
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <button class="btn btn-success" v-show="! loading">
                        <i class="glyphicon glyphicon-usd"></i>
                        Solicitar retiro
                    </button>

                    <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
                </div>
            </div>
        </div>

    </form>
</template>

<script>
    export default {
        props: ['balance', 'block_balance'],
        data: function() {
            return {
                send: false,
                loading: false,
                available: this.balance - this.block_balance,
                passwordInvalid:  false,
                withdrawForm: {
                    amount: null,
                    password: null,
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

            //  Valida la data ante de solicitar el pago
            validateData: function() {
                this.send = true;
                this.passwordInvalid = false;

                this.$validator.validateAll().then(result => {
                    if (result && this.available >= this.withdrawForm.amount) {
                        this.withdrawRequest();
                    }
                })
            },

            // Solicita el cobro
            withdrawRequest: function() {
                this.loading = true;

                axios.post('/user/withdraw', this.withdrawForm).then(response => {

                    if (response.data.success) {
                        location.href = response.data.redirect;
                    } else {
                        if (response.data.passwordInvalid) {
                            this.passwordInvalid = true;
                        }

                        this.loading = false;
                    }

                }).catch(response => {
                    this.loading = false;
                });
            },
        },
    }
</script>
<template>
    <form v-on:submit.prevent="validateData()">
        <div class="alert alert-success" v-show="updateBankSuccess">
            <p>Datos de la cuenta actualizados</p>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="bank_id">Banco</label>
                    <select
                            name="bank_id"
                            id="bank_id"
                            class="form-control"
                            v-model="bankUpdateForm.bank_id"
                            v-validate
                            data-vv-rules="regex:^[1-9]{1}[0-9]*$"
                            >
                        <option value="0">- Banco -</option>
                        <option
                                v-for="bank in bankList"
                                v-bind:value="bank.id"
                                >
                            {{ bank.name }}
                        </option>
                    </select>
                    <p
                            class="text-danger"
                            v-show="send && hasError('bank_id', 'regex', errors)"
                            >
                        Este campo es requerido
                    </p>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="number_account">Número de cuenta</label>
                    <input
                            type="text"
                            class="form-control"
                            name="number_account"
                            id="number_account"
                            placeholder="Número de cuenta"
                            v-model="bankUpdateForm.number_account"
                            v-validate
                            data-vv-rules="required|regex:^[0-9]{20}$"
                            >
                    <p
                            class="text-danger"
                            v-show="send && hasError('number_account', 'required', errors)"
                            >
                        Este campo es requerido
                    </p>
                    <p
                            class="text-danger"
                            v-show="send && hasError('number_account', 'regex', errors)"
                            >
                        Formato invalido
                    </p>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="name">Nombre para cobro</label>
                    <input
                        type="text"
                        class="form-control"
                        name="name"
                        id="name"
                        placeholder="Nombre para cobro"
                        v-model="bankUpdateForm.name"
                        v-validate
                        data-vv-rules="required|max:50"
                    >
                    <p
                        class="text-danger"
                        v-show="send && hasError('name', 'required', errors)"
                    >
                        Este campo es requerido
                    </p>
                    <p
                        class="text-danger"
                        v-show="send && hasError('name', 'max', errors)"
                    >
                        Maximo 50 caracteres
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="identity_card">Cédula de identidad</label>
                    <input
                        type="text"
                        class="form-control"
                        name="identity_card"
                        id="identity_card"
                        placeholder="Cédula de identidad"
                        v-model="bankUpdateForm.identity_card"
                        v-validate
                        data-vv-rules="required|numeric|min:7|max:8"
                    >
                    <p
                        class="text-danger"
                        v-show="send && hasError('identity_card', 'required', errors)"
                    >
                        Este campo es requerido
                    </p>
                    <p
                        class="text-danger"
                        v-show="send && (
                            hasError('identity_card', 'numeric', errors) ||
                            hasError('identity_card', 'min', errors) ||
                            hasError('identity_card', 'max', errors)
                        )"
                    >
                        Formato invalido
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <button class="btn btn-primary" v-show="! loading">
                    <i class="glyphicon glyphicon-edit"></i>
                    Actualizar datos
                </button>

                <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        props: [
            'banks',
            'bank_id',
            'number_account',
            'name',
            'identity_card',
        ],
        data: function() {
            return {
                send: false,
                loading: false,
                updateBankSuccess: false,
                bankList: JSON.parse(this.banks),
                bankUpdateForm: {
                    bank_id: this.bank_id,
                    number_account: this.number_account,
                    name: this.name,
                    identity_card: this.identity_card,
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
                        this.updateBankData();
                    }
                });
            },

            updateBankData: function() {
                this.loading = true;
                axios.post('/user/bankUpdate', this.bankUpdateForm).then(response => {

                    if (response.data.success) {

                        this.updateBankSuccess = true;

                        window.setTimeout(() => {
                            this.updateBankSuccess = false;
                        }, 5000);

                    }

                    this.loading = false;

                }).catch(response => {
                    this.loading = false;
                });
            },
        }
    }
</script>
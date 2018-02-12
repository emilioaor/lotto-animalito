<template>
    <form id="transferForm" v-on:submit.prevent="validateData()" enctype="multipart/form-data">

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="from_id">Origen</label>
                    <select
                            name="from_id"
                            id="from_id"
                            class="form-control"
                            v-model="transferForm.from_id"
                            v-validate
                            data-vv-rules="regex:^[1-9]{1}[0-9]*$"
                            >
                        <option value="0">- Origen -</option>
                        <option
                                v-for="bank in bankList"
                                v-bind:value="bank.id"
                                >
                            {{ bank.name }}
                        </option>
                    </select>
                    <p
                        class="text-danger"
                        v-show="send && hasError('from_id', 'regex', errors)"
                    >
                        Este campo es requerido
                    </p>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="to_id">Destino</label>
                    <select
                            name="to_id"
                            id="to_id"
                            class="form-control"
                            v-model="transferForm.to_id"
                            v-validate
                            data-vv-rules="regex:^[1-9]{1}[0-9]*$"
                            >
                        <option value="0">- Destino -</option>
                        <option
                                v-for="bank in bankList"
                                v-bind:value="bank.id"
                                v-if="bank.i_have"
                                >
                            {{ bank.name }}
                        </option>
                    </select>
                    <p
                        class="text-danger"
                        v-show="send && hasError('to_id', 'regex', errors)"
                    >
                        Este campo es requerido
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="references">Referencia</label>
                    <input
                        type="text"
                        name="references"
                        id="references"
                        class="form-control"
                        placeholder="Referencia"
                        v-model="transferForm.references"
                        v-validate
                        data-vv-rules="required"
                    >
                    </input>
                    <p
                        class="text-danger"
                        v-show="send && hasError('references', 'required', errors)"
                    >
                        Este campo es requerido
                    </p>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="amount">Monto</label>
                    <input
                            type="number"
                            name="amount"
                            id="amount"
                            class="form-control"
                            placeholder="Monto"
                            v-model="transferForm.amount"
                            v-validate
                            data-vv-rules="required"
                            >
                    </input>
                    <p
                            class="text-danger"
                            v-show="send && hasError('amount', 'required', errors)"
                            >
                        Este campo es requerido
                    </p>
                    <p
                            class="text-danger"
                            v-show="send && !hasError('amount', 'required', errors) && transferForm.amount < minAmount"
                            >
                        El minimo es {{ minAmount }}
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
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
                    <img v-bind:src="transferForm.capture" class="img-responsive">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <button class="btn btn-success" v-show="! loading">
                        <i class="glyphicon glyphicon-transfer"></i>
                        Recargar
                    </button>

                    <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
                </div>
            </div>
        </div>

    </form>
</template>

<script>
    export default {
        props: ['banks'],
        data: function () {
            return {
                send: false,
                loading: false,
                minAmount: 5000,
                bankList: JSON.parse(this.banks),
                transferForm: {
                    from_id: 0,
                    to_id: 0,
                    amount: null,
                    references: null,
                    capture: null
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

            //  Valida la data antes de registrar
            validateData: function() {
                this.send = true;

                this.$validator.validateAll().then(result => {
                    if (result && this.transferForm.amount >= this.minAmount) {
                        this.registerTransfer();
                    }

                });
            },

            registerTransfer: function() {
                this.loading = true;

                axios.post('/user/transfer', this.transferForm, {'content-type': 'multipart/form-data'}).then(response => {

                    if (response.data.success) {
                        location.href = response.data.redirect;
                    } else {
                        this.loading = false;
                    }

                }).catch(response => {
                    this.loading = false;
                });
            },

            setCapture: function() {
                const file = $('#capture')[0].files[0];
                const reader = new FileReader();

                reader.addEventListener('load', () => {
                    this.transferForm.capture = reader.result;
                });

                reader.readAsDataURL(file);

            },
        }
    }
</script>
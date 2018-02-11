<template>
    <section class="process-transfer">
        <div class="row">
            <div class="col-xs-6 col-sm-3 col-sm-offset-3 text-center">
                <button class="btn btn-success" v-on:click="changeTo('approved')" v-bind:disabled="approved">
                    <i class="glyphicon glyphicon-check"></i>
                    Aprobar
                </button>
            </div>

            <div class="col-xs-6 col-sm-3 text-center">
                <button class="btn btn-danger" v-on:click="changeTo('rejected')" v-bind:disabled="rejected">
                    <i class="glyphicon glyphicon-remove-sign"></i>
                    Rechazar
                </button>
            </div>
        </div>

        <div class="row" v-if="approved">
            <div class="col-sm-6 col-sm-offset-3">
                <form v-bind:action="accept_url" v-on:submit.prevent="validateData(1)">
                    <div class="form-group">
                        <label for="amount">Monto aprobado</label>
                        <input
                                type="text"
                                class="form-control"
                                name="amount"
                                id="amount"
                                placeholder="Monto"
                                v-model="changeStatusForm.amount"
                                v-validate
                                data-vv-rules="required|numeric|regex:^[1-9]{1}[0-9]*$"
                                >
                        <p
                                class="text-danger"
                                v-if="send && hasError('amount', 'required', errors)">
                            Este campo es requerido
                        </p>
                        <p
                                class="text-danger"
                                v-if="send && hasError('amount', 'numeric', errors)">
                            Formato invalido
                        </p>
                        <p
                                class="text-danger"
                                v-if="send && hasError('amount', 'regex', errors)">
                            No puede aprobar monto en 0
                        </p>
                        <p
                                class="text-danger"
                                v-if="send && !errors.has('amount') && changeStatusForm.amount > max_amount">
                            No puede aprobar mas de {{ max_amount }}
                        </p>
                    </div>

                    <div class="form-group text-center">
                        <button class="btn btn-success" v-show="! loading">
                            <i class="glyphicon glyphicon-check"></i>
                            Aprobar
                        </button>

                        <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
                    </div>
                </form>
            </div>
        </div>

        <div class="row" v-if="rejected">
            <div class="col-sm-6 col-sm-offset-3">
                <form v-bind:action="rejected_url" v-on:submit.prevent="validateData(2)">
                    <div class="form-group">
                        <label for="comment">Comentario adicional</label>
                        <textarea
                                name="comment"
                                id="comment"
                                cols="30"
                                rows="4"
                                class="form-control"
                                placeholder="Comentarios adicionales.."
                                v-model="changeStatusForm.comment"
                                v-validate
                                data-vv-rules="required"
                            ></textarea>
                        <p
                                class="text-danger"
                                v-if="send && hasError('comment', 'required', errors)">
                            Este campo es requerido
                        </p>
                    </div>

                    <div class="form-group text-center">
                        <button class="btn btn-danger" v-show="! loading">
                            <i class="glyphicon glyphicon-remove-sign"></i>
                            Rechazar
                        </button>

                        <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props: ['accept_url', 'rejected_url', 'max_amount'],

        data: function() {
            return {
                send: false,
                approved: false,
                rejected: false,
                loading: false,
                changeStatusForm: {
                    amount: 0,
                    comment: '',
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

            changeTo: function(status) {
                if (status == 'approved') {
                    this.approved = true;
                    this.rejected = false;
                    this.changeStatusForm.comment = '';
                } else {
                    this.approved = false;
                    this.rejected = true;
                    this.changeStatusForm.amount = 0;
                }
            },

            validateData: function(status) {
                this.send = true;

                this.$validator.validateAll().then(result => {

                    if (result && this.changeStatusForm.amount <= this.max_amount) {
                        if (status == 1) {
                            this.changeStatus(this.accept_url);
                        } else {
                            this.changeStatus(this.rejected_url);
                        }
                    }
                });
            },

            changeStatus: function(url) {
                this.loading = true;

                axios.post(url, this.changeStatusForm).then(response => {

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
        },
    }
</script>
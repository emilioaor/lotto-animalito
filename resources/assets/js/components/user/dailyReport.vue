<template>
    <section>
        <div class="alert alert-danger" v-show="send && start !== null && end !== null && start > end">
            La fecha fin debe ser mayor o igual a la fecha de inicio
        </div>

        <form id="dailyReportForm" v-bind:action="report_url" method="post" v-on:submit.prevent="validateData()">
            <input type="hidden" name="_token" v-bind:value="csrf">

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="start">Inicio</label>
                        <date-picker
                                id="start"
                                input-class="form-control"
                                placeholder="Fecha de inicio"
                                language="es"
                                v-model="start"
                        ></date-picker>
                        <input type="hidden" name="start" v-bind:value="getFormatStart()">
                        <p class="text-danger" v-show="send && start === null">
                            Este campo es requerido
                        </p>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="end">Fin</label>
                        <date-picker
                                id="end"
                                input-class="form-control"
                                placeholder="Fecha fin"
                                language="es"
                                v-model="end"
                        ></date-picker>
                        <input type="hidden" name="end" v-bind:value="getFormatEnd()">
                        <p class="text-danger" v-show="send && end === null">
                            Este campo es requerido
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <button class="btn btn-success">
                            <i class="glyphicon glyphicon-file"></i>
                            Generar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</template>

<script>
    import DatePicker from 'vuejs-datepicker';

    export default {
        props: ['report_url', 'csrf'],

        data: function() {
            return {
                start: null,
                end: null,
                send: false,
            }
        },

        methods: {
            validateData: function () {
                this.send = true;

                if (this.start !== null && this.end !== null && this.start <= this.end) {
                    this.generateReport();
                }
            },

            generateReport: function () {
                $('#dailyReportForm').submit();
            },

            getFormatStart: function () {

                if (this.start === null) {
                    return '';
                }

                const d = this.start.getDate();
                const m = this.start.getMonth() + 1;
                const y = this.start.getFullYear();

                return y + '-' + (m > 9 ? m : '0' + m) + '-' + (d > 9 ? d : '0' + d);
            },

            getFormatEnd: function () {

                if (this.end === null) {
                    return '';
                }

                const d = this.end.getDate();
                const m = this.end.getMonth() + 1;
                const y = this.end.getFullYear();

                return y + '-' + (m > 9 ? m : '0' + m) + '-' + (d > 9 ? d : '0' + d);
            }
        },

        components: { DatePicker},
    }
</script>
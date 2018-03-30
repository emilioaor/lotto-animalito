<template>
    <section>
        <date-picker
                v-bind:value="this.date"
                input-class="form-control"
                placeholder="Selecciona la fecha"
                language="es"
                @input="changeDate()"
                v-model="date"
                ></date-picker>
        <p class="text-danger" v-show="futureError">
            No puede seleccionar una fecha mayor a la actual
        </p>
    </section>
</template>

<script>
    import DatePicker from 'vuejs-datepicker';

    export default {
        props: ['value', 'url'],
        data: function() {
            return {
                date: null,
                futureError: false,
            }
        },

        mounted: function() {
            const explode = this.value.split('-');
            this.date = new Date(
                    parseInt(explode[0]),       // Año
                    parseInt(explode[1]) - 1,   // Mes
                    parseInt(explode[2])        // Dia
            );
        },

        methods: {

            changeDate: function() {
                this.futureError = false;
                const now = new Date();

                if (this.date > now) {
                    this.futureError = true;
                } else {

                    const d = this.date.getDate();
                    const m = this.date.getMonth() + 1;
                    const y = this.date.getFullYear();
                    const date = y + '-' + (m > 9 ? m : '0' + m) + '-' + (d > 9 ? d : '0' + d);

                    location.href = this.url + '&date=' + date;
                }
            }
        },

        components: { DatePicker},
    }
</script>
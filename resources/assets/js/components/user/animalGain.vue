<template>
    <section class="modal-animals">

        <button
            type="button"
            class="btn btn-primary"
            data-toggle="modal"
            data-target="#modalAnimals"
        >
            <i class="glyphicon glyphicon-hand-right"></i>
            Ganador
        </button>

        <!-- Modal animals -->
        <div id="modalAnimals" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <label for="sort_id">Sorteo {{ formatDate() }}</label>
                        <select
                                name="sort_id"
                                id="sort_id"
                                class="form-control"
                                v-model="gainForm.sort_id"
                                v-validate
                                data-vv-rules="regex:^[1-9]{1}[0-9]*$"
                                >
                            <option value="0">- Selecciona el sorteo -</option>
                            <option
                                    v-for="sort in sortList"
                                    v-bind:value="sort.id"
                                    >
                                {{ sort.time }}
                            </option>
                        </select>
                        <p class="text-danger" v-show="send && hasError('sort_id', 'regex', errors)">
                            Este campo es requerido
                        </p>
                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div
                                class="col-xs-2"
                                v-for="animal in animalList"
                            >
                                <div v-bind:class="{ 'bg-success' : gainForm.animal_id == animal.id }">
                                    <img
                                        v-bind:src="'/img/animals/' + cleanName(animal.name) + '.jpg'"
                                        v-bind:alt="animal.name"
                                        class="img-responsive"
                                        v-on:click="gainForm.animal_id = animal.id"
                                    >
                                </div>
                                <p class="text-center">{{ animal.name }}</p>
                            </div>
                        </div>

                        <div class="row" v-show="send && (errors.has('sort_id') || errors.has('animal_id') )">
                            <div class="col-xs-12">
                                <div class="alert alert-danger">
                                    Debe completar todos los datos
                                </div>
                            </div>
                        </div>

                    </div>

                    <input
                            type="hidden"
                            name="animal_id"
                            id="animal_id"
                            v-bind:value="gainForm.animal_id"
                            v-validate
                            data-vv-rules="required"
                        >
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" v-on:click="validateData()" v-show="! loading">
                            Guardar
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" v-show="! loading">
                            Cancelar
                        </button>

                        <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props: ['animals', 'sorts', 'date'],
        data: function () {
            return {
                send: false,
                loading: false,
                animalList: JSON.parse(this.animals),
                sortList: JSON.parse(this.sorts),
                gainForm: {
                    sort_id: 0,
                    date: this.date,
                    animal_id: null,
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

            cleanName: function(name) {
                name = name.toLowerCase();
                name = name.replace('á', 'a');
                name = name.replace('é', 'e');
                name = name.replace('í', 'i');
                name = name.replace('ó', 'o');
                name = name.replace('ú', 'u');

                return name;
            },

            formatDate: function() {
                return this.date.split('-').reverse().join('-');
            },

            validateData: function() {
                this.send = true;

                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.setGain();
                    }
                });
            },

            setGain: function() {
                this.loading = true;

                axios.post('/user/setGain', this.gainForm).then(response => {

                    if (response.data.success) {
                        location.href = response.data.redirect;
                    } else {
                        this.loading = false;
                    }

                }).catch(response => {
                    this.loading = false;
                });
            },
        },
    }
</script>
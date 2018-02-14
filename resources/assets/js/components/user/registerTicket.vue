<template>
    <section class="ticket-create">
        <div class="row">
            <div class="col-sm-7">

                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="row">

                            <div
                                    class="col-xs-2 text-center"
                                    v-for="animal in animalList"
                                    >

                                <div class="ticket-create__animal">
                                    <div>
                                        <h3>{{ animal.code }}</h3>
                                    </div>
                                    <div>
                                        <img :src="'/img/animals/' + cleanAnimalName(animal.name) + '.jpg'" alt="">
                                    </div>
                                    <div>
                                        <p>{{ animal.name }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

            <div class="col-sm-5">

                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-xs-5">
                                <div class="form-group">
                                    <label for="code">Código</label>
                                    <input
                                        type="text"
                                        name="code"
                                        id="code"
                                        class="form-control"
                                        placeholder="Código"
                                        v-model="code"
                                        v-on:keyup="findAnimal(); goToAmount($event)"
                                        autocomplete="off"
                                    >
                                    <p v-if="selected" class="text-success">
                                        <strong>{{ selected.name }}</strong>
                                    </p>
                                </div>
                            </div>

                            <div class="col-xs-7">
                                <div class="form-group">
                                    <label for="amount">Monto</label>
                                    <input
                                        type="text"
                                        name="amount"
                                        id="amount"
                                        class="form-control"
                                        placeholder="Monto"
                                        v-model="amount"
                                        v-bind:disabled="! this.selected"
                                        v-on:keyup="addToTicket($event)"
                                        v-validate
                                        data-vv-rules="numeric"
                                        autocomplete="off"
                                    >
                                    <p class="text-danger" v-if="errors.has('amount')">
                                        El formato no es correcto
                                    </p>
                                    <p class="text-danger" v-if="!errors.has('amount') && amount < minAmount">
                                        El minimo es {{ minAmount }}
                                    </p>
                                </div>
                            </div>

                        </div>

                        <form v-on:submit.prevent="validateData()">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-responsive">
                                        <tbody>
                                            <tr v-for="animal in animalForm.animals"
                                                v-bind:class="{'bg-danger': validateLimit(animal)}"
                                                >
                                                <td width="10%">
                                                    {{ animal.code }}
                                                </td>
                                                <td width="40%">
                                                    {{ animal.name }}
                                                </td>
                                                <td width="20%" class="text-danger">
                                                    <small v-show="validateLimit(animal)">
                                                        Limite:
                                                        {{ animal.limit }}
                                                    </small>
                                                </td>
                                                <td width="30%">
                                                    {{ animal.amount }}
                                                </td>
                                                <td>
                                                    <button
                                                        class="btn btn-danger"
                                                        type="button"
                                                        v-on:click="removeFromTicket(animal)"
                                                    >
                                                        <i class="glyphicon glyphicon-remove"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot v-if="animalForm.animals.length">
                                            <tr>
                                                <th colspan="3">Subtotal</th>
                                                <th>{{ subtotal }}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div  class="col-xs-12" v-show="animalForm.animals.length">
                                    <p>
                                        <strong>Sorteos</strong>
                                    </p>

                                    <div class="row">
                                        <div
                                            class="col-xs-6"
                                            v-for="sort in sortList">

                                            <div
                                                class="ticket-create__sort"
                                                v-on:click="sortTicketHandler(sort)"
                                            >
                                                    {{ sort.time }}

                                                    <i
                                                        class="glyphicon glyphicon-ok text-success"
                                                        v-show="hasSortInTicket(sort)"
                                                    >
                                                    </i>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12" v-if="animalForm.animals.length">

                                    <p class="ticket-create__total">
                                        <strong>Sorteos jugados:</strong>
                                        {{ animalForm.sorts.length }}
                                    </p>
                                    <p class="ticket-create__total">
                                        <strong>Total Bsf:</strong>
                                        {{ total }}
                                    </p>

                                    <div class="alert alert-danger" v-if="send && ! this.animalForm.sorts.length">
                                        <p class="text-danger">
                                            Debe seleccionar al menos un sorteo.
                                        </p>
                                    </div>

                                    <div class="alert alert-danger" v-if="send && total > myBalance">
                                        <p class="text-danger">
                                            No posee saldo suficiente.
                                        </p>
                                    </div>

                                    <div class="text-center">
                                        <button class="btn btn-success" v-if="! loading">
                                            Comprar
                                        </button>

                                        <img src="/img/loading.gif" alt="Cargando.." v-show="loading">
                                    </div>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props: ['animals', 'balance', 'sorts', 'block_balance'],
        data: function() {
            return {
                send: false,
                loading: false,
                animalList: JSON.parse(this.animals),
                sortList: JSON.parse(this.sorts),
                myBalance: parseInt(this.balance) - parseInt(this.block_balance),
                code: null,
                amount: null,
                selected: false,
                subtotal: 0,
                total: 0,
                minAmount: 1000,

                animalForm: {
                    animals: [],
                    sorts: [],
                }
            }
        },
        methods: {
            //  Limpia el nombre para las imagenes
            cleanAnimalName: function (name) {
                name = name.toLowerCase();
                name = name.replace('á', 'a');
                name = name.replace('é', 'e');
                name = name.replace('í', 'i');
                name = name.replace('ó', 'o');
                name = name.replace('ú', 'u');

                return name;
            },

            //  Busca un animal dentro de la lista
            findAnimal: function() {
                if (this.code && this.code.length > 2) {
                    this.code = null;
                    this.amount = null;
                    return this.selected = false;
                }

                for (var i in this.animalList) {
                    if (this.animalList[i].code == this.code && ! this.hasTicket()) {
                        return this.selected = this.animalList[i];
                    }
                }

                return this.selected = false;
            },

            //  Pasa al monto
            goToAmount: function (evt) {
                evt.preventDefault();
                if (evt.keyCode == 13 && this.selected) {
                    document.querySelector('#amount').focus();
                }
            },

            // Verifica que el animal no exista ya en el ticket
            hasTicket: function() {
                for (var i in this.animalForm.animals) {
                    if (this.animalForm.animals[i].code == this.code) {
                        return true;
                    }
                }

                return false;
            },

            // Agrega un animal a la jugada
            addToTicket: function(evt) {
                evt.preventDefault();
                if (evt.keyCode == 13 && this.selected && parseInt(this.amount) && this.amount >= this.minAmount) {
                    this.animalForm.animals.push({
                        code: this.selected.code,
                        name: this.selected.name,
                        amount: parseInt(this.amount),
                        limit: parseInt(this.selected.limit),
                    });

                    this.subtotal += parseInt(this.amount);
                    this.total = this.subtotal * this.animalForm.sorts.length;

                    this.amount = null;
                    this.code = null;
                    this.selected = false;
                    document.querySelector('#code').focus();
                }
            },

            //  Elimina un detalle del ticket
            removeFromTicket: function (animal) {
                for (var i in this.animalForm.animals) {
                    if (this.animalForm.animals[i].code == animal.code) {
                        this.animalForm.animals.splice(i, 1);
                        this.subtotal -= animal.amount;
                        this.total = this.subtotal * this.animalForm.sorts.length;
                    }
                }
            },

            // Agrega o elimina sorteos del ticket
            sortTicketHandler: function(sort) {
                for (var i in this.animalForm.sorts) {
                    if (this.animalForm.sorts[i].id == sort.id) {
                        //  Elimino el sorteo del ticket
                        this.animalForm.sorts.splice(i, 1);
                        this.total = this.subtotal * this.animalForm.sorts.length;

                        return true;
                    }
                }

                this.animalForm.sorts.push(sort);
                this.total = this.subtotal * this.animalForm.sorts.length;
            },

            // Verifica si un sorteo esta asignado al ticket
            hasSortInTicket: function (sort) {
                for (var i in this.animalForm.sorts) {
                    if (this.animalForm.sorts[i].id == sort.id) {
                        return true;
                    }
                }

                return false;
            },

            // Valida si no excede el limite diario
            validateLimit: function (animal) {
                return (animal.amount * this.animalForm.sorts.length) > animal.limit
            },

            // Verifica si alguno de los animalitos del ticket supera el limite diario
            validateLimitAll: function () {
                for (let i in this.animalForm.animals) {
                    if (this.validateLimit(this.animalForm.animals[i])) {
                        return true;
                    }
                }

                return false;
            },

            //  Valida la data antes de registrar el ticket
            validateData: function () {
                this.send = true;

                if (this.animalForm.sorts.length && this.animalForm.animals.length && this.total <= this.myBalance && ! this.validateLimitAll()) {
                    this.registerTicket();
                }
            },

            // Peticion para registrar ticket
            registerTicket: function() {
                this.loading = true;

                axios.post('/user/ticket', this.animalForm).then(response => {

                    if (response.data.success) {
                        location.href = response.data.redirect;
                    } else {
                        this.loading = false;
                    }
                }).catch(response => {
                    this.loading = false;
                });
            }
        }
    }
</script>
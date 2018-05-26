<template>
    <div id="calc">
        <p>This is an calc of a new components in VueJs</p>

        <form>
            <div class="form-group">
                <label for="term" class="col-sm-2 col-form-label">Term:</label>

                <input id="term" type="number" v-model="term" class="form-control" placeholder="Term"/>

            </div>
            <div class="form-group">
                <label for="rate" class="col-sm-2 col-form-label">Rate:</label>
                <input id="rate" type="number" v-model="rate" class="form-control" placeholder="Rate (%)"/>

            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="spaym" class="col-sm-2 col-form-label">Month:</label>

                    <select id="spaym" v-model="spaym" class="form-control">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="spayy" class="col-sm-2 col-form-label">Year:</label>
                    <select id="spayy" v-model="spayy" class="form-control">
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="amount" class="col-sm-2 col-form-label">Amount:</label>
                <input id="amount" type="number" v-model="amount" class="form-control" placeholder="Amount"/>

            </div>
            <div class="form-group">
                <button type="button" class="btn btn-success" @click="postPost()">Calculate it</button>
            </div>

        </form>
        <p>you entered: {{ amount }}</p>
        <b-table striped hover :items="resultData"></b-table>
    </div>

</template>

<script>

    export default {
        name: "calc",
        data: function () {
            return {
                amount: '',
                term: '',
                rate: '',
                spaym: '',
                spayy: '',
                resultData: []

            }
        },
        props: {

            columns: Array,
            sum: String,
            loading: true
        },
        methods: {
            postPost: function () {
                if ( this.amount=="" || this.term=="" || this.rate=="" || this.spaym=="" || this.spayy==""){
                    alert ("Please full all fields");
                    return;
                }
                if (this.amount=="" < this.term==""){
                    alert ("Your data isn't correct");
                    return;
                }
                this.$http.get("/calc", {
                    params: {
                        sum: this.amount,
                        term: this.term,
                        rate: this.rate,
                        spaym: this.spaym,
                        spayy: this.spayy

                    }
                })
                    .then(function (data) {

                        var array = $.map(data.body, function (value, index) {
                            return [value];
                        });
                        this.resultData = [];
                        for (var i = 0; i < array.length; i++) {
                            this.resultData.push(array[i])
                        }

                    })

            }
        }
    }
</script>

<style scoped>

</style>
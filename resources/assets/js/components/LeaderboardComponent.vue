

<template>
    <div>
       
        <div class="panel panel-default">
            <div class="panel-heading">HotLap Leaders</div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Pic</th>
                        <th>Name</th>
                        <th>Route Name</th>
                        <th>Total Distance</th>
                        <th>Date</th>
                        <th>Total Laps</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for='activity in activities'>
                        <td >{{ activity.profile }}</td>
                        <td>{{ activity.athlete.firstname }}</td>
                        <td>{{ activity.name }}</td>
                        <td>{{ activity.distance }}</td>
                        <td>{{ activity.start_date_local }}</td>
                        <td> count </td>

                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['activities'],
        mounted() {
            console.log(this.activities)
        }
    }
</script>

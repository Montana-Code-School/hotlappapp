

<template>
    <div>
       
        <div class="panel panel-default">
            <div class="panel-heading">HotLap Leaders</div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                
                    <thead class="thead-dark">
                    <tr>
                     
                        <th>Profile Pic</th>
                        <th>Name</th>
                        <th>Total Distance</th>
                        <th>Total Laps</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for='leader in leaderBoard'>
                       
                        <td> <img center :src="leader.profile" class="img-thumbnail rounded-circle border border-success fluid" width="100" height="100"/></td>
                        <td>{{ leader.firstname }}</td>
                        <td>{{ (leader.totalMiles*0.000621371).toFixed(1) }}</td>
                        <td>{{ leader.totalLaps }}</td>
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
        data() {
            return {
                leaderBoard: []
            }

        },
    // computed: {
    //     sortedLeaderBoard(){
    //         return this.leaderBoard.sort(this.sortLeaders);
    //     }
    // },

    mounted() {
        this.getLeaderBoard();
        //console.log(this.leaderBoard)
        //console.log(this.activities);
    },

    methods: {
         getLeaderBoard() {
                //loop through activitites and collect totals for a member
            this.activities.forEach(activity => {
                if(this.isActivityEnough(activity) && this.isStartDateInSelectedMonth(activity)) {
                    if(this.isAthleteInLeaderBoard(activity.athlete)) {
                        this.addActivity(activity);
                    } else {
                        this.leaderBoard.push({...activity.athlete,totalLaps:1, totalMiles:activity.distance})
                    }
                }
            })
            this.leaderBoard.sort(this.sortLeaders);
        },
        isAthleteInLeaderBoard(athlete){
            if (this.leaderBoard.length) {
                let isInLeaderBoard = false;
                this.leaderBoard.forEach(leaders => {
                    if(leaders.id === athlete.id){
                        isInLeaderBoard = true;
                    } 
                })
                return isInLeaderBoard;
            } else {
                return false;

            }
        },
        addActivity(activity) {
            let foundAthlete = this.leaderBoard.find(leader => {
                return activity.athlete.id === leader.id;
            })
            //console.log(activity.distance);
            //console.log(foundAthlete.totalMiles);
            let newTotalMiles = foundAthlete.totalMiles+activity.distance;
            foundAthlete.totalLaps=++foundAthlete.totalLaps;
            foundAthlete.totalMiles = newTotalMiles;
    
        },
        isActivityEnough(activity){
            // decimal moved 4 place left 4972.873 
            if(activity.distance < .4972873) {
                return false;
            } else {
                return true;
            }
        },
        sortLeaders(athleteA, athleteB) {
            if(athleteA.totalLaps < athleteB.totalLaps) {
                return 1;
            } else if (athleteA.totalLaps > athleteB.totalLaps) {
                return -1;
            } else {
                return 0;
            }
        },
        isStartDateInSelectedMonth(activity){
            let today = this.$moment();
            let activityDate = this.$moment(activity.start_date_local)
            console.log(activity.start_date_local);
            console.log(activityDate);
            return today.isSame(activityDate, 'month');
        }
    }
}
</script>

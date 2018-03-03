

<template>
    <div>
      <div>
       <b-container fluid>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><b>HotLap Leaders by Month</b></h4>
            </div>
            <b-dropdown id="ddown-lg" text="Select Month" class="m-md-2">
            <select v-model="selectedMonth">
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
            </b-dropdown>
            </div>
        </b-container>
        </div>

        <div>
          <b-container fluid>    
            <div class="panel-body">
                <table v-model="small" class="table table-bordered table-striped">
                
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
                       
                        <td> <b-img center :src="leader.profile" fluid alt="Fluid image" class="img-thumbnail rounded-circle border border-success" width="100" height="100"/></td>
                        <td>{{ leader.firstname }}</td>
                        <td>{{ (leader.totalMiles*0.000621371).toFixed(1) }}</td>
                        <td>{{ leader.totalLaps }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            </b-container>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['activities'],
        data() {
            return {
                leaderBoard: [],
                selectedMonth: this.$moment().format('MM')
            }
            

        },
        watch: {
            // whenever selectedMonth changes, this function will run
            selectedMonth: function(newMonth){
                this.leaderBoard = [];
                this.getLeaderBoard();
                
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
            let selectedDateYear = this.$moment().year();
            let selectedDateMonth = this.selectedMonth;
            let selectedDate = this.$moment(selectedDateYear + '-' + selectedDateMonth + '-01')
            let activityDate = this.$moment(activity.start_date_local)
            //console.log(activity.start_date_local);
            //console.log(activityDate);
            
            return selectedDate.isSame(activityDate, 'month');
           
            let month = activityDate.month();
            //console.log(month);
            console.log(selectedDate);
            if (parseInt(month) === parseInt(this.selectedMonth)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
</script>

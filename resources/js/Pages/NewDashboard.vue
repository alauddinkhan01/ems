<template>
  <div class="flex h-screen bg-gray-200 font-roboto">
    <Sidebar />

    <div class="flex-1 flex flex-col overflow-hidden">
      <Header :title="title"/>
      

      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-6 py-8">
          <!-- <p>{{ $page.component }}</p> -->
          <!-- {{ $moment(userLatestAttendance.latest_attendance.check_in).format('YYYY-MM-DD') }}----{{ $moment().format('YYYY-MM-DD') }}
          {{$moment(userLatestAttendance.latest_attendance.check_in).format('YYYY-MM-DD') === $moment().format('YYYY-MM-DD')}} -->
          <button class="w-40 h-40 rounded-full bg-indigo-800 hover:bg-indigo-600 text-white" @click="checkIn">Check In</button>
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import Header from "./Components/Header.vue";
import Sidebar from "./Components/Sidebar.vue";
export default{
  props:['userLatestAttendance','currentDate'],
  data(){
    return{
      title:"Dashboard"
    }
  },
  components:{
    Header,
    Sidebar
  },
  methods:{
    checkIn(){
      if (confirm('Do you want to Check-In')) {
        this.axios.get(route('check-in'));
      }
    }
  }
}
</script>
